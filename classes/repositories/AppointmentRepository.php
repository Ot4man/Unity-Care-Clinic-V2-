<?php

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
    // Find appointments by doctor with Patient Info
    public function findAllByDoctorWithPatientInfo($doctorId) {
        $stmt = $this->pdo->prepare("
            SELECT a.*, u.first_name as patient_first, u.last_name as patient_last, u.email as patient_email 
            FROM appointments a
            JOIN users u ON a.patient_id = u.id
            WHERE a.doctor_id = ?
            ORDER BY a.date, a.time
        ");
        $stmt->execute([$doctorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Find appointments by patient with Doctor Info
    public function findAllByPatientWithDoctorInfo($patientId) {
        $stmt = $this->pdo->prepare("
            SELECT a.*, u.first_name as doctor_first, u.last_name as doctor_last, d.specialization
            FROM appointments a
            JOIN users u ON a.doctor_id = u.id
            JOIN doctors d ON u.id = d.id
            WHERE a.patient_id = ?
            ORDER BY a.date, a.time
        ");
        $stmt->execute([$patientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updateStatus($id, $status) {
        $stmt = $this->pdo->prepare("UPDATE appointments SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
    // Find ALL appointments with complete info for Admin
    public function findAllWithDetails() {
        $stmt = $this->pdo->query("
            SELECT a.*, 
                   doc_u.first_name as doctor_first, doc_u.last_name as doctor_last,
                   pat_u.first_name as patient_first, pat_u.last_name as patient_last
            FROM appointments a
            JOIN users doc_u ON a.doctor_id = doc_u.id
            JOIN users pat_u ON a.patient_id = pat_u.id
            ORDER BY a.date DESC, a.time DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Statistics: Count by Status
    public function countByStatus() {
        $stmt = $this->pdo->query("SELECT status, COUNT(*) as count FROM appointments GROUP BY status");
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // Returns ['done' => 5, 'scheduled' => 2]
    }

    // Statistics: Appointments per Doctor
    public function countByDoctor() {
        $stmt = $this->pdo->query("
            SELECT CONCAT(u.first_name, ' ', u.last_name) as name, COUNT(a.id) as count
            FROM appointments a
            JOIN users u ON a.doctor_id = u.id
            GROUP BY a.doctor_id
            ORDER BY count DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
