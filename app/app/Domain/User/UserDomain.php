<?php

class UserDomain
{
    private $name;
    private $email;
    private $password;
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store()
    {
    }
}
