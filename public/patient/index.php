<?php
require_once __DIR__ . '/../../config/config.php';

// Check Role
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'patient') {
    header("Location: ../login.php");
    exit;
}

$repo = new AppointmentRepository($pdo);
$appointments = $repo->findAllByPatientWithDoctorInfo($_SESSION['user_id']);

// Separate into upcoming vs history
$upcoming = [];
$history = [];
$today = date('Y-m-d H:i:s');

foreach ($appointments as $app) {
    // Combine date and time to compare
    $appDateTime = $app['date'] . ' ' . $app['time'];
    if ($app['status'] === 'scheduled' && $appDateTime >= $today) {
        $upcoming[] = $app;
    } else {
        $history[] = $app;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Portal | Unity Care</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">Patient Portal</a>
            <div class="navbar-nav">
                <span style="opacity: 0.8; margin-right: 1rem;">
                    <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </span>
                <a href="../logout.php" class="btn btn-secondary btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container main-wrapper">
        <div class="dashboard-grid w-100">
            <aside class="sidebar">
                <a href="index.php" class="active">My Overview</a>
                <a href="book.php">Book Appointment</a>
                <a href="#" onclick="alert('Feature coming soon')">Profile Settings</a>
            </aside>

            <main>
                <h2>Welcome Back</h2>
                
                <div class="card mb-4">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <h3>Upcoming Appointments</h3>
                        <a href="book.php" class="btn btn-primary btn-sm">New Booking</a>
                    </div>
                    
                    <?php if (empty($upcoming)): ?>
                        <p style="margin-top: 1rem;">No upcoming appointments.</p>
                    <?php else: ?>
                        <div style="margin-top:1rem;">
                            <?php foreach ($upcoming as $app): ?>
                                <div style="background: #F0F2EE; padding: 1rem; border-radius: 8px; margin-bottom: 0.5rem; display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <strong><?php echo htmlspecialchars($app['date']); ?> at <?php echo htmlspecialchars(substr($app['time'], 0, 5)); ?></strong><br>
                                        Dr. <?php echo htmlspecialchars($app['doctor_first'] . ' ' . $app['doctor_last']); ?> (<?php echo htmlspecialchars($app['specialization']); ?>)
                                    </div>
                                    <span style="background: #FEF3C7; color: #92400E; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.9rem;">Scheduled</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card">
                    <h3>Medical History</h3>
                    <table style="width:100%; border-collapse: collapse; margin-top: 1rem;">
                        <tbody>
                            <?php foreach ($history as $app): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding:0.75rem 0; color: #666;">
                                    <?php echo htmlspecialchars($app['date']); ?>
                                </td>
                                <td style="padding:0.75rem 0;">
                                    Dr. <?php echo htmlspecialchars($app['doctor_first'] . ' ' . $app['doctor_last']); ?>
                                </td>
                                <td style="padding:0.75rem 0;">
                                    <?php echo htmlspecialchars($app['reason']); ?>
                                </td>
                                <td style="padding:0.75rem 0; text-align:right;">
                                    <span style="
                                        font-size: 0.8rem; 
                                        color: <?php echo $app['status'] === 'done' ? '#065F46' : '#991B1B'; ?>;
                                    ">
                                        <?php echo ucfirst($app['status']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
