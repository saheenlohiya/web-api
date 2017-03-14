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

        //Use to be required. Not anymore.
        $this->specify("Phone is required", function () {
            $this->user->user_phone = null;
            $this->assertTrue($this->user->validate(['user_phone']));
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

        $this->specify("Zipcode is required", function () {
            $this->user->user_zip = null;
            $this->assertFalse($this->user->validate(['user_zip']));
        });

        $this->specify("DOB is required", function () {
            $this->user->user_dob = null;
            $this->assertFalse($this->user->validate(['user_dob']));
        });

        $this->specify("DOB must be in the required format", function () {
            $this->user->user_dob = '778987';
            $this->assertFalse($this->user->validate(['user_dob']));
            $this->user->user_dob = '10/08/1978';
            $this->assertTrue($this->user->validate(['user_dob']));
        });

        $this->specify("Can save user", function () {
            $this->user->user_firstname = 'Dwamian';
            $this->user->user_lastname = 'Mcleish';
            $this->user->user_email = 'dmcleish@gmail.com';
            $this->user->user_phone = '8192189988';
            $this->user->user_zip = '78758';
            $this->user->user_dob = '10/08/1978';
            $this->user->user_password = 'password';
            $this->assertTrue($this->user->save());
            $this->assertNotNull($this->user->user_date_joined);
            $this->assertNotNull($this->user->user_verification_code);
            $this->assertNotNull($this->user->uuid);
            $this->assertNotNull($this->user->user_ip_address);
            $this->assertNotNull($this->user->user_date_modified);
        });

    }

}