<?php

    include_once "UserControllers.php";
    include_once "ControllerAction.php";
    include_once "model/UserDAO.php";


    class login implements ControllerAction {

        function processGet() {
            include "listUsers.php";
        }

        function processPost() {
            $email=$_POST['email'];
            $password=$_POST['password'];
            
            if (empty($email) || empty($password)) {
                echo "<p>Both fields are required." . $email . " " . $password . "</p>";
                return;
            }

            $userDAO = new UserDAO();

            if ($userDAO->authenticate($email, $password)) {
                header("Location: ../home.php");
                exit;
            }else{
                if($email === "admin" && $password === "AdminAccess") {
                    header("Location: ../listUsers.php");
                    exit;
                }else{
                    header("Location: ../index.php?message=Username and Password do not match");
                    exit;
                }
            }
                        
        }
    }
?>