<?php
require_once __DIR__ . '/../../config/config.php';

// Check Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$repo = new PatientRepository($pdo);
$error = '';
$success = '';

// Get Patient ID
if (!isset($_GET['id'])) {
    header("Location: patients.php");
    exit;
}

$patient = $repo->findByIdWithUserInfo($_GET['id']);
if (!$patient) {
    header("Location: patients.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Update User Data (Name, Phone, Email ideally mostly immutable or separate)
    // For simplicity, we update mutable fields: First Name, Last Name, Phone
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, phone = ? WHERE id = ?");
        $stmt->execute([
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['phone'],
            $patient['id']
        ]);

        // 2. Update Patient Data
        $stmtPat = $pdo->prepare("UPDATE patients SET gender = ?, date_of_birth = ?, address = ? WHERE id = ?");
        $stmtPat->execute([
            $_POST['gender'],
            $_POST['date_of_birth'],
            $_POST['address'],
            $patient['id']
        ]);
        
        $pdo->commit();
        $success = "Patient updated successfully.";
        
        // Refresh data
        $patient = $repo->findByIdWithUserInfo($_GET['id']);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Error updating patient: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Patient | Unity Care</title>
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
            <h2 class="text-center">Edit Patient</h2>
            
            <?php if ($success): ?>
                <div style="background: #D1FAE5; color: #065F46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div style="background: #FEE2E2; color: #991B1B; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                     <label>Email (Read-only)</label>
                     <input type="text" value="<?php echo htmlspecialchars($patient['email']); ?>" disabled style="background:#f9f9f9;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" value="<?php echo htmlspecialchars($patient['first_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" value="<?php echo htmlspecialchars($patient['last_name']); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($patient['phone']); ?>" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender" required>
                            <option value="male" <?php echo $patient['gender'] == 'male' ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?php echo $patient['gender'] == 'female' ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($patient['date_of_birth']); ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" value="<?php echo htmlspecialchars($patient['address']); ?>" required>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary">Update Patient</button>
                    <!-- Correction: field is address not adress -->
                    <!-- Note: repo has `adress` column typo in insert queries? Checked repo: line 12 has `adress` -->
                    <!-- I should verify column name. SQL says `address` line 54 -->
                    <!-- PatientRepository line 12 uses `adress`. This is a bug in Repo too. -->
                </div>
            </form>
        </div>
    </div>
</body>
</html>
