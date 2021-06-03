<?php

    namespace Models;

    class Genre
    {
        
        private $idGenre;
        private $name;

        public function __construct()
        {

        }

        public function getIDGenre()
        {
            return $this->idGenre;
        }

        public function setIDGenre($idGenre)
        {
            $this->idGenre = $idGenre;
        }

        public function getName()
        {
            return $this->name;
        }

        public function setName($name)
        {
            $this->name = $name;
        }
    }

?>