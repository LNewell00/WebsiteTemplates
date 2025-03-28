<?php

    include_once "../model/UserDAO.php";
    include_once "ControllerAction.php";
    include_once "UserControllers.php";

    class FrontController {

        private $controllers;

        public function __construct() {
            $this->controllers = $this->loadControllers();
        }

        public function run() {
            $method = $_SERVER['REQUEST_METHOD'];
            $page = $_REQUEST['page'];

            $controller = $this->controllers[$method.$page];
            if($method=='GET'){
                $controller->processGET();
            }
            if($method=='POST'){
                $controller->processPOST();
            }
        }

        private function loadControllers() {
            $controllers["GET"."login"] = new Login();
            $controllers["POST"."login"] = new Login();
            return $controllers;
        }

    }

    $controllers = new FrontController();
    $controllers->run();

?>