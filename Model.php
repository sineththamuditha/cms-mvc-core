<?php

namespace app\core;

use DateTime;

abstract class Model
{

    // following are the possible validation checks that can arise

    public static string $REQUIRED = 'required';
    public static string $EMAIL = 'email';
    public static string $MIN = 'min';
    public static string $MAX = 'max';
    public static string $MATCH = 'match';
    public static string $UNIQUE = 'unique';
    public static string $CONTACT = 'contact';
    public static string $PASSWORD = 'password';
    public static string $nic = 'nic';
    public static string $DATE = 'date';

    public static string $TIME = 'time';

    public static string $POSITIVE = 'positive';
    public static string $NOTZERO = 'notzero';

    public static string $LONGITUDE = 'longitude';
    public static string $LATITUDE = 'latitude';

    // array to hold errors after validation
    // errors are in the format =>   [ 'attribute' => ['first error', ... ] , ...... ]
    public array $errors = [];


    /**
     * @param array $data
     * @return void
     */
    public function getData(array $data): void {

        // get data from an array
        foreach ($data as $key => $value) {

            // if this class (or inherited class this attribute assign the value
            if( property_exists($this, $key) ) {
                $this->{$key} = $value;
            }

        }

    }

    /**
     * @return array
     */
    abstract public function rules(): array;
    // abstract function to specify rules

