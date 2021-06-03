<?php
    namespace Controllers;

    use DAO\MovieTheaterDAO as MovieTheaterDAO;
    use Models\MovieTheater as MovieTheater;
    use DAO\CinemaDAO as CinemaDAO;
    use Models\Cinema as Cinema;
    use Models\Show as Show;

    class MovieTheaterController
    {
        private $movieTheaterDAO;
        private $cinemaDAO;

        public function __construct()
        {
            $this->movieTheaterDAO = new MovieTheaterDAO();
            $this->cinemaDAO = new CinemaDAO();
        }

        public function ShowMovieTheaterView()
        {
            $idCinema = $_GET['id'];
            $cinemaSearch = $this->cinemaDAO->GetCinemaById($idCinema);

            // hago esto para que el usuario no pueda modificar el cine estando dado de baja (ya que podria ingresar la url para entrar a la sala)
            if($cinemaSearch->getState()){ 

                $movieTheaterList = $this->movieTheaterDAO->GetMovieTheatersByIdCinema($idCinema);

                if(!is_array($movieTheaterList))
                        $movieTheaterList = array($movieTheaterList);

                require_once(VIEWS_PATH."movie-theater.php");
            }
            else
            {
                $listCinema = $this->cinemaDAO->GetAll();

                if(!$listCinema)
                {
                    $message = 'No se encuentran cines registrados';
                }

                require_once(VIEWS_PATH."cinema-list.php");
            }
        }

        public function ShowMovieTheaterViewAbm($message = "", $id_cinema)
        {
            $cinemaSearch = $this->cinemaDAO->GetCinemaById($id_cinema);            
            $movieTheaterList = $this->movieTheaterDAO->GetMovieTheatersByIdCinema($id_cinema);

            if(!is_array($movieTheaterList))
                    $movieTheaterList = array($movieTheaterList);

            require_once(VIEWS_PATH."movie-theater.php");
        }

        public function AddMovieTheater($name, $total_capacity, $price, $id_cinema)
        {
            $movieTheater = new MovieTheater();

            $movieTheater->setState(true);
            $movieTheater->setName($name);
            $movieTheater->setCurrentCapacity($total_capacity);
            $movieTheater->setPrice($price);
            $movieTheater->setTotalCapacity($total_capacity);

            $cinema = new Cinema();
            $cinema = $this->cinemaDAO->GetCinemaById($id_cinema);
            $movieTheater->setCinema($cinema);

            //$cinema->setIdCinema($id_cinema);
            //$movieTheater->setCinema($cinema);

            if($this->MovieTheaterExists($movieTheater, $id_cinema)){ // se verifica si el nombre ya existe en el cine
                $message = "El nombre de la sala que se quiere agregar ya esta en uso";
            }else{

                if($this->movieTheaterDAO->Add($movieTheater)){

                    $message = "La sala se ha agregado correctamente !!!";
                }
                else
                {
                    $message = "Hubo un error al agregar la sala";
                }
            }

            $this->ShowMovieTheaterViewAbm($message, $id_cinema);
        }

        public function RemoveMovieTheater($idMovieTheater)
        {
            $movieTheaterSearch = $this->movieTheaterDAO->GetMovieTheaterById($idMovieTheater);

            $this->movieTheaterDAO->Remove($idMovieTheater);

            $this->ShowMovieTheaterViewAbm($message = "Se ha eliminado correctamente la sala",
                $movieTheaterSearch->getCinema()->getIdCinema());
        }

        public function EnableMovieTheater($idMovieTheater)
        {
            $movieTheaterSearch = $this->movieTheaterDAO->GetMovieTheaterById($idMovieTheater);

            $this->movieTheaterDAO->Enable($idMovieTheater);

            $this->ShowMovieTheaterViewAbm($message = "Se ha dado de alta correctamente la sala",
                $movieTheaterSearch->getCinema()->getIdCinema());
        }

        public function ShowUpdateMovieTheaterView()
        {
            $idMovieTheater = $_GET['id'];
            $movieTheaterSearch = $this->movieTheaterDAO->GetMovieTheaterById($idMovieTheater);

            if($movieTheaterSearch){
                require_once(VIEWS_PATH."update-movie-theater.php");
            }else{
                $message = 'La sala que busca no se encuentra registrada, intente de nuevo';

                $this->ShowMovieTheaterViewAbm($message, $movieTheaterSearch->getCinema()->getIdCinema());
            }
        }

        public function UpdateMovieTheater($id_movie_theater, $name, $total_capacity, $price, $id_cinema)
        {
            $movieTheater = new MovieTheater();

            $movieTheater->setIdMovieTheater((int)$id_movie_theater);
            $movieTheater->setState(true);
            $movieTheater->setName($name);
            $movieTheater->setCurrentCapacity($total_capacity);
            $movieTheater->setPrice($price);
            $movieTheater->setTotalCapacity($total_capacity);

            $cinema = new Cinema();
            $cinema->setIdCinema($id_cinema);
            $movieTheater->setCinema($cinema);

            if($this->MovieTheaterExistsUpdate($movieTheater, $id_cinema)){
                $message = "Los datos de la sala a agregar ya existen en este cine !";
            }else{
                $rowCount = $this->movieTheaterDAO->Update($movieTheater);
                if($rowCount > 0)
                {
                    $message = "La sala se ha editado correctamente !!!";
                }
                else
                {
                    $message = "La sala no se ha editado correctamente";
                }
            }
            $this->ShowMovieTheaterViewAbm($message,$id_cinema);
        }

        public function MovieTheaterExists($movieTheaterToSearch, $id_cinema)
        {
            $movieTheaterList = $this->movieTheaterDAO->GetMovieTheatersByIdCinema($id_cinema);
             if(!is_array($movieTheaterList))
                    $movieTheaterList = array($movieTheaterList);

            foreach($movieTheaterList as $movieTheater){
                if($movieTheater->getName() == $movieTheaterToSearch->getName()){
                    return 1;
                }
            }
            return 0;
        }

        public function MovieTheaterExistsUpdate($movieTheaterToSearch, $id_cinema)
        {
            $movieTheaterList = $this->movieTheaterDAO->GetMovieTheatersByIdCinema($id_cinema);
             if(!is_array($movieTheaterList))
                    $movieTheaterList = array($movieTheaterList);

            foreach($movieTheaterList as $movieTheater){
                if($movieTheater->getName() == $movieTheaterToSearch->getName() && $movieTheater->getIdMovieTheater() != $movieTheaterToSearch->getIdMovieTheater()){
                    return 1;
                }
            }
            return 0;
        }

        public function calculateTotalCapacity()
        {
            
        }

        public function getMovieTheaterByShowId($id_show) 
        {
			$show = new Show();
			$show->setIdShow($id_show);
			return $this->movieTheaterDAO->getByIdShow($show);
		}

    }

?>
