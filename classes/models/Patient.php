<?php

require_once "User.php";

class Patient extends User
{
    protected $gender;
    protected $dateOfBirth;
    protected $address;

    public function __construct(
        $id,
        $email,
        $firstName,
        $lastName,
        $passwordHash,
        $gender,
        $dateOfBirth,
        $address
    ) {
        parent::__construct($id, $email, $firstName, $lastName, $passwordHash, "patient");

        $this->gender = $gender;
        $this->dateOfBirth = $dateOfBirth;
        $this->address = $address;
    }

    public function getRole()
    {
        return "patient";
    }
}