    /**
     * @param $data
     * @return bool
     */
    public function validate($data): bool {

        // traverse through rules of the model to validate data
        foreach ($this->rules() as $attribute => $rules) {

            // if the value is not provided assign empty string
            $value = $data[$attribute] ?? '';

            // since one attribute can more than one , we have check each of them
            foreach ($rules as $rule) {

                $ruleName = $rule;

                // if rule is an array assign first name
                if( !is_string($ruleName) ) {
                    $ruleName = $rule[0];
                }

                // if required it cannot be empty
                if( $ruleName === self::$REQUIRED && !$value ) {
                    $this->addRuleError($attribute, self::$REQUIRED);
                }

                // if rule is email check whether it is an valid email
                if( $ruleName === self::$EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL) ) {
                    $this->addRuleError($attribute, self::$EMAIL);
                }

                // check whether string is greater than minimum length
                if( $ruleName === self::$MIN && strlen($value) < $rule['min'] ) {
                    $this->addRuleError($attribute, self::$MIN, $rule);
                }

                // check whether string is less than maximum length
                if( $ruleName === self::$MAX && strlen($value) > $rule['max'] ) {
                    $this->addRuleError($attribute, self::$MAX, $rule);
                }

                // if it matches to attribute of the same model
                if( $ruleName === self::$MATCH && $value !== $data[$rule['match']] ) {
                    $this->addRuleError($attribute, self::$MATCH, $rule);
                }

                // check whether it is unique in this tables column
                if( $ruleName === self::$UNIQUE ) {
                    $className = $rule['class'];
                    $uniqueAttr = $rule['attribute'] ?? $attribute;
                    $tableName = $className::table();
                    $statement = Application::$app->database->pdo->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr");
                    $statement->bindValue(":attr", $value);
                    $statement->execute();
                    $record = $statement->fetchObject();
                    if( $record ) {
                        $this->addRuleError($attribute, self::$UNIQUE, ['field' => $attribute]);
                    }
                }

                // if this is a valid contact number
                if( $ruleName === self::$CONTACT && !preg_match('/^0[0-9]{9}$/', $value) ) {
                    $this->addRuleError($attribute, self::$CONTACT);
                }

                // if this matches the rules of the password
                // must contain an uppercase letter
                // must contain a lowercase letter
                // must contain a number
                // must be at least 8 characters long
                if( $ruleName === self::$PASSWORD && !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $value) ) {
                    $this->addRuleError($attribute, self::$PASSWORD);
                }

                // if this is a valid nic number in both formats
                if( $ruleName === self::$nic && !(preg_match('/^[0-9]{9}[vV]$/', $value) || preg_match('/^[0-9]{12}$/', $value)) ) {
                    $this->addRuleError($attribute, self::$nic);
                }

                // if this is a valid date
                if( $ruleName === self::$DATE && date('Y-m-d') >= $value && $this->validateDateorTime('Y-m-d',$value)) {
                    $this->addRuleError($attribute, self::$DATE);
                }

                // if this is a valid time
                if( $ruleName === self::$TIME && $this->validateDateorTime('H:i:s',$value) ) {
                    $this->addRuleError($attribute, self::$TIME);
                }

                // if this is a positive number
                if( $ruleName === self::$POSITIVE && $value < 0 ) {
                    $this->addRuleError($attribute, self::$POSITIVE);
                }

                // if this is not zero
                if( $ruleName === self::$NOTZERO && $value == 0 ) {
                    $this->addRuleError($attribute, self::$NOTZERO);
                }

                // if this is a valid longitude
                if( $ruleName === self::$LONGITUDE && ($value > 81.8914 || $value < 79.695) ) {
                    $this->addRuleError($attribute, self::$LONGITUDE);
                }

                // if this is a valid latitude
                if( $ruleName === self::$LATITUDE && ($value > 9.8167 || $value < 5.9167) ) {
                    $this->addRuleError($attribute, self::$LATITUDE);
                }

            }

        }

        return empty($this->errors);

    }

    /**
     * @param $attribute
     * @param $rule
     * @param array $params
     * @return void
     */
    private function addRuleError($attribute, $rule, array $params = []): void {

        // private function to be used by validate function to add errors
        $message = $this->errorMessages()[$rule] ?? '';

        // if the rule name is an array , replace the params
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }

        // add the error to the errors array
        $this->errors[$attribute][] = $message;

    }

    /**
     * @param $attribute
     * @param $message
     * @return void
     */
    public function addError($attribute, $message): void {

        // public function to be used by controller to add errors
        $this->errors[$attribute][] = $message;

    }

    /**
     * @return string[]
     */
    public function errorMessages(): array {

        // function to return error messages
        return [
            self::$REQUIRED => 'This field is required',
            self::$EMAIL => 'This field must be a valid email address',
            self::$MIN => 'Min length of this field must be equal or greater than {min}',
            self::$MAX => 'Max length of this field must be equal or less than {max}',
            self::$MATCH => 'This field must be the same as {match}',
            self::$UNIQUE => '{field} already exists',
            self::$CONTACT => 'This field must be a valid contact number',
            self::$nic => 'This field must be a valid NIC number',
            self::$DATE => 'This field must be a future date',
            self::$TIME => 'This field must be a valid time',
            self::$POSITIVE => 'This field must be a positive number',
            self::$NOTZERO => 'This field must be a non-zero number',
            self::$LONGITUDE => 'Longitude must belong to Sri Lanka',
            self::$LATITUDE => 'Latitude must belong to Sri Lanka',
        ];

    }

    /**
     * @param $attribute
     * @return bool
     */
    public function hasError($attribute): bool {

        // check whether the attribute has an error
        return $this->errors[$attribute] ?? false;

    }

    /**
     * @param $attribute
     * @return string
     */
    public function getFirstError($attribute): string {

        // get the first error of the attribute
        return $this->errors[$attribute][0] ?? '';

    }

    /**
     * @return void
     */
    public function reset(): void {

        // reset the attributes to null
        foreach ($this->rules() as $attribute => $rules) {

            // if an int
            if( is_int($this->{$attribute})) {
                $this->{$attribute} = 0;
            }

            // if a float
            else if( is_float($this->{$attribute})) {
                $this->{$attribute} = 0.0;
            }

            // if a string
            else if( is_string($this->{$attribute})) {
                $this->{$attribute} = '';
            }

            // if an array
            else if( is_array($this->{$attribute})) {
                $this->{$attribute} = [];
            }

            // else assign null
            else {
                $this->{$attribute} = null;
            }

        }

    }

    /**
     * @param string $format
     * @param mixed $value
     * @return bool
     */
    private function validateDateorTime(string $format, mixed $value): bool
    {

        // function to validate date or time
        $d = DateTime::createFromFormat($format, $value);

        // return if not empty and format matches
        return $d && $d->format($format) === $value;

    }

}