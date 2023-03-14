<?php

namespace UserLoginService\Application;

use Exception;
use UserLoginService\Domain\User;
use UserLoginService\Infrastructure\FacebookSessionManager;
use UserLoginService\Tests\Doubles\FacebookSessionManagerStub;


class UserLoginService
{
    private array $loggedUsers = [];
    private SessionManager $sessionManager;

    public function __construct($sessionManager)
    {
        $this->sessionManager = $sessionManager;
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

        return $this->sessionManager->getSessions();
    }

    public function login(string $username, string $password): string
    {

        $correctLogin = $this->sessionManager->login($username,$password);

        if ($correctLogin) {
            $user = new User($username);
            $this->manualLogin($user);
            return "Login correcto";
        }
        else
            return "Login incorrecto";
    }

    public function logout(User $user): string
    {
        if (in_array($user, $this->loggedUsers, true))
        {
            $this->sessionManager->logout($user->getUserName());
            $indexToRemove = array_search($user, $this->loggedUsers);
            unset($this->loggedUsers[$indexToRemove]);
            return "Ok";
        }
        else
        {
            return "User not found";
        }
    }
}