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

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $userRepo = new UserRepository($pdo);
    if ($userRepo->delete($_POST['delete_id'])) {
        $success = "Doctor deleted successfully.";
    } else {
        $error = "Failed to delete doctor.";
    }
}

$doctors = $repo->findAllWithUserInfo();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Doctors | Unity Care</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Portal</a>
            <div class="navbar-nav">
                <a href="dashboard.php" style="margin-right: 1rem; color: white; text-decoration:none;">Dashboard</a>
                <a href="../logout.php" class="btn btn-secondary btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container main-wrapper">
        <div class="dashboard-grid w-100">
            <aside class="sidebar">
                <a href="dashboard.php">Dashboard Overview</a>
                <a href="doctors.php" class="active">Manage Doctors</a>
                <a href="patients.php">Manage Patients</a>
                <a href="appointments.php">View Appointments</a>
            </aside>

            <main>
                <?php if ($success): ?>
                    <div style="background: #D1FAE5; color: #065F46; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;"><?php echo $success; ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div style="background: #FEE2E2; color: #991B1B; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 2rem;">
                        <h3>Doctors List</h3> 
                        <a href="add_doctor.php" class="btn btn-primary">Add New Doctor</a>
                    </div>
                    
                    <table style="width:100%; border-collapse: collapse;">
                        <thead>
                            <tr style="text-align:left; border-bottom: 2px solid #eee;">
                                <th style="padding:1rem;">Name</th>
                                <th style="padding:1rem;">Specialization</th>
                                <th style="padding:1rem;">Phone</th>
                                <th style="padding:1rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($doctors)): ?>
                                <tr><td colspan="4" style="padding:1rem; text-align:center;">No doctors found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($doctors as $doc): ?>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding:1rem;">
                                        Dr. <?php echo htmlspecialchars($doc['first_name'] . ' ' . $doc['last_name']); ?>
                                        <br><small style="color:#888"><?php echo htmlspecialchars($doc['email']); ?></small>
                                    </td> 
                                    <td style="padding:1rem;"><?php echo htmlspecialchars($doc['specialization']); ?></td>
                                    <td style="padding:1rem;"><?php echo htmlspecialchars($doc['phone']); ?></td>
                                    <td style="padding:1rem;">
                                        <a href="edit_doctor.php?id=<?php echo $doc['id']; ?>" class="btn btn-primary btn-sm" style="margin-right:0.5rem; text-decoration:none;">Edit</a>
                                        <form method="POST" onsubmit="return confirm('Delete this doctor?');" style="display:inline;">
                                            <input type="hidden" name="delete_id" value="<?php echo $doc['id']; ?>">
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
