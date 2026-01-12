<?php
require_once __DIR__ . '/../../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$repo = new PatientRepository($pdo);
$error = '';
$success = '';

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    // Delete user from 'users' table, cascade deletes patient
    $userRepo = new UserRepository($pdo);
    if ($userRepo->delete($_POST['delete_id'])) {
        $success = "Patient deleted successfully.";
    } else {
        $error = "Failed to delete patient.";
    }
}

$patients = $repo->findAllWithUserInfo();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Patients | Unity Care</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Portal</a>
            <div class="navbar-nav">
                <a href="dashboard.php" style="margin-right: 1rem; color: white;">Dashboard</a>
                <a href="../logout.php" class="btn btn-secondary btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container main-wrapper">
        <div class="dashboard-grid w-100">
            <aside class="sidebar">
                <a href="dashboard.php">Dashboard</a>
                <a href="doctors.php">Doctors</a>
                <a href="departments.php">Departments</a>
                <a href="patients.php" class="active">Patients</a>
                <a href="appointments.php">Appointments</a>
                <a href="medications.php">Medications</a>
            </aside>

            <main>
                <h2>Registered Patients</h2>
                
                <?php if ($success): ?>
                    <div style="background: #D1FAE5; color: #065F46; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;"><?php echo $success; ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div style="background: #FEE2E2; color: #991B1B; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1rem;">
                        <h3>Patient List</h3>
                        <a href="add_patient.php" class="btn btn-primary">Add Patient</a>
                    </div>
                    <table style="width:100%; border-collapse: collapse;">
                        <thead>
                            <tr style="text-align:left; border-bottom: 2px solid #eee;">
                                <th style="padding:1rem;">Name</th>
                                <th style="padding:1rem;">Contact</th>
                                <th style="padding:1rem;">Gender/DOB</th>
                                <th style="padding:1rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($patients)): ?>
                                <tr><td colspan="4" style="padding:1rem; text-align:center;">No patients found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($patients as $p): ?>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding:1rem;">
                                        <?php echo htmlspecialchars($p['first_name'] . ' ' . $p['last_name']); ?>
                                    </td>
                                    <td style="padding:1rem;">
                                        <?php echo htmlspecialchars($p['email']); ?><br>
                                        <small><?php echo htmlspecialchars($p['phone'] ?? '-'); ?></small>
                                    </td> 
                                    <td style="padding:1rem;">
                                        <?php echo htmlspecialchars(ucfirst($p['gender'] ?? '-')); ?> / 
                                        <?php echo htmlspecialchars($p['date_of_birth'] ?? '-'); ?>
                                    </td>
                                    <td style="padding:1rem;">
                                        <a href="edit_patient.php?id=<?php echo $p['id']; ?>" class="btn btn-primary btn-sm" style="margin-right:0.5rem; text-decoration:none;">Edit</a>
                                        <form method="POST" onsubmit="return confirm('Delete this patient?');" style="display:inline;">
                                            <input type="hidden" name="delete_id" value="<?php echo $p['id']; ?>">
                                            <button type="submit" class="btn btn-secondary btn-sm" style="background: #EF4444; border:none; color:white;">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
