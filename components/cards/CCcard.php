<?php

namespace app\core\components\cards;

use app\models\choModel;

class CCcard
{
    private array $chos = [];

    public function __construct() {
        $this->chos = CHOModel::getCHOs();
    }

    public function displayCCs(array $ccs = []): void
    {
        foreach ($ccs as $cc) {
            $this->CCCard($cc);
        }

    }

    private function CCCard($cc): void {
        echo sprintf("<div class='cc-card' title=%s>", $cc['ccID']);
        echo "<div class='cc-card-header'>";
        echo sprintf("<h1>%s</h1>",$cc['city']);
        echo "</div>";
        echo sprintf("<div class='cc-map'><div  id='%s' style='%s'></div></div>", $cc['ccID'],"width: 100%; height: 300px;");
        echo "<div class='form-grid-1'>";
        echo sprintf("<div class='form-group'><label class='form-label'>Address</label><input type='text' disabled class='basic-input-field' value='%s'></div></div>", $cc['address']);
        echo sprintf("<div class='form-grid-2-2'><div class='form-group'><label class='form-label'>District</label><input id='%s' class='basic-input-field' value='%s' disabled></div>",$cc['cho'] ,$this->chos[$cc['cho']]);
//        echo "<p class='details-group'>";
        echo sprintf("<div class='form-group'><label class='form-label'>Contact Number</label><input class='basic-input-field' value='%s' disabled></div>", $cc['contactNumber']);
//        echo "</p>";
        echo sprintf("<div class='form-group'><label class='form-label'>Fax Number</label><input type='text' class='basic-input-field' value='%s' disabled></div>",$cc['fax']);
        echo sprintf("<div class='form-group'><label class='form-label'>Email</label><input type='text' class='basic-input-field' value='%s' disabled><a class='mail-icon' href='mailto:%s'><i class='material-icons'>email</i></a></div>",$cc['email'],$cc['email']);
        echo '</div>';
        echo "</div>";
    }
}
