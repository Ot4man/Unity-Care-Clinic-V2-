<?php



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
        $phone,
        $passwordHash,
        $gender,
        $dateOfBirth,
        $address
    ) {
        parent::__construct($id, $email, $firstName, $lastName, $phone, $passwordHash, "patient");

        $this->gender = $gender;
        $this->dateOfBirth = $dateOfBirth;
        $this->address = $address;
    }

    public function getRole()
    {
        return "patient";
    }

    public function getGender() { return $this->gender; }
    public function getDateOfBirth() { return $this->dateOfBirth; }
    public function getAddress() { return $this->address; }
    public function getId() { return $this->id; }
}
