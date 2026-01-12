<?php
require_once __DIR__ . '/../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Role-based Redirection
switch ($_SESSION['user_role']) {
    case 'admin':
        header("Location: admin/dashboard.php");
        exit;
    case 'doctor':
        header("Location: doctor/index.php");
        exit;
    case 'patient':
        header("Location: patient/index.php");
        exit;
    default:
        // Fallback
        break;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Unity Care</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">Unity Care Clinic</a>
            <div class="navbar-nav">
                <span style="opacity: 0.8; margin-right: 1rem;">
                    Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Guest'); ?>
                </span>
                <a href="logout.php" class="btn btn-secondary btn-sm" style="padding: 0.5rem 1rem;">Logout</a>
            </div>
        </div>
    </nav>
    <div class="container main-wrapper">
        <div class="dashboard-grid w-100">
            <!-- Sidebar -->
            <aside class="sidebar">
                <a href="#" class="active">Overview</a>
                <a href="#">My Appointments</a>
                <a href="#">Medical History</a>
                <a href="#">Profile Settings</a>
            </aside>

            <!-- Main Content -->
            <main>
                <div class="card">
                    <h2>Overview</h2>
                    <p>Welcome to your patient portal. Manage your health conveniently.</p>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
                        <button class="btn btn-primary">Book Appointment</button>
                        <button class="btn btn-outline">View Prescriptions</button>
                    </div>
                </div>

                <div class="card mt-4">
                    <h3>Upcoming Schedule</h3>
                    <p>No upcoming appointments found.</p>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
