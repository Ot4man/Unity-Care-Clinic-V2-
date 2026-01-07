<?php
require_once 'BaseRepository.php';
require_once '../models/Medication.php';

class MedicationRepository extends BaseRepository {
    public function __construct($pdo) {
        parent::__construct($pdo, 'medications');
    }

    // Save medication
    public function save(Medication $med) {
        if ($med->getId()) {
            $stmt = $this->pdo->prepare("UPDATE medications SET name = ?, instructions = ? WHERE id = ?");
            return $stmt->execute([$med->getName(), $med->getInstructions(), $med->getId()]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO medications (name, instructions) VALUES (?, ?)");
            return $stmt->execute([$med->getName(), $med->getInstructions()]);
        }
    }
}
