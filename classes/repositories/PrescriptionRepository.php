<?php
require_once 'BaseRepository.php';
require_once '../models/Prescription.php';

class PrescriptionRepository extends BaseRepository {
    public function __construct($pdo) {
        parent::__construct($pdo, 'prescriptions');
    }

    // Save prescription
    public function save(Prescription $pres) {
        if ($pres->getId()) {
            $stmt = $this->pdo->prepare("
                UPDATE prescriptions 
                SET date = ?, doctor_id = ?, patient_id = ?, medication_id = ?, dosage_instructions = ?
                WHERE id = ?
            ");
            return $stmt->execute([
                $pres->getDate(),
                $pres->getDoctorId(),
                $pres->getPatientId(),
                $pres->getMedicationId(),
                $pres->getDosageInstructions(),
                $pres->getId()
            ]);
        } else {
            $stmt = $this->pdo->prepare("
                INSERT INTO prescriptions (date, doctor_id, patient_id, medication_id, dosage_instructions)
                VALUES (?, ?, ?, ?, ?)
            ");
            return $stmt->execute([
                $pres->getDate(),
                $pres->getDoctorId(),
                $pres->getPatientId(),
                $pres->getMedicationId(),
                $pres->getDosageInstructions()
            ]);
        }
    }

    // Find prescriptions by doctor
    public function findByDoctor($doctorId) {
        $stmt = $this->pdo->prepare("SELECT * FROM prescriptions WHERE doctor_id = ?");
        $stmt->execute([$doctorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Find prescriptions by patient
    public function findByPatient($patientId) {
        $stmt = $this->pdo->prepare("SELECT * FROM prescriptions WHERE patient_id = ?");
        $stmt->execute([$patientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
