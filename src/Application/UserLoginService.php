<?php

namespace UserLoginService\Application;

use Exception;
use UserLoginService\Domain\User;
use UserLoginService\Infrastructure\FacebookSessionManager;
use UserLoginService\Tests\Doubles\FacebookSessionManagerStub;


class UserLoginService
{
    private array $loggedUsers = [];
    private FacebookSessionManager $FBSessionManager;

    public function __construct($FBSessionManager)
    {
        $this->FBSessionManager = $FBSessionManager;
    }

    public function manualLogin(User $user): void
    {
        if (in_array($user, $this->loggedUsers, true))
        {
            throw new Exception("User already logged in");
        }
        $this->loggedUsers[] = $user;
        return;
    }

    public function getLoggedUsers(): array
    {
        return $this->loggedUsers;
    }

    public function getExternalSessions(): int
    {

        return $this->FBSessionManager->getSessions();
    }

    public function login(string $username, string $password): string
    {

        $correctLogin = $this->FBSessionManager->login($username,$password);

        if ($correctLogin) {
            $user = new User($username);
            $this->manualLogin($user);
            return "Login correcto";
        }
        else
            return "Login incorrecto";
    }
}