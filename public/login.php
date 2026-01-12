<?php
require_once __DIR__ . '/../config/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        try {
            // $pdo is already created in config.php
            $userRepo = new UserRepository($pdo);
            $user = $userRepo->findByEmail($email);

            if ($user && $user->verifyPassword($password)) {
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['user_role'] = $user->getRole();
                $_SESSION['user_name'] = $user->getFullName();
                
                // Redirect based on role or to a common dashboard
                header("Location: index.php"); 
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        } catch (Exception $e) {
            $error = "System error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Unity Care</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #F9FAF8 0%, #E8EBE6 100%);
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 3rem;
            text-align: center;
        }
        .logo-mark {
            width: 50px;
            height: 50px;
            background-color: var(--primary-color);
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="card login-card">
        <div class="logo-mark">U</div>
        <h2 style="margin-bottom: 0.5rem;">Welcome Back</h2>
        <p style="margin-bottom: 2rem;">Sign in to Unity Care Clinic</p>
        
        <?php if ($error): ?>
            <div style="background: #FEE2E2; color: #991B1B; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; text-align: left; font-size: 0.9rem;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" style="text-align: left;">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required placeholder="name@example.com">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>
            <div class="d-grid" style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary w-100">Sign In</button>
            </div>
        </form>
    </div>
</body>
</html>
