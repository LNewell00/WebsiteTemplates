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

        public function checkForUser($user) {
            $conn = $this->getConnection();
            $stmt = $conn->prepare("SELECT email FROM USERS WHERE email = ?");
            
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }
        
            $stmt->bind_param("s", $user->getEmail()); // Bind the email value to the placeholder
            if (!$stmt->execute()) {
                throw new Exception("Failed to execute statement: " . $stmt->error);
            }
        
            $result = $stmt->get_result(); // Get the result set from the query
            $userExists = $result->num_rows > 0; // Check if any rows were returned (user exists)
        
            $stmt->close();
            $conn->close();
        
            return $userExists; // Return true if user exists, false otherwise
        }
        
    }

?>