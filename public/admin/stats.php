<?php
require_once __DIR__ . '/../../config/config.php';

// Check Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$apptRepo = new AppointmentRepository($pdo);
$presRepo = new PrescriptionRepository($pdo);

// Fetch Data
$statusCounts = $apptRepo->countByStatus(); // ['done'=>5, 'scheduled'=>10]
$topDoctors = $apptRepo->countByDoctor();
$topMeds = $presRepo->getTopMedications();

// Prepare counts for safe display
$scheduled = $statusCounts['scheduled'] ?? 0;
$done = $statusCounts['done'] ?? 0;
$cancelled = $statusCounts['cancelled'] ?? 0;
$total = $scheduled + $done + $cancelled;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Statistics | Unity Care Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-color);
            margin: 0.5rem 0;
        }
        .stat-label {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .chart-container {
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 1.5rem; 
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Portal</a>
            <div class="navbar-nav">
                <a href="dashboard.php" style="margin-right: 1rem; color: white;">&larr; Dashboard</a>
            </div>
        </div>
    </nav>

    <div class="container main-wrapper">
        <div class="w-100">
            <h2 class="mb-4">Clinic Statistics</h2>

            <!-- KEY METRICS -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="stat-card">
                    <div class="stat-label">Total Appointments</div>
                    <div class="stat-number"><?php echo $total; ?></div>
                </div>
                <div class="stat-card" style="border-top: 4px solid #F59E0B;">
                    <div class="stat-label">Scheduled</div>
                    <div class="stat-number" style="color: #F59E0B;"><?php echo $scheduled; ?></div>
                </div>
                <div class="stat-card" style="border-top: 4px solid #10B981;">
                    <div class="stat-label">Completed</div>
                    <div class="stat-number" style="color: #10B981;"><?php echo $done; ?></div>
                </div>
                <div class="stat-card" style="border-top: 4px solid #EF4444;">
                    <div class="stat-label">Cancelled</div>
                    <div class="stat-number" style="color: #EF4444;"><?php echo $cancelled; ?></div>
                </div>
            </div>

            <div class="chart-container">
                <!-- TOP DOCTORS -->
                <div class="card">
                    <h3>Top Doctors (by Appointments)</h3>
                    <table style="width:100%; margin-top:1rem; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid #eee; text-align:left;">
                                <th style="padding:0.5rem;">Doctor</th>
                                <th style="padding:0.5rem; text-align:right;">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($topDoctors)): ?>
                                <tr><td colspan="2" style="padding:1rem;">No data available</td></tr>
                            <?php else: ?>
                                <?php foreach ($topDoctors as $doc): ?>
                                    <tr style="border-bottom: 1px solid #f9f9f9;">
                                        <td style="padding:0.75rem 0.5rem;"><?php echo htmlspecialchars($doc['name']); ?></td>
                                        <td style="padding:0.75rem 0.5rem; text-align:right;">
                                            <span style="background:#eef2ff; color:var(--primary-color); padding:0.2rem 0.6rem; border-radius:12px; font-weight:bold;">
                                                <?php echo $doc['count']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- TOP MEDICATIONS -->
                <div class="card">
                    <h3>Most Prescribed Medications</h3>
                    <table style="width:100%; margin-top:1rem; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid #eee; text-align:left;">
                                <th style="padding:0.5rem;">Medication</th>
                                <th style="padding:0.5rem; text-align:right;">Prescriptions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($topMeds)): ?>
                                <tr><td colspan="2" style="padding:1rem;">No prescriptions yet</td></tr>
                            <?php else: ?>
                                <?php foreach ($topMeds as $med): ?>
                                    <tr style="border-bottom: 1px solid #f9f9f9;">
                                        <td style="padding:0.75rem 0.5rem;"><?php echo htmlspecialchars($med['name']); ?></td>
                                        <td style="padding:0.75rem 0.5rem; text-align:right;">
                                            <span style="background:#ecfdf5; color:#065F46; padding:0.2rem 0.6rem; border-radius:12px; font-weight:bold;">
                                                <?php echo $med['count']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
