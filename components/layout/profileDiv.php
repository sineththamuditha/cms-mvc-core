<?php

namespace app\core\components\layout;

use app\core\Application;
use app\models\notificationModel;

class profileDiv
{
    private array $notifications = [];
    private array $processByuserType = [
        'admin' => [],
        'logistic' => ['donation','employee'],
        'manager' => ['donation', 'request','employee'],
        'driver' => ['delivery','employee'],
        'cho' => ['complain','employee'],
        'donee' => ['request','user','event'],
        'donor' => ['donation','user','event'],
    ];

    private array $notificationIcon = [
        'event' => 'event',
        'directDonations' => 'local_shipping',
        'request' => 'local_shipping',
        'acceptedRequests' => 'local_shipping',
        'delivery' => 'local_shipping',
        'ccDonation' => 'local_shipping',
        'complaint' => 'report',
        'registration' => 'how_to_reg'
    ];

    public function __construct()
    {

        echo "<div class='profile'>";
        $this->notifications = notificationModel::getNotification(['userID' => $_SESSION['user'], 'usertype' =>  $_SESSION['userType'], 'related' => $this->processByuserType[$_SESSION['userType']]]);
//        echo "<pre>";
//        print_r($this->notifications);
//        echo "</pre>";
    }


    public function notification(): void
    {
        echo "<div class='notif-box' id='notif-btn'>";
        echo "<a href='#'><i class='material-icons'>notifications</i></a>";
        echo "<div class='notification' id='notification' style='display: none'>
        <div class='notif-header'>
            <div class='header-left-block'><h3>Notifications</h3></div>
            
        </div>";
        $this->showNotificationCards();
        echo "</div></div>";
    }

    private function showNotificationCards() : void {
        if(empty($this->notifications)) {
            echo "<div class='empty-notification-div'><h5>Nothing to show here</h5></div>";
            return;
        }
        echo "<div class='notif-box-scroller'>";
        foreach ($this->notifications as $notification) {
            echo "<div class='notif-card'><div class='notif-left-block'>";
            echo sprintf("<div class='notif-message'>
                            <h5>%s</h5>
                            <p><small>%s</small></p>
                        </div>",$notification['title'],$notification['message']);
            echo sprintf("<div class='notif-date-time'>
                            <p class='date'>%s</p>
                            <p class='time'>%s</p>
                        </div>",date('M d',strtotime($notification['dateCreated'])),date('g:i a',strtotime($notification['dateCreated'])));
            echo    sprintf("</div><div class='notif-right-block'>
                    <i class='material-icons'>%s</i></div>
            </div>", $this->notificationIcon[$notification['related']]);
        }
        echo "</div>";
    }

    public function profile() : void
    {
        echo sprintf("<a class='profile-box' href='/CommuSupport/%s/profile'>", $_SESSION['userType']);
        echo "<div class='name-box'>";
        echo sprintf("<h4>%s</h4>",$this->getUsername());
        echo sprintf("<p>%s</p>", $this->getPosition());
        echo "</div>";
        echo "<div class='profile-img'>";
        echo "<img src='https://www.w3schools.com/howto/img_avatar.png' alt='profile'>";
        echo "</div>";
//        echo "</div>";
        echo "</a>";
    }

    public function end(): void
    {
        echo "</div>";
    }

    private function getUsername(): string
    {
        return Application::session()->get('username');
    }

    private function getPosition(): string
    {
        switch(Application::session()->get('userType')){
            case 'donor':
                return 'Donor';
            case 'donee':
                return 'Donee';
            case 'manager':
                return 'Manager';
                case 'logistic':
                return 'Logistic';
            case 'cho':
                return 'CHO';
                case 'admin':
                return 'Admin';
                case 'driver':
                return 'Driver';

            default:
                return 'User';
        }
    }
}