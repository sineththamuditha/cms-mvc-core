<?php

namespace app\core\components\cards;

use app\models\donationModel;
use app\models\subdeliveryModel;

class deliveryCard
{
    private array $subcategories = [];
    private array $destinations = [];

    public function __construct()
    {
        $this->subcategories = donationModel::getAllSubcategories();
        $this->destinations = subdeliveryModel::getDestinations();
        foreach ($this->destinations as $key => $destination) {
            $address = explode(",", $destination);
            $this->destinations[$key] = trim(end($address));
        }

    }

    public function showDeliveryCard($data, $type = ""): void
    {
        foreach ($data as $item) {
            $item['startCity'] = $this->destinations[$item['start']];
            $item['endCity'] = $this->destinations[$item['end']];
            $item['subcategoryName'] = $this->subcategories[$item['item']];
            $this->showCard($item, $type);
        }
    }

    private function showCard($data, $type): void
    {

        switch ($type) {
            case "directDonations":
                echo sprintf("<div class='delivery-card' id='%s'>", $data['donationID']);
                echo sprintf("<div class='delivery-card-type'><h4>%s</h4></div>", "Direct Donation");
                echo sprintf("<div class='delivery-card-header'><h4>%s</h4><p class='log-del-status-cancelled'>%s</p></div>", $data['subcategoryName'], $data['status']);
//                var_dump($data);
                echo "<div class='log-del-details'>";
                echo sprintf("<p><strong>Start: </strong>%s</p>", $data['startCity']);
                echo sprintf("<p><strong>Dest: </strong>%s</p>", $data['endCity']);
                echo sprintf("<p><strong>Created: </strong>%s</p>", $data['date']);
                break;

            case "acceptedRequests":
                echo sprintf("<div class='delivery-card' id='%s'>", $data['acceptedID']);
                echo sprintf("<div class='delivery-card-type'><h4>%s</h4></div>", "Accepted Request");
                echo sprintf("<div class='delivery-card-header'><h4>%s</h4><p class='log-del-status-cancelled'>%s</p></div>", $data['subcategoryName'], $data['deliveryStatus']);
//                var_dump($data);
                echo "<div class='log-del-details'>";
                echo sprintf("<p><strong>Start: </strong>%s</p>", $data['startCity']);
                echo sprintf("<p><strong>Dest: </strong>%s</p>", $data['endCity']);
                echo sprintf("<p><strong>Created: </strong>%s</p>", $data['approvedDate']);
                break;

            case "ccDonations":
                echo sprintf("<div class='delivery-card' id='%s'>", $data['ccDonationID']);
                echo sprintf("<div class='delivery-card-type'><h4>%s</h4></div>", "CCDonation");
                echo sprintf("<div class='delivery-card-header'><h4>%s</h4><p class='log-del-status-cancelled'>%s</p></div>", $data['subcategoryName'], $data['deliveryStatus']);
                echo "<div class='log-del-details'>";
                echo sprintf("<p><strong>Start: </strong>%s</p>", $data['startCity']);
                echo sprintf("<p><strong>Dest: </strong>%s</p>", $data['endCity']);
                echo sprintf("<p><strong>Created: </strong>%s</p>", $data['date']);
                break;

            default:
                return;
        }


        echo "</div>";
        echo "<div class='log-del-btns'>";
        echo sprintf("<button class='log-del-primary view-btn' value='%s' id='%s' >More Details</button>",$type,$data['subdeliveryID']);
//        echo "<button class='log-del-primary'><i class='material-icons'>location_on</i>Route</button>
        echo "</div></div>";

    }


}

