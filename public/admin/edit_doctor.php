<?php
require_once __DIR__ . '/../../config/config.php';

// Check Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$repo = new DoctorRepository($pdo);
$error = '';
$success = '';

// Get Doctor ID
if (!isset($_GET['id'])) {
    header("Location: doctors.php");
    exit;
}

$doctor = $repo->findByIdWithUserInfo($_GET['id']);
if (!$doctor) {
    header("Location: doctors.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, phone = ? WHERE id = ?");
        $stmt->execute([
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['phone'],
            $doctor['id']
        ]);

        $stmtDoc = $pdo->prepare("UPDATE doctors SET specialization = ? WHERE id = ?");
        $stmtDoc->execute([
            $_POST['specialization'],
            $doctor['id']
        ]);
        
        $pdo->commit();
        $success = "Doctor updated successfully.";
        
        // Refresh data
        $doctor = $repo->findByIdWithUserInfo($_GET['id']);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Error updating doctor: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Doctor | Unity Care</title>
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
            <h2 class="text-center">Edit Doctor</h2>
            
            <?php if ($success): ?>
                <div style="background: #D1FAE5; color: #065F46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div style="background: #FEE2E2; color: #991B1B; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                     <label>Email (Read-only)</label>
                     <input type="text" value="<?php echo htmlspecialchars($doctor['email']); ?>" disabled style="background:#f9f9f9;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" value="<?php echo htmlspecialchars($doctor['first_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" value="<?php echo htmlspecialchars($doctor['last_name']); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($doctor['phone']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Specialization</label>
                    <input type="text" name="specialization" value="<?php echo htmlspecialchars($doctor['specialization']); ?>" required>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary">Update Doctor</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
