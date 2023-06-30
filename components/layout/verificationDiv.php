<?php

namespace app\core\components\layout;

class verificationDiv
{
    public string $userType = '';
    public $user;
    public int $mobileVerification = 0;
    public int $doneeVerification = 0;
    public int $needVerification = 0;

    public function __construct()
    {
        $this->userType = \app\core\Application::session()->get('userType');
        if ($this->userType === 'donee') {
            $this->user = \app\models\doneeModel::getModel(['doneeID' => \app\core\Application::session()->get('user')]);
        } else if ($this->userType === 'donor') {
            $this->user = \app\models\donorModel::getModel(['donorID' => \app\core\Application::session()->get('user')]);
        }
        $this->checkMobileVerification();
        if ($this->userType === 'donee') {
            $this->checkDoneeVerification();
        }
    }

    public function checkMobileVerification(): void
    {
        if ($this->user->mobileVerification === 0) {
            $this->mobileVerification = 1;
            $this->needVerification = 1;
        }
    }

    private function mobileVerificationDiv(): void
    {
        echo "<div class='error-container'>";
        echo "<div class='error-img'>";
        echo "<img src='/CommuSupport/public/src/errors/Mobile.svg'>";
        echo "</div>";
        echo "<div class='mobile-verification-div-content-body'>";
        echo "<a href='/CommuSupport/verifyMobile' class='btn btn-primary'>Verify your Mobile</a>";
        echo "</div>";
        echo "</div>";
    }

    public function checkDoneeVerification(): void
    {
        if ($this->user->verificationStatus === 0) {
            $this->doneeVerification = 1;
            $this->needVerification = 1;
        }
    }

    private function doneeVerificationDiv(): void
    {
        echo "<div class='error-container'>";
        echo "<div class='error-img'>";
        echo "<img src='/CommuSupport/public/src/errors/Document.svg'>";
        echo "</div>";
        echo "</div>";
    }

    public function notVerified(): int
    {
        if ($this->needVerification) {
            echo '<div class="content"><div class="error-grid-2">';
            if ($this->mobileVerification) {
                $this->mobileVerificationDiv();
            }
            if ($this->doneeVerification) {
                $this->doneeVerificationDiv();
            }
            echo '</div></div>';
            return 1;
        }
        return 0;
    }

    public function __destruct()
    {
        ?>
        <script>
            window.onload = function () {
                const checkError = document.querySelector('.error-grid-2');
                if (checkError && checkError.children.length === 1) {
                    console.log(checkError);
                    checkError.classList.replace('error-grid-2', 'error-container');
                }
            }
        </script>
        <?php

    }
}