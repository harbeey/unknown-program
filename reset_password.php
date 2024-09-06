<?php
session_start();
include 'db_config.php'; // Include your database configuration file

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Get database connection
        $conn = getDbConnection();

        // Check if the token is valid and not expired
        $sql = "SELECT * FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Token is valid, update the password
            $sql = "UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            }
            $stmt->bind_param("ss", $new_password, $token);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $success = "Your password has been reset successfully.";
            } else {
                $error = "Failed to reset your password. Please try again.";
            }
        } else {
            $error = "Invalid or expired token.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
        
        .container input[type="password"],
        .container input[type="submit"],
        .container button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .container input[type="password"]:focus,
        .container input[type="submit"]:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 8px rgba(106, 17, 203, 0.3);
            outline: none;
        }
        
        .container input[type="submit"],
        .container button {
            background-color: #6a11cb;
            border: none;
            font-size: 16px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .container input[type="submit"]:hover,
        .container button:hover {
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

            .container input[type="submit"],
            .container button {
                font-size: 14px;
                padding: 8px;
            }

            .container input[type="password"] {
                font-size: 14px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fa-solid fa-lock"> Reset Password</i></h2>
        <?php if (!empty($error)) echo "<p>$error</p>"; ?>
        <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
        <form action="" method="post">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
            <input type="password" name="new_password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <input type="submit" value="Reset Password">
        </form>
        <button onclick="window.location.href='login.php'">Back to Login</button>
    </div>
    <script src="https://kit.fontawesome.com/a8759ef171.js" crossorigin="anonymous"></script>
</body>
</html>