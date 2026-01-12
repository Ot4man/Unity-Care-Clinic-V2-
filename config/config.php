<?php
session_start();

// Database Connection
$host = 'localhost';
$dbname = 'unity_care_clinic_v2';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Load Models
require_once __DIR__ . '/../classes/models/User.php';
require_once __DIR__ . '/../classes/models/Admin.php';
require_once __DIR__ . '/../classes/models/Doctor.php';
require_once __DIR__ . '/../classes/models/Patient.php';
require_once __DIR__ . '/../classes/models/Appointment.php';
require_once __DIR__ . '/../classes/models/Medication.php';
require_once __DIR__ . '/../classes/models/Prescription.php';
require_once __DIR__ . '/../classes/models/Department.php';

// Load Repositories
require_once __DIR__ . '/../classes/repositories/BaseRepository.php';
require_once __DIR__ . '/../classes/repositories/UserRepository.php';
require_once __DIR__ . '/../classes/repositories/DoctorRepository.php';
require_once __DIR__ . '/../classes/repositories/PatientRepository.php';
require_once __DIR__ . '/../classes/repositories/AppointmentRepository.php';
require_once __DIR__ . '/../classes/repositories/MedicationRepository.php';
require_once __DIR__ . '/../classes/repositories/PrescriptionRepository.php';
require_once __DIR__ . '/../classes/repositories/DepartmentRepository.php';

// Now $pdo is available globally to any file that includes this one.
