<?php

namespace app\core;

use app\models\choModel;
use app\models\doneeModel;
use app\models\donorModel;
use app\models\driverModel;
use app\models\logisticModel;
use app\models\managerModel;
use app\models\userModel;

class SMS
{
    private ?string $id = '';
    private ?string $pw = '';
    private string $baseURL = 'http://www.textit.biz/sendmsg';

    public function __construct(array $config)
    {
        $this->id = $config['id'];
        $this->pw = $config['pw'];
    }

    private function sendSMS(string $to, string $msg): bool
    {
        $msg = urlencode($msg);
        $url = $this->baseURL . "?id=" . $this->id . "&pw=" . $this->pw . "&to=$to&text=$msg";
        $returnMsg = file($url);
        if (trim($returnMsg[0]) === "OK") {
            return true;
        } else {
            return false;
        }
    }

    private function getUserModel(userModel $user)
    {
        $userClass = null;
        switch ($user->userType) {
            case 'donee':
                $userClass = doneeModel::getModel(['doneeID' => $user->userID]);
                break;
            case 'donor':
                $userClass = donorModel::getModel(['donorID' => $user->userID]);
                break;
            case 'driver':
                $userClass = driverModel::getModel(['employeeID' => $user->userID]);
                break;
            case 'logistic':
                $userClass = logisticModel::getModel(['employeeID' => $user->userID]);
                break;
            case 'manager':
                $userClass = managerModel::getModel(['employeeID' => $user->userID]);
                break;
            case 'cho':
                $userClass = choModel::getModel(['choID' => $user->userID]);
                break;
        }
        return $userClass;
    }

    public function send(string $msg, userModel $user): bool
    {
        $user = $this->getUserModel($user);
        return $this->sendSMS($user->contactNumber, $msg);
    }

    public function sendSMSByUserID(string $msg, $userID): bool
    {
        $user = $this->getModelByID($userID);
        return $this->sendSMS($user->contactNumber, $msg);
    }

    public function getModelByID(string $id)
    {
        if(str_contains($id,'donee')){
            return doneeModel::getModel(['doneeID' => $id]);
        }
        if(str_contains($id,'donor')){
            return donorModel::getModel(['donorID' => $id]);
        }
        if(str_contains($id,'driver')){
            return driverModel::getModel(['employeeID' => $id]);
        }
        if(str_contains($id,'logistic')){
            return logisticModel::getModel(['employeeID' => $id]);
        }
        if(str_contains($id,'manager')){
            return managerModel::getModel(['employeeID' => $id]);
        }
        if(str_contains($id,'cho')){
            return choModel::getModel(['choID' => $id]);
        }
        if(str_contains($id,'cc')) {
            return logisticModel::getModel(['ccID' => $id]);
        }
        return null;
    }


}