<?php
require_once 'BaseRepository.php';
require_once '../models/Doctor.php';

class DoctorRepository extends BaseRepository {
    public function __construct($pdo) {
        parent::__construct($pdo, 'doctors');
    }

    // Save doctor (insert or update)
    public function save(Doctor $doctor) {
        if ($doctor->getId()) {
            $stmt = $this->pdo->prepare("
                UPDATE doctors SET spicialization = ?, department_id = ? WHERE id = ?
            ");
            return $stmt->execute([
                $doctor->getSpecialization(),
                $doctor->getDepartmentId(),
                $doctor->getId()
            ]);
        } else {
            $stmt = $this->pdo->prepare("
                INSERT INTO doctors (id, spicialization, department_id) VALUES (?, ?, ?)
            ");
            return $stmt->execute([
                $doctor->getId(),
                $doctor->getSpecialization(),
                $doctor->getDepartmentId()
            ]);
        }
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM doctors WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM doctors");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM doctors WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
