<?php



class Doctor extends User
{
    protected $specialization;
    protected $departmentId;

    public function __construct(
        $id,
        $email,
        $firstName,
        $lastName,
        $phone,
        $passwordHash,
        $specialization,
        $departmentId
    ) {
        parent::__construct($id, $email, $firstName, $lastName, $phone, $passwordHash, "doctor");

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

    public function getDepartmentId()
    {
        return $this->departmentId;
    }
}
