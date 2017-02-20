<?php
namespace tests\models;

use app\models\Users;
use Yii;

class UsersTest extends \Codeception\Test\Unit
{

    use \Codeception\Specify;

    /**
     * @var \UnitTester
     */
    private $user;
    protected $tester;

    protected function _before()
    {
        $this->user = Users::create();
    }

    protected function _after()
    {
    }

    // tests
    public function testValidateNewUsers()
    {



        $this->specify("Firstname is required", function () {
            $this->user->user_firstname = null;
            $this->assertFalse($this->user->validate(['user_firstname']));
        });

        $this->specify("Lastname is required", function () {
            $this->user->user_lastname = null;
            $this->assertFalse($this->user->validate(['user_lastname']));
        });

        $this->specify("Email is required", function () {
            $this->user->user_email = null;
            $this->assertFalse($this->user->validate(['user_email']));
        });

        $this->specify("Phone is required", function () {
            $this->user->user_phone = null;
            $this->assertFalse($this->user->validate(['user_phone']));
        });

        $this->specify("Password is required", function () {
            $this->user->user_password = null;
            $this->assertFalse($this->user->validate(['user_password']));
        });

        $this->specify("Email not in correct format", function () {
            $this->user->user_email = 'test';
            $this->assertFalse($this->user->validate(['user_email']));
        });

        $this->specify("Email in correct format", function () {
            $this->user->user_email = 'test@gmail.com';
            $this->assertTrue($this->user->validate(['user_email']));
        });

        $this->specify("Can save user", function () {
            $this->user->user_firstname = 'Dwamian';
            $this->user->user_lastname = 'Mcleish';
            $this->user->user_email = 'test2@gmail.com';
            $this->user->user_phone = '8192189988';
            $this->user->user_password = Yii::$app->getSecurity()->generatePasswordHash('password');
            $this->assertTrue($this->user->save());
            $this->assertNotNull($this->user->user_date_joined);
            $this->assertNotNull($this->user->user_verification_code);
            $this->assertNotNull($this->user->uuid);
            $this->assertNotNull($this->user->user_ip_address);
            //$this->assertNotNull($this->user->user_lat);
            //$this->assertNotNull($this->user->user_lon);
            $this->assertTrue($this->user->save());
            $this->assertNotNull($this->user->user_date_modified);
        });

    }

}