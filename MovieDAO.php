<?php

    require "Movie.php";

    class MovieDAO {
        public function getConnection() {
            $conn = new mysqli("127.0.0.1", "NEWELLWEBSERVER_USER", "Abcd1234", "NetNewellDB");
            if ($conn->connect_error) {
                die('Connection error: ' . $conn->connect_error);
            }
            return $conn;
        }

        public function getMovieData($id) {
            $connection = $this->getConnection();    
            $stmt = $connection->prepare("SELECT * FROM MOVIES WHERE id = ?;");
        
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $movie = new Movie();
                $movie->load($row);
            }
        
            $stmt->close();
            $connection->close();
            return $movie;
        }
        
        

        public function getMovies() {
            
            $movies = [];
            $connection = $this->getConnection();

            if (!$connection) return $movies;
            $stmt = $connection->prepare("SELECT * FROM MOVIES;");

            if (!$stmt) {
                error_log("Statement preparation failed: " . $connection->error);
                return $movies;
            }

            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $movie = new Movie();
                $movie->load($row);
                $movies[] = $movie;
            }
            $stmt->close();
            $connection->close();
            return $movies;
        }

    }