<?php

namespace app\core\components\cards;

use app\models\deliveryModel;

class logisticDelivery
{
    private array $chos = [];

    public function __construct() {
        $this->chos = deliveryModel::getdelivery();
    }

    public function displayCCs(array $deliveries = []): void
    {
        foreach ($deliveries as $delivery) {
            $this->deliveryCard($delivery);
        }
    }

    private function deliveryCard($cc): void {
        echo sprintf("<div class='cc-card' title=%s>", $cc['ccID']);
        echo "<div class='cc-card-header'>";
        echo sprintf("<h1>%s</h1>",$cc['city']);
        echo "</div>";
        echo sprintf("<div class='cc-map'><div  id='%s' style='%s'></div></div>", $cc['ccID'],"width: 100%; height: 200px;");
        echo "<div class='cc-details'>";
        echo sprintf("<p><strong>Address : </strong>%s</p>", $cc['address']);
        echo sprintf("<p><strong>District : </strong><span id='%s' class='cho'>%s</span></p>",$cc['cho'] ,$this->chos[$cc['cho']]);
        echo "<div class='details-group'>";
        echo sprintf("<p><strong>Contact : </strong>%s</p>
                <div class='icon-button'><i class='material-icons'>call</i></div>", $cc['contactNumber']);
        echo "</div>";
        echo sprintf("<div class='details-group'>
                <p><strong>Fax : </strong>%s</p>
                <div class='icon-button'><i class='material-icons'>fax</i></div>
            </div>",$cc['fax']);
        echo '</div>';
        echo "</div>";
    }
}