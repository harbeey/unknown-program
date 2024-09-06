<?php
session_start();
include 'db_config.php'; // Include your database configuration file

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];

    // Get database connection
    $conn = getDbConnection();

    // Check if the username exists in the database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists, generate a unique token
        $token = bin2hex(random_bytes(16)); // Generates a 32-character (16 bytes) random string
        $sql = "UPDATE users SET reset_token = ?, reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE username = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }
        $stmt->bind_param("ss", $token, $username);
        $stmt->execute();

        // Simulate sending email
        $reset_link = "http://localhost/poseidon/Quiz App/reset_password.php?token=" . $token;
        // Normally you would send an email with this link
        $success = "Password reset link has been generated. <a href='$reset_link'>Reset Password</a>";

    } else {
        $error = "No account found with that username.";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(45deg, #0000ff, #4b0082, #8a2be2, #9400d3, #9932cc);
            background-size: 200% 200%;
            animation: gradientAnimation 15s ease infinite;
        }

        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
            animation: fadeIn 1s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        
        .container input[type="text"],
        .container input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .container input[type="text"]:focus,
        .container input[type="submit"]:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 8px rgba(106, 17, 203, 0.3);
            outline: none;
        }
        
        .container input[type="submit"] {
            background-color: #6a11cb;
            border: none;
            font-size: 16px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .container input[type="submit"]:hover {
            background-color: #2575fc;
            transform: scale(1.05);
        }

        .container p {
            color: red;
        }

        .container .success {
            color: green;
        }

        /* Media queries for responsiveness */
        @media (max-width: 600px) {
            .container {
                padding: 20px;
                width: 90%;
            }

            .container h2 {
                font-size: 20px;
            }

            .container input[type="submit"] {
                font-size: 14px;
                padding: 8px;
            }

            .container input[type="text"] {
                font-size: 14px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fa-solid fa-person-circle-question"> Forgot Password</i></h2>
        <?php if (!empty($error)) echo "<p>$error</p>"; ?>
        <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
        <form action="" method="post">
            <input type="text" name="username" placeholder="Enter your username" required>
            <input type="submit" value="Send Reset Link">
        </form>
    </div>
    <script src="https://kit.fontawesome.com/a8759ef171.js" crossorigin="anonymous"></script>
</body>
</html>
