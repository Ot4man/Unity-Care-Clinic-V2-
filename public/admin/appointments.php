<?php
require_once __DIR__ . '/../../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$repo = new AppointmentRepository($pdo);
$appointments = $repo->findAllWithDetails();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Appointments | Unity Care</title>
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
                <a href="patients.php">Patients</a>
                <a href="appointments.php" class="active">Appointments</a>
                <a href="medications.php">Medications</a>
            </aside>

            <main>
                <h2>All System Appointments</h2>
                <div class="card">
                    <table style="width:100%; border-collapse: collapse;">
                        <thead>
                            <tr style="text-align:left; border-bottom: 2px solid #eee;">
                                <th style="padding:1rem;">Date</th>
                                <th style="padding:1rem;">Doctor</th>
                                <th style="padding:1rem;">Patient</th>
                                <th style="padding:1rem;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($appointments)): ?>
                                <tr><td colspan="4" style="padding:1rem; text-align:center;">No appointments found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($appointments as $app): ?>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding:1rem;">
                                        <?php echo htmlspecialchars($app['date']); ?> <br>
                                        <small><?php echo htmlspecialchars(substr($app['time'], 0, 5)); ?></small>
                                    </td>
                                    <td style="padding:1rem;">
                                        Dr. <?php echo htmlspecialchars($app['doctor_first'] . ' ' . $app['doctor_last']); ?>
                                    </td>
                                    <td style="padding:1rem;">
                                        <?php echo htmlspecialchars($app['patient_first'] . ' ' . $app['patient_last']); ?>
                                    </td>
                                    <td style="padding:1rem;">
                                        <span style="
                                            padding: 0.25rem 0.5rem; 
                                            border-radius: 4px; 
                                            font-size: 0.8rem;
                                            background: <?php echo $app['status'] === 'done' ? '#D1FAE5' : ($app['status'] === 'cancelled' ? '#FEE2E2' : '#FEF3C7'); ?>;
                                            color: <?php echo $app['status'] === 'done' ? '#065F46' : ($app['status'] === 'cancelled' ? '#991B1B' : '#92400E'); ?>;
                                        ">
                                            <?php echo ucfirst($app['status']); ?>
                                        </span>
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
