<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';

// Initialize variables
$userData = [];
$error = '';

// Get user information from database
try {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT * FROM Users WHERE UserID = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $userData = $result->fetch_assoc();
    } else {
        // User not found, log them out
        session_destroy();
        header("Location: login.php?error=user_not_found");
        exit();
    }
    
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    $error = "Error loading user data. Please try again later.";
    error_log("Dashboard error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Rentr.com</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80') 
                        no-repeat center center/cover;
            min-height: 100vh;
            color: #333;
            line-height: 1.6;
        }
        
        /* Header Styles */
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
            color: #333;
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
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .main-nav a:hover {
            color: #007bff;
        }
        
        .auth-buttons {
            display: flex;
            gap: 15px;
        }
        
        .btn {
            padding: 8px 20px;
            border-radius: 4px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .btn-login {
            color: #333;
            border: 1px solid #ddd;
        }
        
        .btn-login:hover {
            border-color: #007bff;
            color: #007bff;
        }
        
        .btn-signup {
            background-color: #007bff;
            color: white;
            border: 1px solid #007bff;
        }
        
        .btn-signup:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        
        /* Dashboard Styles */
        .dashboard-container {
            max-width: 1200px;
            margin: 80px auto 40px;
            padding: 40px 20px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .welcome-message {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .welcome-message h1 {
            color: #0066cc;
            margin-bottom: 10px;
        }
        
        .welcome-message p {
            color: #666;
            margin-bottom: 20px;
        }
        
        .properties-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }
        
        .property-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .property-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 4px;
        }
        .logout-btn {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #666;
            text-decoration: none;
        }
        .logout-btn:hover {
            color: #333;
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="container">
            <div class="logo">
                <a href="index.php">Rentr.com</a>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="dashboard.php">Home</a></li>
                    <li><a href="properties.php">Properties</a></li>
                    <li><a href="dashboard.php">My Account</a></li>
                    <div class="user-actions">
                        <span class="welcome-message">Welcome, <?php echo htmlspecialchars($userData['FullName'] ?? 'User'); ?></span>
                        <a href="logout.php" class="btn">Logout</a>
                    </div>
                </ul>
            </nav>
        </div>
    </header>

    <div class="dashboard-container">
        <div class="welcome-message">
            <h1>Welcome back, <?php echo htmlspecialchars($email); ?>!</h1>
            <p>Manage your rental properties or find your next home.</p>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <h2>Available Properties</h2>
        <div class="properties-grid">
            <?php
            try {
                $conn = getDBConnection();
                $sql = "SELECT * FROM Properties WHERE IsAvailable = 1";
                $stmt = sqlsrv_query($conn, $sql);
                
                if ($stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }
                
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    echo '<div class="property-card">';
                    echo '<img src="' . htmlspecialchars($row['ImageURL'] ?? 'property.jpg') . '" alt="Property Image">';
                    echo '<h3>' . htmlspecialchars($row['Title']) . '</h3>';
                    echo '<p>' . htmlspecialchars($row['Description']) . '</p>';
                    echo '<p><strong>$' . number_format($row['Price'], 2) . ' / month</strong></p>';
                    echo '<button>View Details</button>';
                    echo '</div>';
                }
                
                sqlsrv_free_stmt($stmt);
                sqlsrv_close($conn);
                
            } catch (Exception $e) {
                echo '<p>Error loading properties. Please try again later.</p>';
                // For debugging: echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            }
            ?>
        </div>
    </div>
</body>
</html>
