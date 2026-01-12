<?php

abstract class User
{
    protected $id;
    protected $email;
    protected $firstName;
    protected $lastName;
    protected $phone;
    protected $passwordHash;
    protected $role;

    public function __construct($id, $email, $firstName, $lastName, $phone, $passwordHash, $role)
    {
        $this->id = $id;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phone = $phone;
        $this->passwordHash = $passwordHash;
        $this->role = $role;
    }



    public function verifyPassword($password)
    {
        return password_verify($password, $this->passwordHash);
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getFirstName() { return $this->firstName; }
    public function getLastName() { return $this->lastName; }
    public function getRole() { return $this->role; }
    public function getId() { return $this->id; }
    public function getPasswordHash() { return $this->passwordHash; }

    public function setPasswordHash($hash) { $this->passwordHash = $hash; }
    public function setId($id) { $this->id = $id; }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getFullName()
    {
        return $this->firstName . " " . $this->lastName;
    }
}
