<?php

namespace UserLoginService\Application;

use UserLoginService\Domain\User;
use Exception;
use UserLoginService\Infrastructure\FacebookSessionManager;


class UserLoginService
{
    private array $loggedUsers = [];

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
        $FBSessionManager = new FacebookSessionManager();

        return $FBSessionManager->getSessions();
    }
}