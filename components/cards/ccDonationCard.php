<?php

namespace app\core\components\cards;

class ccDonationCard
{

    public function __construct()
    {
    }

    public function showCCDonationCards($CCDonations,$type = 'posted',$ccID='') {
        switch ($type) {
            case 'posted':
                foreach ($CCDonations as $CCDonation) {
                    $this->showPostedCCDonationCard($CCDonation);
                }
                break;
            case 'ongoing':
                foreach ($CCDonations as $CCDonation) {
                    $this->showOngoingCCDonationCard($CCDonation,$ccID);
                }
                break;
            case 'completed':
                foreach ($CCDonations as $CCDonation) {
                    $this->showCompletedCCDonationCard($CCDonation,$ccID);
                }
                break;
        }

    }

    private function showPostedCCDonationCard($CCDonation) :void {
        echo sprintf('<div class="CC-donation-card" id="%s">',$CCDonation['ccDonationID']);
        echo sprintf('<div class="CC-donation-header">');
        echo sprintf('<h4> %s </h4>',$CCDonation['subcategoryName']);
        echo sprintf('</div>');
        echo sprintf('<div class="CC-donation-details">');
        echo sprintf('<p> <span> Amount: </span> %s </p>',$CCDonation['amount']);
        echo sprintf('<p> <span> Posted By: </span> %s CC </p>',$CCDonation['toCity']);
        echo sprintf('</div>');
        echo sprintf("<div class='CC-donation-btns'><button class='CC-donation-primary accept'>Accept</button></div>");
        echo "</div>";
    }

    private function showOngoingCCDonationCard($CCDonation,string $ccID) : void {
        $accepted = empty($CCDonation['fromCC']) ? 0 : 1 ;
        echo sprintf('<div class="CC-donation-card" id="%s">',$CCDonation['ccDonationID']);
        echo sprintf('<div class="CC-donation-header">');
        echo sprintf('<h4> %s </h4>',$CCDonation['subcategoryName']);
        if($accepted) {
            echo sprintf('<strong class="color-secondary-font-point9rem"> Accepted </strong>');
        } else {
            echo sprintf('<strong class="color-danger-font-point9rem"> Not accepted </strong>');
        }
        echo sprintf('</div>');
        echo sprintf('<div class="CC-donation-details">');
        echo sprintf('<p> <span> Amount: </span> %s </p>',$CCDonation['amount']);
        if($accepted) {
            if($ccID === $CCDonation['toCC'])
                echo sprintf('<p> <span> Accepted By: </span> %s CC </p>',$CCDonation['fromCity']);
            else
                echo sprintf('<p> <span> Posted By: </span> %s CC </p>',$CCDonation['toCity']);
        } else {
            echo sprintf('<p> <span> Date posted: </span> %s  </p>',$CCDonation['createdDate']);
        }
        echo sprintf('</div>');
        if($accepted) {
            echo sprintf("<div class='CC-donation-btns'><button class='CC-donation-primary view'>View</button></div>");
        }
        else {
            echo sprintf("<div class='CC-donation-note'><p> %s </p></div>", $CCDonation['notes']);
        }
        echo "</div>";
    }

    private function showCompletedCCdonationCard($CCDonationCard,$ccID) : void {
        echo sprintf('<div class="CC-donation-card" id="%s">',$CCDonationCard['ccDonationID']);
        echo sprintf('<div class="CC-donation-header">');
        echo sprintf('<h4> %s </h4>',$CCDonationCard['subcategoryName']);
        echo sprintf('<p class="font-point8rem"> %s </p>',$CCDonationCard['completedDate']);
        echo sprintf('</div>');
        echo sprintf('<div class="CC-donation-details">');
        echo sprintf('<p> <span> Amount: </span> %s </p>',$CCDonationCard['amount']);
        if($ccID === $CCDonationCard['toCC'])
            echo sprintf('<p> <span> Accepted By: </span> %s CC </p>',$CCDonationCard['fromCity']);
        else
            echo sprintf('<p> <span> Posted By: </span> %s CC </p>',$CCDonationCard['toCity']);
        echo sprintf('</div>');
        echo sprintf("<div class='CC-donation-btns'><button class='CC-donation-primary view'>View</button></div>");
        echo "</div>";
    }

}