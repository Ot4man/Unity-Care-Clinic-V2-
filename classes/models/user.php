<?php

abstract class User
{
    protected $id;
    protected $email;
    protected $firstName;
    protected $lastName;
    protected $passwordHash;
    protected $role;

    public function __construct($id, $email, $firstName, $lastName, $passwordHash, $role)
    {
        $this->id = $id;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->passwordHash = $passwordHash;
        $this->role = $role;
    }

    abstract public function getRole();

    public function verifyPassword($password)
    {
        return password_verify($password, $this->passwordHash);
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getFullName()
    {
        return $this->firstName . " " . $this->lastName;
    }
}
