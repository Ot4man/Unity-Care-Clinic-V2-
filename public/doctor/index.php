<?php
require_once __DIR__ . '/../../config/config.php';

// Check Role
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'doctor') {
    header("Location: ../login.php");
    exit;
}

$repo = new AppointmentRepository($pdo);

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'], $_POST['status'])) {
    $repo->updateStatus($_POST['appointment_id'], $_POST['status']);
    // Refresh to show update
    header("Location: index.php");
    exit;
}

$appointments = $repo->findAllByDoctorWithPatientInfo($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Portal | Unity Care</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">Doctor Portal</a>
            <div class="navbar-nav">
                <span style="opacity: 0.8; margin-right: 1rem;">
                    Dr. <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </span>
                <a href="../logout.php" class="btn btn-secondary btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container main-wrapper">
        <div class="dashboard-grid w-100">
            <aside class="sidebar">
                <a href="index.php" class="active">My Schedule</a>
                <a href="#" onclick="alert('Patients history feature coming soon')">Patient Records</a>
                <a href="#" onclick="alert('Profile feature coming soon')">Profile</a>
            </aside>

            <main>
                <h2>Today's Schedule</h2>
                <div class="card">
                    <?php if (empty($appointments)): ?>
                        <p>No appointments scheduled.</p>
                    <?php else: ?>
                        <table style="width:100%; border-collapse: collapse;">
                            <thead>
                                <tr style="text-align:left; border-bottom: 2px solid #eee;">
                                    <th style="padding:1rem;">Date & Time</th>
                                    <th style="padding:1rem;">Patient</th>
                                    <th style="padding:1rem;">Reason</th>
                                    <th style="padding:1rem;">Status</th>
                                    <th style="padding:1rem;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($appointments as $app): ?>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding:1rem;">
                                        <?php echo htmlspecialchars($app['date']); ?> <br>
                                        <small style="color:#78866B; font-weight:bold;"><?php echo htmlspecialchars(substr($app['time'], 0, 5)); ?></small>
                                    </td>
                                    <td style="padding:1rem;">
                                        <?php echo htmlspecialchars($app['patient_first'] . ' ' . $app['patient_last']); ?>
                                    </td>
                                    <td style="padding:1rem;">
                                        <?php echo htmlspecialchars($app['reason']); ?>
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
                                    <td style="padding:1rem;">
                                        <?php if ($app['status'] === 'scheduled'): ?>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="appointment_id" value="<?php echo $app['id']; ?>">
                                                <input type="hidden" name="status" value="done">
                                                <button type="submit" class="btn btn-primary btn-sm" style="padding: 0.4rem 0.8rem;">Mark Done</button>
                                            </form>
                                            <form method="POST" onsubmit="return confirm('Cancel this appointment?');" style="display:inline; margin-left: 0.5rem;">
                                                <input type="hidden" name="appointment_id" value="<?php echo $app['id']; ?>">
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="btn btn-secondary btn-sm" style="padding: 0.4rem 0.8rem; background: #EF4444; border:none; color: white;">Cancel</button>
                                            </form>
                                        <?php else: ?>
                                            <span style="color:#aaa;">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
