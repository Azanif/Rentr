<?php
session_start();
require_once 'config.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$success = getFlashMessage();

// Check for logout
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    $success = 'You have been successfully logged out.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    // Basic validation
    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password';
    } else {
        try {
            $conn = getDBConnection();
            
            // Get user by email
            $sql = "SELECT user_id, email, password, full_name, user_role FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                
                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Check if password needs rehashing (if we update hashing algorithm in the future)
                    if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
                        $newHash = password_hash($password, PASSWORD_DEFAULT);
                        // Update the user's password in the database
                        $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                        $updateStmt->bind_param("si", $newHash, $user['user_id']);
                        $updateStmt->execute();
                        $updateStmt->close();
                    }
                    
                    // Set session variables
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['user_role'] = $user['user_role'];
                    $_SESSION['full_name'] = $user['full_name'];
                    
                    // Set remember me cookie if requested (30 days)
                    if ($remember) {
                        $token = bin2hex(random_bytes(32));
                        $expires = time() + (30 * 24 * 60 * 60); // 30 days
                        setcookie('remember_token', $token, $expires, '/');
                        
                        // Store token in database
                        $updateToken = $conn->prepare("UPDATE users SET remember_token = ?, token_expires = FROM_UNIXTIME(?) WHERE user_id = ?");
                        $updateToken->bind_param("sii", $token, $expires, $user['user_id']);
                        $updateToken->execute();
                        $updateToken->close();
                    }
                    
                    // Update last login time
                    $updateLogin = $conn->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
                    $updateLogin->bind_param("i", $user['user_id']);
                    $updateLogin->execute();
                    $updateLogin->close();
                    
                    // Redirect to dashboard
                    $_SESSION['flash_message'] = 'Welcome back, ' . htmlspecialchars($user['full_name']) . '!';
                    header('Location: dashboard.php');
                    exit();
                    
                } else {
                    $error = 'Invalid email or password';
                }
            } else {
                $error = 'No account found with that email address';
            }
            
            $stmt->close();
            $conn->close();
            
        } catch (Exception $e) {
            $error = 'An error occurred. Please try again later.';
            error_log("Login error: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rentr.com</title>
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --primary-blue: #1a73e8;
            --secondary-blue: #0d47a1;
            --light-blue: #e8f0fe;
            --dark-blue: #0d47a1;
            --white: #ffffff;
            --gray: #f5f5f5;
            --dark-gray: #333333;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('images/perlis.jpg') no-repeat center center/cover;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: #333;
            line-height: 1.6;
        }
        
        .main-header {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 15px 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }
        
        .logo a {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-blue);
            text-decoration: none;
        }
        
        .main-nav ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            align-items: center;
        }
        
        .main-nav li {
            margin-left: 30px;
        }
        
        .main-nav a {
            color: var(--dark-gray);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .main-nav a:hover {
            color: var(--primary-blue);
        }
        
        .auth-buttons {
            display: flex;
            gap: 15px;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
            border: 1px solid transparent;
            cursor: pointer;
        }
        
        /* Primary button styles (used for login and signup buttons) */
        .btn-primary,
        .btn-login,
        .btn-signup {
            background-color: var(--primary-blue);
            color: white;
            border-color: var(--primary-blue);
        }
        
        .btn-primary:hover,
        .btn-login:hover,
        .btn-signup:hover {
            background-color: var(--secondary-blue);
            border-color: var(--secondary-blue);
            color: white;
        }
            border-color: var(--secondary-blue);
        }

        .login-container {
            max-width: 500px;
            margin: auto;
            padding: 40px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .title {
            color: var(--primary-blue);
            margin-bottom: 10px;
        }

        .error {
            background: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn-primary {
            width: 100%;
            padding: 14px;
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .signup {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .signup a {
            color: #0066cc;
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="container">
            <div class="logo">
                <a href="index.php">Rentr.</a>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li class="auth-buttons">
                        <a href="login.php" class="btn btn-login">Login</a>
                        <a href="register.php" class="btn btn-signup">Sign Up</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="login-container">
        <h1 class="title">Rentr.com</h1>
        <h2 style="text-align: center; margin-bottom: 30px; color: #0066cc;">Welcome To Rentr.</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST" style="margin-bottom: 20px;">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <div class="signup">Don't have an account? <a href="register.php">Sign up</a></div>
    </div>
</body>
</html>
