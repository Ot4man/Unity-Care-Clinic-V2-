<?php
require_once __DIR__ . '/../../config/config.php';

// Check Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $dob = $_POST['date_of_birth'] ?? '';
    $address = $_POST['address'] ?? '';
    
    // Default password
    $password = '123456'; 
    $hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $pdo->beginTransaction();

        // 1. Create Patient Object (User + Patient data)
        $patient = new Patient(
            null, 
            $email, 
            $first_name, 
            $last_name, 
            $phone, 
            $hash, 
            $gender, 
            $dob,
            $address
        );

        // 2. Save User Info (UserRepository handles the 'users' table)
        $userRepo = new UserRepository($pdo);
        if ($userRepo->findByEmail($email)) {
            throw new Exception("Email already exists.");
        }
        $userRepo->save($patient); // This inserts into 'users' and sets $patient->id

        // 3. Save Patient Info (PatientRepository handles the 'patients' table)
        $patRepo = new PatientRepository($pdo);
        $patRepo->save($patient);

        $pdo->commit();
        $success = "Patient created successfully! Default password is '123456'.";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Error adding patient: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Patient | Unity Care</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Portal</a>
            <div class="navbar-nav">
                <a href="patients.php" style="margin-right: 1rem; color: white;">&larr; Back to Patients</a>
            </div>
        </div>
    </nav>

    <div class="container main-wrapper" style="justify-content: center;">
        <div class="card" style="width: 100%; max-width: 600px;">
            <h2 class="text-center">Register New Patient</h2>
            
            <?php if ($error): ?>
                <div style="background: #FEE2E2; color: #991B1B; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div style="background: #D1FAE5; color: #065F46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <?php echo htmlspecialchars($success); ?> <a href="patients.php">View List</a>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender" required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="date_of_birth" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" required>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary">Register Patient</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
