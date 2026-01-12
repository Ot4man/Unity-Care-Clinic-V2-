<?php
require_once __DIR__ . '/../../config/config.php';

// Check Role
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'patient') {
    header("Location: ../login.php");
    exit;
}

$success = '';
$error = '';

// Get Doctors for dropdown
$doctorRepo = new DoctorRepository($pdo);
$doctors = $doctorRepo->findAllWithUserInfo(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = $_POST['doctor_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $reason = $_POST['reason'];
    
    // Basic validation
    if (empty($doctor_id) || empty($date) || empty($time)) {
        $error = "Please fill in all required fields.";
    } else {
        try {
            $appointment = new Appointment(
                $date,
                $time,
                $doctor_id,
                $_SESSION['user_id'], // Patient ID
                $reason,
                'scheduled'
            );
            
            $appRepo = new AppointmentRepository($pdo);
            $appRepo->save($appointment);
            
            $success = "Appointment booked successfully!";
        } catch (Exception $e) {
            $error = "Error booking appointment: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Appointment | Unity Care</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">Patient Portal</a>
            <div class="navbar-nav">
                <a href="index.php" style="margin-right: 1rem; color: white;">&larr; Back to Dashboard</a>
            </div>
        </div>
    </nav>

    <div class="container main-wrapper" style="justify-content: center;">
        <div class="card" style="width: 100%; max-width: 600px;">
            <h2 class="text-center">Book New Appointment</h2>
            
            <?php if ($error): ?>
                <div style="background: #FEE2E2; color: #991B1B; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div style="background: #D1FAE5; color: #065F46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <?php echo htmlspecialchars($success); ?> <a href="index.php">View Schedule</a>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Select Doctor</label>
                    <select name="doctor_id" required>
                        <option value="">-- Choose a Doctor --</option>
                        <?php foreach ($doctors as $doc): ?>
                            <option value="<?php echo $doc['id']; ?>">
                                Dr. <?php echo htmlspecialchars($doc['first_name'] . ' ' . $doc['last_name']); ?> 
                                (<?php echo htmlspecialchars($doc['specialization']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Time</label>
                        <input type="time" name="time" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Reason for Visit</label>
                    <textarea name="reason" rows="3" placeholder="Briefly describe your symptoms..."></textarea>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary">Confirm Booking</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
