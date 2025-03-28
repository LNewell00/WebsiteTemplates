<?php

    require_once 'User.php';

    class UserDAO {

        public function getConnection() {
            $conn = new mysqli("127.0.0.1", "NEWELLWEBSERVER_USER", "Abcd1234", "NetNewellDB");
            if ($conn->connect_error) {
                die('Connection error: ' . $conn->connect_error);
            }
            return $conn;
        }

        public function insertUser($user) {
            $conn = $this->getConnection();
            if(!$this->checkForUser($user)) {
                $stmt = $conn->prepare("INSERT INTO USERS (username, password, email) VALUES (?, ?, ?)");
                if (!$stmt) {
                    throw new Exception("Failed to prepare statement: " . $conn->error);
                }
                $stmt->bind_param("sss", $user->getUsername(), $user->getPassword(), $user->getEmail());
                if (!$stmt->execute()) {
                    throw new Exception("Failed to execute statement: " . $stmt->error);
                }
                $stmt->close();
                $conn->close();

                return true;
            }else{
                return false;
            }
        }

        public function getUsers() {
            $users = [];
            $connection = $this->getConnection();
            if (!$connection) return $users;

            $stmt = $connection->prepare("SELECT * FROM USERS;");
            if (!$stmt) {
                error_log("Statement preparation failed: " . $connection->error);
                return $users;
            }

            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $user = new User();
                $user->load($row);
                $users[] = $user;
            }
            $stmt->close();
            $connection->close();
            return $users;
        }

        public function checkForUser($user) {
            $conn = $this->getConnection();
            $stmt = $conn->prepare("SELECT email FROM USERS WHERE email = ?");
            
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }
        
            $stmt->bind_param("s", $user->getEmail());
            if (!$stmt->execute()) {
                throw new Exception("Failed to execute statement: " . $stmt->error);
            }
        
            $result = $stmt->get_result();
            $userExists = $result->num_rows > 0;
        
            $stmt->close();
            $conn->close();
        
            return $userExists;
        }

        public function authenticate($email, $password) {
            $conn = $this->getConnection();
            
            $stmt = $conn->prepare("SELECT password FROM USERS WHERE email = ?;");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc(); // Get the user row
            $stmt->close();
            $conn->close();
        
            if ($row) {
                if (password_verify($password, $row['password'])) {
                    return true;
                }
            }
        
            return false;
        }
        
        
    }

?>