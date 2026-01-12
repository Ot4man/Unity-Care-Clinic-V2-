<?php
require_once __DIR__ . '/../../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$repo = new MedicationRepository($pdo);
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $instructions = $_POST['instructions'];
    
    if ($name && $instructions) {
        $med = new Medication($name, $instructions);
        $repo->save($med);
        $success = "Medication added.";
    } else {
        $error = "All fields required.";
    }
}

$medications = $repo->findAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Medications | Unity Care</title>
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
                <a href="appointments.php">Appointments</a>
                <a href="medications.php" class="active">Medications</a>
            </aside>

            <main>
                <h2>Pharmacy Inventory</h2>
                
                <?php if ($success): ?>
                    <div style="background: #D1FAE5; color: #065F46; padding: 1rem; border-radius: 8px; mb-4;"><?php echo $success; ?></div>
                <?php endif; ?>

                <div class="card mb-4">
                    <h3>Add Medication</h3>
                    <form method="POST" style="display: flex; gap: 1rem; align-items: end;">
                        <div style="flex:1;">
                            <label>Drug Name</label>
                            <input type="text" name="name" required placeholder="Name">
                        </div>
                        <div style="flex:2;">
                            <label>Instructions</label>
                            <input type="text" name="instructions" required placeholder="Dosage info">
                        </div>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </form>
                </div>

                <div class="card">
                     <table style="width:100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid #eee; text-align: left;">
                                <th style="padding:0.5rem;">Name</th>
                                <th style="padding:0.5rem;">Instructions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($medications as $m): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding:0.75rem 0.5rem;"><strong><?php echo htmlspecialchars($m['name']); ?></strong></td>
                                <td style="padding:0.75rem 0.5rem; color:#666;"><?php echo htmlspecialchars($m['instructions']); ?></td>
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
