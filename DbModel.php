<?php

namespace app\core;

abstract class DbModel extends Model
{

    abstract public function table() : string;

    abstract public function attributes() : array;

    abstract public function primaryKey(): string;

    public static function getPrimaryKey() : string
    {
        return (new static())->primaryKey();
    }

    public static function getModel($where)
    {
        return (new static())->findOne($where);
    }

    public static function getAllData($where = [], $orderBy = []) : array
    {
        return (new static())->retrieve($where, $orderBy);
    }

    // base function to save a record on the table of the model
    // here columns to be saved will be taken from the attributes() function in the relevant model
    // and values will be the currently holding values of the relevant attribute of the model
    public function save(): bool
    {
        $table = $this->table();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $statement = self::prepare("INSERT INTO $table (".implode(',', $attributes).") VALUES (".implode(',', $params).")");

        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }
        $statement->execute();
        return true;
    }

    public static function prepare($sql): \PDOStatement
    {
        return Application::$app->database->pdo->prepare($sql);
    }

    //to simplify select queries which get only one row as an object of the relevant class
    public function findOne($where) : DbModel | bool | null
    {
        $tableName = static::table();
        $attributes = array_keys($where);
        $sql = implode("AND ", array_map(fn($attr) => "`$attr` = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();
        return $statement->fetchObject(static::class);
    }

    //to simplify select queries which get all matching columns with option to order them
    // $where = ['id' => 1, 'name' => 'john']
    // $orderBy = ['ASC' => ['id', 'name']]
    public function retrieve(array $where = [], array $orderBy = []): array
    {
        $tableName = static::table();
        $sql = "Select * from $tableName";
        if($where) {
            $attributes = array_keys($where);
            $sql .= " WHERE ".implode(" AND ", array_map(fn($attr) => "$attr = '$where[$attr]'", $attributes));
        }
        if($orderBy) {
            $order = array_keys($orderBy)[0];
            $sql .= " ORDER BY ". implode(",", $orderBy[$order]) . " " . $order;
        }
        $statement = self::prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    //to simplify delete queries
    // $where = ['id' => 1, 'name' => 'john']
    public function delete($where): bool
    {
        $tableName = static::table();
        $attributes = array_keys($where);
        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("DELETE FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();
        return true;
    }

    //to simplify update queries
    // $where = ['id' => 1, 'name' => 'john']
    // $data = ['name' => 'john', 'age' => 20]
    // but the thing is we cannot use the same column for where and set
    public function update(array $where,array $data): bool
    {
            $tableName = static::table();
            $attributes = array_keys($where);
            $setData = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
            $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr ", $attributes));
            $statement = self::prepare("UPDATE $tableName SET $setData WHERE $sql");
            foreach ($where as $key => $item) {
                $statement->bindValue(":$key", $item);
            }
            foreach ($data as $key => $item) {
                $statement->bindValue(":$key", $item);
            }
            return $statement->execute();
    }

    //to retrieve data from two tables
    //example: $tableName = 'users'; <- table name
    // $onColumn = 'id'; <- column name of the first table to join on. if on both tables is same , only have to specify here
    // $whereCondition = ['id' => 1, 'name' => 'John']; <- WHERE clause
    // $orderBy = ['ASC' => ['id', 'name']]; <- ORDER BY clause
    // $col = 'id'; <- column name of the second table to join on. only have to specify if they differ
    public function retrieveWithJoin(string $tableName, string $onColumn, array $whereCondition = [], array $orderBy = [],string $col =''): array {
        $table = static::table();
        $sql = '';
        if($col !== '') {
            $sql = "SELECT * FROM $table INNER JOIN $tableName ON $table.$col = $tableName.$onColumn";
        }
        else {
            $sql = "SELECT * FROM $table INNER JOIN $tableName ON $table.$onColumn = $tableName.$onColumn";
        }

        if($whereCondition) {
            $attributes = array_keys($whereCondition);
            $where = implode("AND ", array_map(fn($attr) => "$attr = '$whereCondition[$attr]'", $attributes));
            $sql .= " WHERE $where";
        }
        if($orderBy) {
            $order = array_keys($orderBy)[0];
            $sql .= " ORDER BY ". implode(",", $orderBy[$order]) . " " . $order;
        }
        $statement = self::prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }


    //to run custom queries
    //example: $sql = "SELECT * FROM users INNER JOIN ..."; <- without WHERE, ORDER and LIKE clauses
    // $where = ['id' => 1, 'name' => 'John']; <- WHERE clause, can specify if not equal // $where = ['!id' => 1, '!name' => '!John'];
    // $sort = ['ASC' => ['id', 'name']]; <- ORDER BY clause
    // $search = ['search' , ['name','John']]; <- LIKE clause
    // $groupBy = 'id'; <- GROUP BY clause
    // $having = ['id' => 1, 'name' => 'John']; <- HAVING clause
    // $fetchMode = \PDO::FETCH_ASSOC; <- fetch mode
    public static function runCustomQuery(string $sql, array $where = [], array $sort = [], array $search = [],string $groupBy = "",array $having = [] ,string|int $fetchMode = \PDO::FETCH_ASSOC): array {

        $wherestmnt = ' WHERE ';

        // structure where array to be like "id = 1", "name != 'John'"
        // then implode it with AND, then append it to where statement
        if($where) {
            $where = implode("AND ", array_map(function($attr)  use ($where)
                                                {
                                                    if($attr[0] === '!') {
                                                        return substr($attr, 1) . " != '$where[$attr]'";
                                                    }
                                                    else {
                                                        return "$attr = '$where[$attr]'";
                                                    }
                                                }, array_keys($where)));
            $wherestmnt .= " $where";
        }

        // structure search array to be like "name LIKE '%John%'"
        // then implode it with OR, then append it to where statement
        if(!empty($search[0])) {
            $wherestmnt = $wherestmnt === " WHERE " ? $wherestmnt : $wherestmnt . " AND ";
            $wherestmnt .= implode(" OR ", array_map(fn($attr) => "$attr LIKE '%$search[0]%' ", $search[1]));
        }

        // append where statement to sql
        $sql .= $wherestmnt === " WHERE " ? '' : $wherestmnt;

        // if there is no group by clause and there is order by clause
        // append order by clause to sql
        if( empty($groupBy) && (!empty($sort[array_key_first($sort)]))) {
            $order = array_keys($sort)[0];
            $sql .= " ORDER BY ". implode(",", $sort[$order]) . " " . $order;
        }

        // if there is group by clause
        // append group by clause to sql
        if($groupBy !== "") {
            $sql .= " GROUP BY $groupBy";
        }

        // if there is having clause
        // structure having array to be like "id = 1", "name != 'John'"
        // then implode it with AND, then append it to sql
        if(!empty($having)) {
            $having = implode("AND ", array_map(fn($attr) => "$attr = '$having[$attr]'", array_keys($having)));
            $sql .= " HAVING $having";
        }

        // if there is group by clause and there is order by clause
        // append order by clause to sql
        if( !empty($groupBy) && (!empty($sort[array_key_first($sort)])) ) {
            $order = array_keys($sort)[0];
            $sql .= " ORDER BY ". implode(",", $sort[$order]) . " " . $order;
        }

        $statement = self::prepare($sql);
        $statement->execute();
        return $statement->fetchAll($fetchMode);
//        return [$sql];
    }

}