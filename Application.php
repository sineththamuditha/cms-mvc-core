<?php

namespace app\core;

use app\models\userModel;
use app\models\userTokenModel;

class Application
{
    public static string $ROOT_DIR;
    public static Application $app;

    public string $userClass;
    public string $userType = '';
    public Router $router;
    public Request $request;
    public Response $response;
    public Database $database;
    public Session $session;
    public Cookie $cookie;
    public SMS $sms;
    public File $file;
    public ?userModel $user;
    private array $rootInfo;

    public function __construct(public string $rootPath, array $config)

    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;


        $this->userClass = $config['userClass'];
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->session = new Session();
        $this->cookie = new Cookie();
        $this->sms = new SMS($config['sms']);
        $this->file = new File();
        $this->database = new Database($config['db']);
        $this->rootInfo = $config['root'];

        $this->settingLoggedData();

        if($this->cookie->isRememberMeSet()) {
            if($this->rememberLogin()) {
                $this->response->redirect('/');
            }
        }
    }

    public static function session() : Session
    {
        return self::$app->session;
    }

    public static function cookie() : Cookie
    {
        return self::$app->cookie;
    }

    public static function sms() : SMS
    {
        return self::$app->sms;
    }

    public static function file() : File
    {
        return self::$app->file;
    }

    public function run() : void
    {
        try {
            ob_start();
            echo $this->router->resolve();
            ob_end_flush();
        } catch (\Exception $e) {
            $this->response->setStatusCode($e->getCode());
            ob_start();
            echo $this->router->render('error',$e->getMessage(),[
                'exception' => $e
            ]);
            ob_end_flush();
        }
    }

    // function to save user info on the session upon login
    /**
     * @param $user
     * @return bool
     */
    public function login($user): bool
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        if(session_regenerate_id()) {
            $this->session->set('user', $primaryValue);
            $this->session->set('username', $user->username);
            $this->session->set('userType', $user->userType());
            return true;
        }
        return false;
    }

    // function to remove user details from the session upon logout
    /**
     * @return void
     */
    public function logout(): void
    {
        $this->user = null;
        $this->session->remove('user');
        $this->session->remove('username');
        $this->session->set('userType','guest');
    }

    // function to get the user type of the user
    /**
     * @return string
     */
    public function userType() : string
    {
        return $this->session->get('userType');
    }

    // function to check whether the username is of the root
    /**
     * @param string $username
     * @return bool
     */
    public function isRoot(string $username): bool
    {
        return $username === $this->rootInfo['username'];
    }

    // function to check whether the password is of the root
    /**
     * @param string $password
     * @return bool
     */
    public function isRootPassword(string $password): bool
    {
        return password_verify($password, $this->rootInfo['password']);
    }

    public function getSelectorNValidator(): array | null
    {
        $selectorNValidator = $this->cookie->getCookie('rememberMe');
        if(!$selectorNValidator) {
            return ['',''];
        }
        return explode(':', $selectorNValidator);
    }

    public function settingLoggedData(): void {
        $primaryValue = $this->session->get('user');
        if($primaryValue) {
            $primaryKey = $this->userClass::getPrimaryKey();
            $this->user = $this->userClass::getModel([$primaryKey => $primaryValue]);
        } else {
            $this->user = null;
            if(!$this->session->get('userType')) {
                $this->session->set('userType', 'guest');
            }
        }
    }

    private function rememberLogin(): bool {
        [$selector, $validator] = $this->getSelectorNValidator();
        $userToken = userTokenModel::getModel(['selector' => $selector]);
        if(!$userToken) {
            $this->cookie->unsetCookie('rememberMe');
            return false;
        }
        if(date('Y-m-d H:i:s') > $userToken->expiryDate) {
            $this->cookie->unsetCookie('rememberMe');
            $userToken->delete(['selector' => $selector]);
            return false;
        }
        if(password_verify($validator, $userToken->validator)) {
            echo 'remember login';
            return $this->login(userModel::getModel(['userID' => $userToken->userID]));
        }
        return false;
    }
}
