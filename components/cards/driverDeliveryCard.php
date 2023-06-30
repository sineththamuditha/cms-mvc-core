<?php

namespace app\core\components\cards;

use app\models\deliveryModel;

class driverDeliveryCard
{
    // to store destinations of deliveries as key value pair
    private static array $deliveryDetails = [];

    public function __construct()
    {
        $sql = "SELECT donorID,address FROM donor UNION SELECT doneeID,address FROM donee UNION SELECT ccID,address FROM communitycenter UNION SELECT subcategoryID,subcategoryName FROM subcategory";
        $stmt = deliveryModel::prepare($sql);
        $stmt->execute();
        self::$deliveryDetails = $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @param array $deliveries
     * @return void
     */
    public function showAssignedDeliveries(array $deliveries) : void {
        foreach ($deliveries as $delivery) {
            $this->showDeliveryCard($delivery);
        }
    }

    /**
     * @param $data
     * @return void
     */
    private function showDeliveryCard($data) : void {
        echo sprintf("<div class='driver-del-card' id='%s'>", $data['subdeliveryID'] . ',' . $data['type']);
        echo sprintf("<div class='card-column subcategory'><strong>Sub Category</strong><p>%s</p></div>", self::$deliveryDetails[$data['item']]);
        echo sprintf("<div class='card-column pickupaddress'><strong>Pick up Address</strong><p>%s</p></div>", self::$deliveryDetails[$data['start']]);
        echo sprintf("<div class='card-column deliveryaddress'><strong>Drop Off</strong><p>%s</p></div>",self::$deliveryDetails[$data['end']]);
        echo sprintf("<div class='card-column assigneddate'><strong>Created Date</strong><p>%s</p></div>", $data['createdDate']);

        // buttons to show the route and finish the delivery
        echo "<div class='card-column route-complete-btns'>
            <a class='del-route' href=#'>Route</a>
            <a class='del-finish' href='#'>Finish</a>
        </div>";

        // button to request reassign delivery or to cancel the request
        echo sprintf("<div class='card-column delivery-btns'>
            <a class='del-reassign' href='#'>%s</a>
            </div></div>", $data['status'] === 'Ongoing' ? 'Request to Re-Assign' : 'Cancel Re-assign request');

    }

    /**
     * @param $data
     * @return void
     */
    public static function showCompletedDeliveryCards($deliveries) : void {

        self::$deliveryDetails = deliveryModel::getDestinationAddresses();

        foreach ($deliveries as $delivery) {
            self::showCompletedDeliveryCard($delivery);
        }

    }

    /**
     * @param $data
     * @return void
     */
    public static function showCompletedDeliveryCard($data) : void {

        echo sprintf("<div class='driver-del-card' id='%s'>", $data['subdeliveryID'] . ',' . $data['type']);
        echo sprintf("<div class='card-column subcategory'><strong>Sub Category</strong><p>%s</p></div>", self::$deliveryDetails[$data['item']]);
        echo sprintf("<div class='card-column pickupaddress'><strong>Pick up Address</strong><p>%s</p></div>", self::$deliveryDetails[$data['start']]);
        echo sprintf("<div class='card-column deliveryaddress'><strong>Drop Off</strong><p>%s</p></div>",self::$deliveryDetails[$data['end']]);
        echo sprintf("<div class='card-column assigneddate'><strong>Created Date</strong><p>%s</p></div>", $data['createdDate']);
        echo sprintf("<div class='card-column assigneddate'><strong>Completed Date</strong><p>%s</p></div>", $data['completedDate']);

        // button to view the details of the delivery
        echo "<div class='card-column route-complete-btns'>
            <a class='del-finish view-completed-delivery' href='#'>View details</a>
        </div>";
        echo sprintf("</div>");
    }

}