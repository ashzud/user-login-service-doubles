<?php

namespace UserLoginService\Infrastructure;

class FacebookSessionManagerStub extends FacebookSessionManager
{
    public function login(string $userName, string $password): bool
    {
        //Imaginad que esto en realidad realiza una llamada al API de Facebook
        return true;
    }

    public function getSessions(): int
    {
        //Imaginad que esto en realidad realiza una llamada al API de Facebook
        return 35;
    }
}