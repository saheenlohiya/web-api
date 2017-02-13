<?php
namespace tests\models;

use app\models\Users;

class UsersTest extends \Codeception\Test\Unit
{

    use \Codeception\Specify;

    /**
     * @var \UnitTester
     */
    private $user;

    protected function _before()
    {

    }

    protected function _after()
    {
    }

    // tests
    public function testValidation()
    {

        $this->user = Users::create();

        $this->specify("Email is required", function () {
            $this->user->user_email = null;
            $this->assertFalse($this->user->validate(['user_email']));
        });

        $this->specify("Email not in correct format", function () {
            $this->user->user_email = 'test';
            $this->assertFalse($this->user->validate(['user_email']));
        });

        $this->specify("Email in correct format", function () {
            $this->user->user_email = 'test@gmail.com';
            $this->assertTrue($this->user->validate(['user_email']));
        });
    }
}