<?php
require_once __DIR__ . '/../../config/config.php';

// Check Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$repo = new DepartmentRepository($pdo);
$error = '';
$success = '';
$dept = null;

if (isset($_GET['id'])) {
    $deptInfo = $repo->findById($_GET['id']); 
    // Need to create a findById method if it doesn't return object, usually BaseRepository returns array.
    // Our BaseRepository::findById returns array.
    
    if (!$deptInfo) {
        header("Location: departments.php");
        exit;
    }
} else {
    header("Location: departments.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $location = $_POST['location'];
    
    if ($name && $location) {
        // Construct object with ID
        $updatedDept = new Department($name, $location, $deptInfo['id']);
        if ($repo->save($updatedDept)) {
            $success = "Department updated successfully.";
            // Update local var to show new values
            $deptInfo['name'] = $name;
            $deptInfo['location'] = $location;
        } else {
            $error = "Failed to update.";
        }
    } else {
        $error = "All fields required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Department | Unity Care</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Portal</a>
            <div class="navbar-nav">
                <a href="departments.php" style="margin-right: 1rem; color: white;">&larr; Back to Departments</a>
            </div>
        </div>
    </nav>

    <div class="container main-wrapper" style="justify-content: center;">
        <div class="card" style="width: 100%; max-width: 600px;">
            <h2 class="text-center">Edit Department</h2>
            
            <?php if ($error): ?>
                <div style="background: #FEE2E2; color: #991B1B; padding: 1rem; border-radius: 8px; mb-4;"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div style="background: #D1FAE5; color: #065F46; padding: 1rem; border-radius: 8px; mb-4;"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Department Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($deptInfo['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" value="<?php echo htmlspecialchars($deptInfo['location']); ?>" required>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
