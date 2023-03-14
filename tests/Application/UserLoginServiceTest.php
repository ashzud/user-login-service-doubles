<?php

declare(strict_types=1);

namespace UserLoginService\Tests\Application;

use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use UserLoginService\Application\SessionManager;
use UserLoginService\Application\UserLoginService;
use UserLoginService\Domain\User;
use UserLoginService\Tests\Doubles\FacebookSessionManagerFake;
use UserLoginService\Tests\Doubles\FacebookSessionManagerStub;

final class UserLoginServiceTest extends TestCase
{
    protected $sessionManager;
    protected $userLoginService;

    protected function setUp():void
    {
        parent::setUp();
        $this->sessionManager = Mockery::mock(SessionManager::class);
        $this->userLoginService = new UserLoginService($this->sessionManager);
    }

    /**
     * @test
     */
    public function userIsCorrectlyLoggedIn()
    {
        $user = new User("TestUser");

        $this->userLoginService->manualLogin($user);
        $loggedUsers = $this->userLoginService->getLoggedUsers();

        $this->assertContains($user, $loggedUsers);
    }

    /**
     * @test
     */
    public function userIsAlreadyLoggedIn()
    {
        $FBSessionManager = new FacebookSessionManagerStub();
        $userLoginService = new UserLoginService($FBSessionManager);
        $user = new User("TestUser");

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("User already logged in");

        $userLoginService->manualLogin($user);
        $userLoginService->manualLogin($user);
    }

    /**
     * @test
     */
    public function checkNumberOfActiveSessions()
    {
        $this->sessionManager
            ->allows()
            ->getSessions()
            ->andReturns(10);

        $numberOfSessions = $this->userLoginService->getExternalSessions();

        $this->assertEquals(10, $numberOfSessions);
    }

    /**
     * @test
     */
    public function checkCorrectLogin()
    {
        $this->sessionManager->allows()->login("username", "password");
        $userLoginResult = $this->userLoginService->login("username", "password");

        $this->assertEquals("Login correcto", $userLoginResult);
    }

    /**
     * @test
     */
    public function checkIncorrectLogin()
    {
        $this->sessionManager
            ->allows()
            ->login("username", "password");
        $userLoginResult = $this->userLoginService->login("fail", "fail");

        $this->assertEquals("Login incorrecto", $userLoginResult);
    }

    /**
     * @test
     */
    public function checkLogoutError()
    {
        $sessionManagerSpy = Mockery::spy(SessionManager::class);
        $userLoginService = new UserLoginService($sessionManagerSpy);
        $userToLogout = new User("userName");

        $logoutResult = $userLoginService->logout($userToLogout);

        $this->assertEquals("User not found", $logoutResult);
        $sessionManagerSpy->shouldNotHaveReceived()->logout("userName");
    }

    /**
     * @test
     */
    public function checkCorrectLogout()
    {
        $sessionManagerSpy = Mockery::spy(SessionManager::class);

        $userLoginService = new UserLoginService($sessionManagerSpy);

        $userToLogout = new User("userName");

        $logoutResult = $userLoginService->logout($userToLogout);

        $this->assertEquals("Ok", $logoutResult);
        $sessionManagerSpy->shouldHaveReceived()->logout("userName");
    }

    /**
     * @test
     */
    public function serviceNotAvailableForLogout()
    {
        $this->sessionManager->shouldReceive("login");
        $this->sessionManager->
    }
}
