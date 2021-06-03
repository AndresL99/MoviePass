<?php
    namespace Controllers;

    class HomeController
    {
        public function Index($message = "")
        {
            require_once(VIEWS_PATH."login.php");
        }         
        
        public function UserView($message = "")
        {
        	require_once(VIEWS_PATH."movieList.php");
        }

        public function login($message = "")
        {
            require_once(VIEWS_PATH."login.php");
        }

        public function logout($message = "")
        {
            unset($_SESSION['loggedUser']);
            session_destroy();
            require_once(VIEWS_PATH."login.php");
        }

        public function register($message = "")
        {
            require_once(VIEWS_PATH."register.php");
        }

        public function listCineView($message = "")
        {
            require_once(VIEWS_PATH."cinema-list.php");
        }

        public function addCineView($message = "")
        {
            require_once(VIEWS_PATH."cinema-add.php");
        } 
    }
?>