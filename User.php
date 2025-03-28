<?php

    class User {
        private $username;
        private $password;
        private $email;

        public function load($row) {
            $this->username = $row["username"];
            $this->password = $row["password"];
            $this->email = $row["email"];
        }

        public function setUsername($username) {
            $this->username = $username;
        }
        public function getUsername() {
            return $this->username;
        }
        public function setPassword($password) {
            $this->password = $password;
        }
        public function getPassword() {
            return $this->password;
        }
        public function setEmail($email) {
            $this->email = $email;
        }
        public function getEmail() {
            return $this->email;
        }
    }

?>