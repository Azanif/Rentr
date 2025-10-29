<?php
// Start the session
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to dashboard if already logged in
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rentr.com - Find Your Perfect Rental Home</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Add any additional styles specific to index.php here */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        
        /* Hero Section Styling */
        .hero {
            background: linear-gradient(rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.6)), 
                        url('images/rumah.jpg') 
                        no-repeat center center/cover;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #333;
            text-align: center;
            padding: 0 20px;
            position: relative;
        }
        
        .hero-nav {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 20px;
        }
        
        .nav-links {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .nav-links a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            padding: 8px 15px;
            border-radius: 20px;
            position: relative;
        }
        
        .nav-links a:focus {
            outline: 3px solid #ffbf47;
            outline-offset: 2px;
        }
        
        .nav-links a::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: #0066cc;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .nav-links a:hover::after,
        .nav-links a:focus::after {
            transform: scaleX(1);
        }
        
        .hero-nav a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        
        .auth-buttons {
            margin-left: auto;
            display: flex;
            gap: 10px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #0066cc;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            transition: all 0.3s;
            border: 2px solid #0066cc;
            cursor: pointer;
            font-size: 1rem;
        }
        
        .btn:focus {
            outline: 3px solid #ffbf47;
            outline-offset: 2px;
        }
        
        .btn:hover {
            background-color: #004d99;
        }
        
        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .search-bar {
            margin: 15px 0;
            width: 100%;
            max-width: 600px;
            margin: 15px auto 30px;
        }
        
        .search-form {
            display: flex;
            width: 100%;
        }
        
        .search-input {
            flex: 1;
            padding: 12px 20px;
            border: 2px solid #0066cc;
            border-radius: 30px 0 0 30px;
            font-size: 1rem;
            outline: none;
            background-color: rgba(255, 255, 255, 0.9);
        }
        
        .search-btn {
            background-color: #0066cc;
            color: white;
            border: none;
            padding: 0 20px;
            border-radius: 0 30px 30px 0;
            cursor: pointer;
            font-size: 1.1rem;
            transition: all 0.3s;
        }
        
        .search-btn:hover {
            background-color: #004d99;
        }
        
        .features, .about-us, .contact-section {
            padding: 80px 20px;
            text-align: center;
        }
        
        .about-content, .contact-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            justify-content: center;
            text-align: left;
        }
        
        .about-text, .contact-info, .contact-form {
            flex: 1;
            min-width: 300px;
        }
        
        .contact-form {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        .social-links {
            margin-top: 20px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .social-links a {
            color: #0066cc;
            text-decoration: none;
            padding: 5px 0;
            transition: color 0.3s;
        }
        
        .social-links a:hover {
            color: #004d99;
            text-decoration: underline;
        }
        
        .features h2 {
            margin-bottom: 40px;
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .feature-card {
            padding: 30px;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-card i {
            font-size: 2.5rem;
            color: #0066cc;
            margin-bottom: 20px;
        }
    </style>
    <script>
        function smoothScroll(targetId) {
            const element = document.getElementById(targetId);
            if (element) {
                window.scrollTo({
                    top: element.offsetTop - 80, // Adjust for fixed header
                    behavior: 'smooth'
                });
            }
        }
    </script>
</head>
<body>
    <!-- Hero Section with Navigation -->
    <section class="hero">
        <!-- Navigation in Hero -->
        <nav class="hero-nav" aria-label="Main navigation">
            <ul class="nav-links">
                <li><a href="index.php" aria-label="Go to homepage">Home</a></li>
            </ul>
            <div class="auth-buttons">
                <a href="login.php" class="btn" aria-label="Login to your account">Login</a>
                <a href="register.php" class="btn" aria-label="Sign up for a new account" style="background-color: #1a73e8; border-color: #1a73e8; margin-left: 10px;">Sign Up</a>
            </div>
        </nav>
        
        <!-- Hero Content -->
        <div class="hero-content">
            <h1>Rentr.</h1>
            <p>Welcome to a better way to rent</p>
            <div class="search-bar">
                <form action="properties.php" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Search for properties..." class="search-input">
                    <button type="submit" class="search-btn">
                        Search
                    </button>
                </form>
            </div>
        </div>
    </section>
</body>
</html>
</body>
</html>
