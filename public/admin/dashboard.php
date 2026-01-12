<?php
require_once __DIR__ . '/../../config/config.php';

// 1. Check if user is logged in and is Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Unity Care</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Portal</a>
            <div class="navbar-nav">
                <span style="opacity: 0.8; margin-right: 1rem;">
                    Admin: <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </span>
                <a href="../logout.php" class="btn btn-secondary btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container main-wrapper">
        <div class="dashboard-grid w-100">
            
            <!-- Sidebar Navigation -->
            <aside class="sidebar">
                <a href="dashboard.php" class="active">Dashboard Overview</a>
                <a href="doctors.php">Manage Doctors</a>
                <a href="patients.php">Manage Patients</a>
                <a href="appointments.php">View Appointments</a>
                <a href="stats.php">Statistics</a>
            </aside>

            <!-- Main Content Area -->
            <main>
                <h2>Welcome, Administrator</h2>
                <div class="card">
                    <h3>Quick Actions</h3>
                    <p>Manage your clinic staff and patients efficiently.</p>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 1rem;">
                        <a href="doctors.php" class="btn btn-primary" style="text-align:center;">Manage Doctors</a>
                        <a href="patients.php" class="btn btn-outline" style="text-align:center;">Manage Patients</a>
                    </div>
                </div>

                <div class="card mt-4">
                    <h3>System Status</h3>
                    <p>System is running smoothly. Date: <?php echo date('Y-m-d'); ?></p>
                </div>
            </main>
        </div>
    </div>
</body>
</html>