<?php
require_once 'BaseRepository.php';
require_once '../models/Appointment.php';

class AppointmentRepository extends BaseRepository {
    public function __construct($pdo) {
        parent::__construct($pdo, 'appointments');
    }

    // Save a new appointment or update existing
    public function save(Appointment $appointment) {
        if ($appointment->getId()) {
            // Update
            $stmt = $this->pdo->prepare("
                UPDATE appointments 
                SET date = ?, time = ?, doctor_id = ?, patient_id = ?, reason = ?, status = ?
                WHERE id = ?
            ");
            return $stmt->execute([
                $appointment->getDate(),
                $appointment->getTime(),
                $appointment->getDoctorId(),
                $appointment->getPatientId(),
                $appointment->getReason(),
                $appointment->getStatus(),
                $appointment->getId()
            ]);
        } else {
            // Insert
            $stmt = $this->pdo->prepare("
                INSERT INTO appointments (date, time, doctor_id, patient_id, reason, status)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            return $stmt->execute([
                $appointment->getDate(),
                $appointment->getTime(),
                $appointment->getDoctorId(),
                $appointment->getPatientId(),
                $appointment->getReason(),
                $appointment->getStatus()
            ]);
        }
    }

    // Find appointments by doctor
    public function findByDoctor($doctorId) {
        $stmt = $this->pdo->prepare("SELECT * FROM appointments WHERE doctor_id = ?");
        $stmt->execute([$doctorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Find appointments by patient
    public function findByPatient($patientId) {
        $stmt = $this->pdo->prepare("SELECT * FROM appointments WHERE patient_id = ?");
        $stmt->execute([$patientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
