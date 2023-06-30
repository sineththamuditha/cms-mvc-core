<?php

namespace app\core\components\cards;

class unverifiedDoneeCard
{

    public function __construct() {

    }

    public function displayUnverifiedDonees(array $donees)
    {
        foreach ($donees['individuals'] as $donee) {
            if($donee['verificationStatus'] == 0) {
                $this->unverifiedDoneeCard($donee);
            }
        }

        foreach ($donees['organizations'] as $donee) {
            if($donee['verificationStatus'] == 0) {
                $this->unverifiedDoneeCard($donee);
            }
        }
    }

    private function unverifiedDoneeCard($donee): void {
        echo "<div class='unver-user-card'>";
        echo "<div class='profile-section'>";
        echo "<img src='/CommuSupport/public/src/icons/lockedDonee/lock_acc.jpg'
                 alt='Profile Picture'>";
        echo "<div class='action-buttons'>";
        echo sprintf("<button class='btn btn-primary verify' value='%s'>Verify</button>", $donee['doneeID']);
        echo "</div>";
        echo "</div>";
        echo "<div class='info-section'>";
        $this->displayName($donee);
        echo "<div class='stat-card'>";
        echo "<p>Mobile number: </p>";
        echo sprintf("<p>%s</p>", $donee['mobileVerification'] ? 'Verified' : 'Not Verified');
        echo "</div>";
        echo "<div class='stat-card'>";
        echo "<p>Registered date: </p>";
        echo sprintf("<p>%s</p>", $donee['registeredDate']);
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }

    private function displayName($donee) {
        if($donee['type'] === 'Individual') {
            echo "<div class='stat-card'>";
            echo "<p>Name: </p>";
            echo sprintf("<p>%s %s</p>", $donee['fname'], $donee['lname']);
            echo "</div>";
            echo "<div class='stat-card'>";
            echo "<p>NIC: </p>";
            echo sprintf("<p>%s</p>", $donee['NIC']);
            echo "</div>";
        } else {
            echo "<div class='stat-card'>";
            echo "<p>Organization: </p>";
            echo sprintf("<p>%s</p>", $donee['organizationName']);
            echo "</div>";
            echo "<div class='stat-card'>";
            echo "<p>Representative: </p>";
            echo sprintf("<p>%s</p>", $donee['representative']);
            echo "</div>";
        }
    }
}