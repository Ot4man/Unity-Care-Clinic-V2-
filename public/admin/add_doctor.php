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
    $specialization = $_POST['specialization'] ?? '';
    // Default password for new doctors
    $password = '123456'; 
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    
    $department_id = $_POST['department_id'] ?? 1;

    try {
        $pdo->beginTransaction();

        // 1. Create Doctor Object (initially without ID)
        $doctor = new Doctor(
            null, 
            $email, 
            $first_name, 
            $last_name, 
            $phone, 
            $hash, 
            $specialization, 
            $department_id
        );

        // 2. Save User Info (UserRepository handles the 'users' table)
        // Since Doctor extends User, we can pass it here.
        $userRepo = new UserRepository($pdo);
        if ($userRepo->findByEmail($email)) {
            throw new Exception("Email already exists.");
        }
        $userRepo->save($doctor); // This inserts into 'users' and sets $doctor->id

        // 3. Save Doctor Info (DoctorRepository handles the 'doctors' table)
        $docRepo = new DoctorRepository($pdo);
        $docRepo->save($doctor);

        $pdo->commit();
        $success = "Doctor created successfully! Default password is '123456'.";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Error adding doctor: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Doctor | Unity Care</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Portal</a>
            <div class="navbar-nav">
                <a href="doctors.php" style="margin-right: 1rem; color: white;">&larr; Back to Doctors</a>
            </div>
        </div>
    </nav>

    <div class="container main-wrapper" style="justify-content: center;">
        <div class="card" style="width: 100%; max-width: 600px;">
            <h2 class="text-center">Add New Doctor</h2>
            
            <?php if ($error): ?>
                <div style="background: #FEE2E2; color: #991B1B; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div style="background: #D1FAE5; color: #065F46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <?php echo htmlspecialchars($success); ?> <a href="doctors.php">View List</a>
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

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" required>
                </div>

                <div class="form-group">
                    <label>Specialization</label>
                    <input type="text" name="specialization" required placeholder="e.g. Cardiologist">
                </div>
                
                <div class="form-group">
                    <label>Department</label>
                    <select name="department_id" required>
                        <option value="1">Cardiology</option>
                        <option value="2">Neurology</option>
                        <option value="3">Pediatrics</option>
                        <option value="4">General</option>
                    </select>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary">Create Doctor Account</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
