<?php

namespace app\core\components\cards;


// class to display requested cards to the posted donee
// card has all the details of the request and no buttons
class postedRequestCard
{

    public static function displayCards($requests) {

        if(!$requests) {
            return;
        }

        //traverse through the array to display each card
        foreach ($requests as $request) {

            self::showCard($request);

        }

    }

    // function to display a single card
    public static function showCard($request) {

        // card div
        echo sprintf("<div class='posted-rq-card' id='%s'>",$request['requestID']);

        // card header
        echo sprintf("<div class='posted-rq-card-header'>");
        echo sprintf("<h2>%s</h2>", $request['subcategoryName']);
        echo sprintf("<p class='posted-rq-card-approval'> <strong>Approval : </strong> %s </p>", $request['approval']);
        echo sprintf("</div>");

        // card info -> amount requseted and posted date
        echo sprintf("<div class='posted-rq-card-info'>");
        echo sprintf("<p><strong>Amount : </strong> %s </p>", $request['amount']);
        echo sprintf("<p><strong>Posted date : </strong> %s </p>", $request['postedDate']);
        echo sprintf("</div>");

        // card info -> urgency and expiry date
        echo sprintf("<div class='posted-rq-card-info'>");
        echo sprintf("<p><strong>Urgency : </strong> %s </p>", $request['urgency']);
        echo sprintf("<p><strong>Visible until : </strong> %s </p>", $request['expDate']);
        echo sprintf("</div>");

        // card info -> any notes added by the user
        echo sprintf("<div class='posted-rq-card-info  description'>");
        echo sprintf("<p>%s</p>", $request['notes'] ?  $request['notes'] : "<p style='color: var(--danger-color)'>You have not added any note</p>");
        echo sprintf("</div>");

        // card info -> number of users who have accepted the request and the number of items accepted
        echo sprintf("<div class='posted-rq-card-info accepted-info'>");

        // if the request is not approved by the manager
        if($request['approval'] === 'Pending') {
            echo "<p><strong> Your request haven't been approved by the manager yet! </strong> </p>";
        }
        // if the request is approved but no user has accepted the request
        else if($request['users'] === 0) {
            echo "<p><strong> No user have accepted your request yet </strong> </p>";
        }
        // if the request is approved and users have accepted the request
        else {
            echo sprintf("<p><strong> %s users </strong>have accepted %s. Check under accepted page for more info </p>", $request['users'], $request['acceptedAmount']);
        }
        echo sprintf("</div>");

        echo sprintf("<div class='posted-rq-btns'>
                <button class='btn-danger cancel-req' value='%s'>Cancel</button>
            </div>", $request['requestID']);

        echo sprintf("</div>");

    }

}