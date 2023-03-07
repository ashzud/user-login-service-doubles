<?php

namespace UserLoginService\Domain;

class User
{
    private string $userName;

    public function __construct(string $userName)
    {
        $this->userName = $userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }


}