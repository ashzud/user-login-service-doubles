<?php

namespace UserLoginService\Tests\Doubles;

use UserLoginService\Infrastructure\FacebookSessionManager;

class FacebookSessionManagerFake extends FacebookSessionManager
{
    public function login(string $userName, string $password): bool
    {
        return strcmp($userName, "test") == 0 and strcmp($password, "test") == 0;
    }
}