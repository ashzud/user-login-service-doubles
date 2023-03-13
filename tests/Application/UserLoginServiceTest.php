<?php

declare(strict_types=1);

namespace UserLoginService\Tests\Application;

use PHPUnit\Framework\TestCase;
use UserLoginService\Application\UserLoginService;
use UserLoginService\Domain\User;
use Exception;
use UserLoginService\Infrastructure\FacebookSessionManagerStub;

final class UserLoginServiceTest extends TestCase
{
    /**
     * @test
     */
    public function userIsCorrectlyLoggedIn()
    {
        $userLoginService = new UserLoginService();
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
        $userLoginService = new UserLoginService();
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

}
