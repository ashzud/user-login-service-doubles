<?php

namespace UserLoginService\Tests\Doubles;

use UserLoginService\Infrastructure\FacebookSessionManager;

class FacebookSessionManagerStub extends FacebookSessionManager
{
    public function getSessions(): int
    {
        //Imaginad que esto en realidad realiza una llamada al API de Facebook
        return 35;
    }
}