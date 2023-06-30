<?php

namespace app\core\components\form;

class form
{

    public static function begin($action, $method,$cols =1) : form
    {
        $colClass = $cols == 1 ? "form-grid-1" : "form-grid-2-2";
        echo sprintf("<form action='%s' method='%s' class='%s' enctype='multipart/form-data'>", $action, $method, $colClass);
        return new form();
    }

    public static function end() : void
    {
        echo '</form>';
    }

    public function inputField($model, $label, $type, $attribute, $id = "",): void
    {
        echo "<div class='form-group'>";
        echo sprintf('<label class="form-label">%s :</label>', $label);
        if($id == "") {
            echo sprintf('<input type="%s" name="%s" value="%s" class="basic-input-field" size="40">', $type, $attribute, $model->{$attribute});
        } else {
            echo sprintf('<input type="%s" name="%s" value="%s" id="%s" class="basic-input-field" size="40">', $type, $attribute, $model->{$attribute}, $id);
        }
        echo sprintf('<span class="error">%s</span>', $model->getFirstError($attribute));
        echo "</div>";
    }

    public function textArea($model, $label, $attribute,$size = [10,30],$id=''): void
    {
        echo "<div class='form-group'>";
        echo sprintf('<label class="form-label">%s :</label>', $label);
        echo sprintf("<textarea name='%s' rows='%s' cols='%s' class='basic-text-area' %s>%s</textarea>", $attribute, $size[0], $size[1],empty($id) ? $id: "id='". $id ."'", $model->{$attribute});
        echo sprintf('<span class="error">%s</span>', $model->getFirstError($attribute));
        echo "</div>";
    }

    public function dropDownList($model, $label, $attribute, $options, $id =""): void
    {
        echo "<div class='form-group'>";
        echo sprintf('<label class="form-label">%s :</label>', $label);
        if($id == ""){
            echo sprintf("<select name='%s' class='basic-input-field'>", $attribute);
        }else{

            echo sprintf("<select name='%s' id='%s' class='basic-input-field'>", $attribute, $id);
        }
        echo "<option value=''>Select</option>";
        foreach ($options as $key => $value) {
            if($attribute){
                $selected = $model->{$attribute} === $key ? 'selected' : '';
            }else{
                $selected = '';
            }
            echo sprintf("<option value='%s' %s>%s</option>", $key, $selected, $value);
        }
        echo '</select>';
        echo sprintf('<span class="error">%s</span>', $model->getFirstError($attribute));
        echo "</div>";
    }

    public function checkBox($model,$label,$attribute,$id='') {
        $attributeValue = $attribute ? $model->{$attribute} : '';
        echo "<div>";
        echo sprintf("<label>%s : </label>",$label);
        if($id == '') {
            echo sprintf("<input type='checkbox' name='%s' value='%s'>",$attribute,$attributeValue);
        }
        else {
            echo sprintf("<input type='checkbox' name='%s' value='%s' id='%s'>",$attribute,$attributeValue,$id);
        }
        echo sprintf('<span class="error">%s</span>', $model->getFirstError($attribute));
        echo "</div>";
    }

    public function button($label, $type = 'submit', $id = '',$classes =[]) : void
    {
        if($classes) {
            $classes = implode(' ',$classes);
        }
        else {
            $classes = 'btn-primary';
        }
        if($id == ""){
            echo sprintf("<button type='%s' class='%s'>%s</button>", $type,$classes ,$label);
        }else{
            echo sprintf("<button type='%s' id='%s' class='%s'>%s</button>", $type, $id,$classes ,$label);
        }
    }

    public function formHeader($heading) : void
    {
        echo sprintf("<h3>%s</h3>", $heading);
    }

    public function fileInput($model,$label, $id = '') : void
    {
        echo "<div class='form-group'>";
        echo sprintf('<label class="form-label">%s :</label>', $label);
        if($id == "") {
            echo sprintf('<input type="file" name="%s" class="basic-input-field" size="40">', $id);
        } else {
            echo sprintf('<input type="file" name="%s" id="%s" class="basic-input-field" size="40">', $id, $id);
        }
        echo sprintf('<span class="error">%s</span>', $model->getFirstError($id));
        echo "</div>";
    }

    public function inputFieldwithPlaceholder($model, $label, $type, $attribute,$placeholder, $id = ""): void
    {
        echo "<div class='form-group'>";
        echo sprintf('<label class="form-label">%s :</label>', $label);
        if($id == "") {
            echo sprintf('<input type="%s" name="%s" value="%s" class="basic-input-field" size="40" placeholder="%s">', $type, $attribute, $model->{$attribute},$placeholder);
        } else {
            echo sprintf('<input type="%s" name="%s" value="%s" id="%s" class="basic-input-field" size="40" placeholder="%s">', $type, $attribute, $model->{$attribute}, $id, $placeholder );
        }
        echo sprintf('<span class="error">%s</span>', $model->getFirstError($attribute));
        echo "</div>";
    }
}
