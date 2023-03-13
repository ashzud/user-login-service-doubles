<?php

declare(strict_types=1);

namespace UserLoginService\Tests\Application;

use Exception;
use PHPUnit\Framework\TestCase;
use UserLoginService\Application\UserLoginService;
use UserLoginService\Domain\User;
use UserLoginService\Tests\Doubles\FacebookSessionManagerFake;
use UserLoginService\Tests\Doubles\FacebookSessionManagerStub;

final class UserLoginServiceTest extends TestCase
{
    /**
     * @test
     */
    public function userIsCorrectlyLoggedIn()
    {
        $FBSessionManager = new FacebookSessionManagerStub();
        $userLoginService = new UserLoginService($FBSessionManager);
        $user = new User("TestUser");

        $userLoginService->manualLogin($user);
        $loggedUsers = $userLoginService->getLoggedUsers();

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
        $FBSessionManager = new FacebookSessionManagerStub();

        $numberOfSessions = $FBSessionManager->getSessions();

        $this->assertEquals(35, $numberOfSessions);
    }

    /**
     * @test
     */
    public function checkCorrectLogin()
    {
        $FBSessionManager = new FacebookSessionManagerFake();
        $userLoginService = new UserLoginService($FBSessionManager);

        $userLoginResult = $userLoginService->login("test", "test");

        $this->assertEquals("Login correcto", $userLoginResult);
    }

    /**
     * @test
     */
    public function checkIncorrectLogin()
    {
        $FBSessionManager = new FacebookSessionManagerFake();
        $userLoginService = new UserLoginService($FBSessionManager);

        $userLoginResult = $userLoginService->login("fail", "fail");

        $this->assertEquals("Login incorrecto", $userLoginResult);
    }
}
