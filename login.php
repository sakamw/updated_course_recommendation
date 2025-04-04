<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = :email AND is_active = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: dashboard.php');
    } else {
        $error_message = "Invalid email, password, or your account is deactivated!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="user_style.css">
    <style>
        /* Popup Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 5px;
            text-align: center;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }
        
        .forgot-password {
            display: block;
            text-align: right;
            margin-bottom: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="register.php">Register</a></li>
    </ul>
</nav>

<div class="login-container">
    <h2>Login</h2>
    
    <?php if (isset($error_message)) { echo "<p style='color:red;'>$error_message</p>"; } ?>
    
    <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <a href="#" class="forgot-password" id="forgotPassword">Forgot Password?</a>
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>
</div>

<!-- The Modal -->
<div id="passwordModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Password Reset</h3>
        <p>Please contact your administrator at:</p>
        <p><strong>kenife@gmail.com</strong></p>
    </div>
</div>

<footer>
    &copy; 2025 Course Recommendation System | Inspired by KUCCPS
</footer>

<script>
    // Get the modal
    var modal = document.getElementById("passwordModal");

    // Get the button that opens the modal
    var btn = document.getElementById("forgotPassword");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal 
    btn.onclick = function(event) {
        event.preventDefault();
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
</body>
</html>