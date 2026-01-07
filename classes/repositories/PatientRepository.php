<?php
require_once 'BaseRepository.php';
require_once '../models/Patient.php';

class PatientRepository extends BaseRepository {
    public function __construct($pdo) {
        parent::__construct($pdo, 'patients');
    }

    // Save patient (insert or update)
    public function save(Patient $patient) {
        if ($patient->getId()) {
            $stmt = $this->pdo->prepare("
                UPDATE patients SET gender = ?, date_of_birth = ?, adress = ? WHERE id = ?
            ");
            return $stmt->execute([
                $patient->getGender(),
                $patient->getDateOfBirth(),
                $patient->getAddress(),
                $patient->getId()
            ]);
        } else {
            $stmt = $this->pdo->prepare("
                INSERT INTO patients (id, gender, date_of_birth, adress) VALUES (?, ?, ?, ?)
            ");
            return $stmt->execute([
                $patient->getId(),
                $patient->getGender(),
                $patient->getDateOfBirth(),
                $patient->getAddress()
            ]);
        }
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM patients WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM patients");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM patients WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
