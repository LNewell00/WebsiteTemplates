<?php

    require "model/UserDAO.php";

    $userDAO = new UserDAO();

    $method = $_SERVER['REQUEST_METHOD'];
    if ($method === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmationPass = $_POST['confirmPassword'];
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        
        if($password !== $confirmationPass) {
            $errorMessage = "Passwords do not match";
        }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = "Email Invalid";
        }else{
            $user = new User();
            $user->setUsername($username);
            $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
            $user->setEmail($email);

            if(($userDAO->insertUser($user)) == true) {
                header("Location: index.html");
                exit();
            }else{
                $errorMessage = "Email already exists";
            }
        }

        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="StyleSheets/indexStyleSheet.css" rel="stylesheet">
    <title>Signup Page</title>
</head>
<body>
    <form class="form_main" action="signup.php" method="POST">
    <p class="heading">Signup for Free!</p>
        <div id="errorDiv" style="color: red; font-weight: 650;">
            <?php
                if (isset($errorMessage)) { echo $errorMessage; }
            ?>
        </div>

                <!-- Username Input -->
        <div class="inputContainer">
            <input 
                class="inputField" 
                type="text" 
                id="username" 
                name="username" 
                placeholder="Enter Username" 
                required 
                minlength="2" 
                maxlength="24" 
                pattern="[a-zA-Z0-9_]+" 
                title="Username can only contain letters, numbers, and underscores."
            >
        </div>

        <!-- Email Input -->
        <div class="inputContainer">
            <input 
                class="inputField" 
                type="email" 
                id="email" 
                name="email" 
                placeholder="Enter Email" 
                required 
                title="Please enter a valid email address."
            >
        </div>

        <!-- Password Input -->
        <div class="inputContainer">
            <input 
                class="inputField" 
                type="password" 
                id="password" 
                name="password" 
                placeholder="Enter Password" 
                required 
                minlength="8" 
                pattern="(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}" 
                title="Password must be at least 8 characters and include uppercase, lowercase, a number, and a special character."
            >
        </div>

        <!-- Confirm Password Input -->
        <div class="inputContainer">
            <input 
                class="inputField" 
                type="password" 
                id="confirmPassword" 
                name="confirmPassword" 
                placeholder="Confirm Password" 
                required
            >
        </div>

        <button class="animated-button" style="margin-top: 15px;" id="button">
            <span>Signup!</span>
            <span></span>
        </button>
    </form>
</body>
</html>
