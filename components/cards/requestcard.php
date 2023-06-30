<?php

namespace app\core\components\cards;

use app\models\requestModel;

class requestcard
{

    // variable to save whether the request is accepted or not
    private bool $accepted = false;

    // variable to save the buttons to be displayed
    private array $btns = [];

    // variable to save the button details (does not get changed)
    private array $btnDetails = [
        "Accept" => "<button class='rq-btn btn-primary %s' value='%s'>Accept</button>",
        "Reject" => "<button class='rq-btn btn-danger %s' value='%s'>Reject</button>",
        "Approve" => "<button class='rq-btn btn-primary %s' value='%s'>Approve</button>",
        "View" => "<button class='rq-btn btn-primary %s' value='%s'>View</button>",
    ];

    // empty constructor
    public function __construct() {

    }

    /**
     * @param array $requests
     * @param array $btns
     * @param bool $accepted
     * @param bool $donee
     * @return void
     */
    public function displayRequests(array $requests = [], array $btns = [], bool $accepted = false, bool $donee = false): void
    {
        // set private variables to passed arguments
        $this->accepted = $accepted;
        $this->btns = $btns;

        if(!$requests) {
            return;
        }

        // loop through the requests and display them
        foreach ($requests as $request) {
            $this->requestCard($request, $donee);
        }

    }

    private function requestCard(array $request,bool $donee): void
    {
        echo sprintf("<div class='rq-card' id='%s'>",$this->accepted ? $request['acceptedID'] : $request['requestID']);
        echo "<div class='rq-card-header'>";
        echo sprintf("<h1>%s</h1>",$request['subcategoryName']);
        if($this->accepted) {
            echo "<div class='rq-delivery-status'>";
            echo sprintf("<strong>Delivery : </strong><p>%s</p>",$request['deliveryStatus']);
            echo "</div>";
        }
        echo "</div>";
        echo "<div class='rq-category'>";
        echo sprintf("<p>%s</p>",$request['categoryName']);
        echo "</div>";
        echo "<div class='rq-description'>";
        echo sprintf("<p>%s</p>",$request['notes']);
        echo "</div>";
        $this->displayBtns($request);
        if($this->accepted) {
            if($request['deliveryStatus'] === "Completed" && $donee) {
                echo "<p class='rq-accepted-date'>";
                echo sprintf("<strong>%s users </strong> donated",$request['users']);
                echo "</p>";
            }
            else {
                echo "<p class='rq-accepted-date'>";
                echo sprintf("<strong>Accepted On : </strong> %s",$request['acceptedDate']);
                echo "</p>";
            }
        }
        echo "</div>";

    }

    private function displayBtns(array $request): void {
        echo "<div class='rq-btns'>";
        foreach ($this->btns as $btn) {
            echo sprintf($this->btnDetails[$btn[0]],$btn[1],$request['requestID']);
        }
        echo "</div>";
    }

    // function to display the accepted request cards to donee
    public function displayAcceptedRequetsToDonee(array $requests = []): void
    {

        if(!$requests) {
            return;
        }

        // loop through the requests and display them
        foreach ($requests as $request) {
            $this->acceptedRequestCard($request);
        }
    }

    // structure of the accepted request card to each request
    private function acceptedRequestCard($request) {

        // request card div with the id of the accepted request
        echo sprintf("<div class='rq-card' id='%s'>",$request['acceptedID']);

        // request card header div
        echo "<div class='rq-card-header'>";
        echo sprintf("<h1>%s</h1>",$request['subcategoryName']);
        echo sprintf("<div class='rq-delivery-status'><strong>Delivery : </strong> <p>%s</p></div>", $request['deliveryStatus']);
        echo "</div>";
        echo "<div class='rq-category'>";
        echo sprintf("<p>%s</p>",$request['categoryName']);
        echo "</div>";
        echo "<div class='rq-accepted-details'>";
        echo sprintf("<p><strong>Accepted amount : </strong> %s </p>",$request['amount']);
        echo sprintf("<p><strong>Accepted Date : </strong> %s </p>",$request['acceptedDate']);
        echo "</div>";
        echo "<div class='rq-btns'>";
        echo sprintf("<button class='rq-btn btn-primary viewRequest' value='%s'>View</button>",$request['requestID']);
        echo "</div>";
        echo "</div>";

    }

}