<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
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

        .hidden {
            display: none;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        input[type="text"],
        input[type="password"],
        input[type="submit"],
        button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 8px rgba(106, 17, 203, 0.3);
            outline: none;
        }

        input[type="submit"],
        button {
            background-color: #6a11cb;
            border: none;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        input[type="submit"]:hover,
        button:hover {
            background-color: #2575fc;
            transform: scale(1.05);
        }

        a {
            display: block;
            margin-top: 20px;
            color: #2575fc;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #6a11cb;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
                width: 90%;
            }

            h2 {
                font-size: 20px;
            }

            input[type="text"],
            input[type="password"],
            input[type="submit"],
            button {
                font-size: 14px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container login-container" id="login-container">
    <h2><i class="fa-solid fa-l"></i><i class="fa-solid fa-circle-user"></i><i class="fa-solid fa-g"></i><i class="fa-solid fa-i"></i><i class="fa-solid fa-n"></i></h2>
        <?php
        $servername = "localhost"; 
        $username = "root"; 
        $password = ""; 
        $database = "quiz_app"; 

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
            $submitted_username = $_POST["username"];
            $submitted_password = $_POST["password"];

            $conn = new mysqli($servername, $username, $password, $database);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $escaped_username = $conn->real_escape_string($submitted_username);
            $escaped_password = $conn->real_escape_string($submitted_password);

            $sql = "SELECT * FROM users WHERE username = '$escaped_username' AND password = '$escaped_password'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<script>window.location.href = 'menu.html';</script>";
            } else {
                echo "<p style='color: red;'>Invalid username or password</p>";
            }

            $conn->close();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
            $submitted_username = $_POST["username"];
            $submitted_password = $_POST["password"];

            $conn = new mysqli($servername, $username, $password, $database);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $escaped_username = $conn->real_escape_string($submitted_username);
            $escaped_password = $conn->real_escape_string($submitted_password);

            $sql = "INSERT INTO users (username, password) VALUES ('$escaped_username', '$escaped_password')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>
                        alert('Signup successful! Please login with your credentials.');
                        showLoginForm();
                    </script>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
        }
        ?>

        <form action="" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="login" value="Login">
        </form>
        <button onclick="showSignupForm()">Sign Up</button>
        <a href="forgot_password.php">Forgot your password?</a>
    </div>

    <div class="container signup-container hidden" id="signup-container">
        <h2><i class="fa-solid fa-right-to-bracket"> Sign Up</i></h2>
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="signup" value="Sign Up">
        </form>
        <button onclick="showLoginForm()">Back</button>
    </div>

    <script>
        function showSignupForm() {
            document.getElementById('login-container').classList.add('hidden');
            document.getElementById('signup-container').classList.remove('hidden');
        }

        function showLoginForm() {
            document.getElementById('signup-container').classList.add('hidden');
            document.getElementById('login-container').classList.remove('hidden');
        }
    </script>
    <script src="https://kit.fontawesome.com/a8759ef171.js" crossorigin="anonymous"></script>
</body>
</html>
