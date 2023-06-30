<?php

namespace app\core\components\cards;

class donationCard
{

    public function displayCards(array $donations) : void {
        foreach ($donations as $donation) {
            $this->donationCard($donation);
        }
    }

    private function donationCard($donation) : void {
        echo  sprintf("<div class='don-del-card' id='%s'><div class='don-del-header'>",$donation['donationID']);
        echo  sprintf("<h4>%s</h4>",$donation['subcategoryName']);
        echo  sprintf("<p class='don-del-status-cancelled'> %s</p></div>", $donation['deliveryStatus']);
        echo  sprintf("<div class='don-del-details'><p><strong>Amount:</strong> %s</p><p></p>",$donation['amount']);
        $this->showRelevantDestination($donation);
        echo  sprintf("<p><strong>Created:</strong>  %s</p>",$donation['date']);
        echo  "</div><div class='don-del-btns'>";
        echo  "<button class='don-del-primary " . ($donation['deliveryStatus'] === "Completed" ? '':'')."'>More Details</button>";
        echo  "</div></div>";
    }

    private function showRelevantDestination($donation) : void {
        if($_SESSION['userType'] === "donor") {
            echo "<p><strong>Donated To:</strong> ". $donation['city'] . " CC</p>";
        } else {
            echo "<p><strong>Donated By: </strong> ".$donation['username']."</p>";
        }
    }



}