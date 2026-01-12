<?php
require_once __DIR__ . '/../../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$repo = new DepartmentRepository($pdo);
$error = '';
$success = '';

// Handle Add/Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_dept'])) {
        $name = $_POST['name'];
        $location = $_POST['location'];
        if ($name && $location) {
            $dept = new Department($name, $location);
            $repo->save($dept);
            $success = "Department added successfully.";
        } else {
            $error = "Name and Location are required.";
        }
    } elseif (isset($_POST['delete_id'])) {
        // Simple delete - might fail if foreign keys exist (doctors), but fine for basic version
        // Ideally should check constraints.
        try {
            // Raw delete query via repository or ad-hoc
            // DepartmentRepository only has save, let's add delete or do it raw here for speed
            // Adding ad-hoc delete since I missed adding delete() to Repo
           $stmt = $pdo->prepare("DELETE FROM departments WHERE id = ?");
           $stmt->execute([$_POST['delete_id']]);
           $success = "Department deleted.";
        } catch (PDOException $e) {
            $error = "Cannot delete department (it might have doctors assigned).";
        }
    }
}

$departments = $repo->findAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Departments | Unity Care</title>
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
                <a href="departments.php" class="active">Departments</a>
                <a href="patients.php">Patients</a>
                <a href="appointments.php">Appointments</a>
                <a href="medications.php">Medications</a>
            </aside>

            <main>
                <h2>Manage Departments</h2>
                
                <?php if ($error): ?>
                    <div style="background: #FEE2E2; color: #991B1B; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div style="background: #D1FAE5; color: #065F46; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>

                <!-- Add Department Form -->
                <div class="card mb-4">
                    <h3>Add New Department</h3>
                    <form method="POST" style="display: flex; gap: 1rem; align-items: end;">
                        <input type="hidden" name="add_dept" value="1">
                        <div style="flex:1;">
                            <label>Name</label>
                            <input type="text" name="name" required placeholder="e.g. Ophthalmic">
                        </div>
                        <div style="flex:1;">
                            <label>Location</label>
                            <input type="text" name="location" required placeholder="e.g. Building D">
                        </div>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </form>
                </div>

                <!-- Departments List -->
                <div class="card">
                    <h3>Existing Departments</h3>
                    <table style="width:100%; border-collapse: collapse; margin-top: 1rem;">
                        <thead>
                            <tr style="text-align:left; border-bottom: 2px solid #eee;">
                                <th style="padding:0.5rem;">Name</th>
                                <th style="padding:0.5rem;">Location</th>
                                <th style="padding:0.5rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($departments as $d): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding:0.75rem 0.5rem;"><?php echo htmlspecialchars($d['name']); ?></td>
                                <td style="padding:0.75rem 0.5rem;"><?php echo htmlspecialchars($d['location']); ?></td>
                                <td style="padding:0.75rem 0.5rem;">
                                    <a href="edit_department.php?id=<?php echo $d['id']; ?>" class="btn btn-primary btn-sm" style="margin-right:0.5rem;">Edit</a>
                                    <form method="POST" onsubmit="return confirm('Are you sure?');" style="display:inline;">
                                        <input type="hidden" name="delete_id" value="<?php echo $d['id']; ?>">
                                        <button type="submit" class="btn btn-secondary btn-sm" style="background: #EF4444; border:none; color:white;">Delete</button>
                                    </form>
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
