<?php

require_once "User.php";

class Doctor extends User
{
    protected $specialization;
    protected $departmentId;

    public function __construct(
        $id,
        $email,
        $firstName,
        $lastName,
        $passwordHash,
        $specialization,
        $departmentId
    ) {
        parent::__construct($id, $email, $firstName, $lastName, $passwordHash, "doctor");

        $this->specialization = $specialization;
        $this->departmentId = $departmentId;
    }

    public function getRole()
    {
        return "doctor";
    }

    public function getSpecialization()
    {
        return $this->specialization;
    }
     public function getId() { return $this->id; }
}
