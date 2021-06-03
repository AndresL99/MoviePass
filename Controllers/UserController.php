<?php

namespace Controllers;

use DAO\UserDAOJson as UserDAOJson;
use \Exception as Exception;
use DAO\UserDAO as UserDAO;
use DAO\RolesDAO as RolesDAO;
use DAO\CinemaDAO as CinemaDAO;
use DAO\MovieTheaterDAO as MovieTheaterDAO;
use DAO\MovieDAO as MovieDAO;
use DAO\ShowDAO as ShowDAO;
use Models\User as User;
use Models\Genre as Genre;
use DAO\GenreDAO as GenreDAO;
use Controllers\HomeController as HomeController;

class UserController
{
    //private $userDAO;
    private $userDAO;
    private $roleDAO;
    private $CinemaDAO;
    private $showDAO;
    private $movieDAO;
    private $movieTheaterDAO;
    private $genreDAO;

    public function __construct()
    {
        $this->userDAO = new UserDAO();
        $this->roleDAO = new RolesDAO();
        $this->cinemaDAO = new CinemaDAO();
        $this->showDAO = new ShowDAO();
        $this->movieTheaterDAO = new MovieTheaterDAO();
        $this->movieDAO = new MovieDAO();
        //$this->userDAOJson = new UserDAOJson();
        $this->genreDAO = new GenreDAO();
    }


    public function register($userName, $password, $firstName, $lastName, $email)
    {
        $message = "El usuario se registró con exito!";

         $user = new User();
         $user->setUserName($userName);
         $user->setPassword($password);
         $user->setFirstName($firstName);
         $user->setLastName($lastName);
         $user->setEmail($email);
         $user->setRole(2); // por defecto es un cliente
        if(!$this->userExist($user))
        {
            try
            {
                $this->userDAO->add($user);
                $message = 'Gracias por unirte ' .$user->getUserName();
                $showsActive = $this->showDAO->GetAllActiveOrderByName();
                $showsActive = $this->SetCompleteShows($showsActive);
                require_once(VIEWS_PATH."client-view.php");
            }
            catch(Exception $e)
            {
                $message = $e->getMessage();
            }

        }
        else
        {
            $message = "El usuario o email ha sido registrado previamente.";
            require_once(VIEWS_PATH."register.php");
        }

    }

    public function SetCompleteShows($showsOfMovieTheater)
        {
             if(!is_array($showsOfMovieTheater))
                    $showsOfMovieTheater = array($showsOfMovieTheater);

            foreach ($showsOfMovieTheater as $show)
                {
                    /*mediante el id de la movie que cargue en el mapear busco la movie y la seteo en el
                    show(funcion)*/
                    $show->setMovie($this->movieDAO->GetMovieById($show->getMovie()->getIdMovie()));

                    $hour = strtotime($show->getHour());
                    $hour = date('H:i', $hour);
                    $show->setHour($hour);

                    /*mediante el id de la movieTheater que cargue en el mapear busco la movieTheater y la seteo en el show(funcion)*/
                    $show->setMovieTheater($this->movieTheaterDAO->GetMovieTheaterById($show->GetMovieTheater()->getIdMovieTheater()));

                    //seteo el cine de la sala
                    $show->getMovieTheater()->setCinema($this->cinemaDAO->GetCinemaById($show->getMovieTheater()->getCinema()->getIdCinema()));
                }

            return $showsOfMovieTheater;
        }

    public function loginView($message = "")
    {
        try
        {
            if(isset($_SESSION['loggedUser']))
            {

                if($_SESSION['loggedUser']->getRole() == 1)
                {
                    $showsActive = $this->showDAO->GetAllActiveOrderByName();
                    $showsActive = $this->SetCompleteShows($showsActive);
                    $genreList = $this->genreDAO->GetAll();
                    require_once(VIEWS_PATH."admin-view.php");
                }
                if($_SESSION['loggedUser']->getRole() == 2)
                {
                    $showsActive = $this->showDAO->GetAllActiveOrderByName();
                    $showsActive = $this->SetCompleteShows($showsActive);
                    $genreList = $this->genreDAO->GetAll();
                    require(ROOT.'/Views/client-view.php');
                }
            }
            else
            {
                require_once(VIEWS_PATH."login.php");
            }
        }
        catch(PDOException $ex)
        {
            $message ="Error en el login controller";
        }
    }

    public function setSession($user)
    {
        $_SESSION['loggedUser'] = $user;
    }

    public function login($userName,$password)
    {
        try
			{
				if(!isset($_SESSION['loggedUser']))
				{
					try
					{
						$search = $this->userDAO->getUserByName($userName);
					}
					catch (Exception $e)
					{
						$message = "Error al buscar datos del usuario.";
					}
					if ($search !=null)
					{
						if ($password == $search->getPassword())
						{
                            $this->setSession($search);
                            $message = 'Bienvenido '.$userName.'!';
                            $this->loginView($message);
                        }

					    else
					    {
						    $message = "Usuario y/o contraseña incorrectos!";
                            $this->loginView($message);
					    }
				    }
				    else
				    {
					    $message="El email ingresado no se encuentra registrado";
                        $this->loginView($message);
				    }

			    }
			    else
			    {
				    $message="Usuario actualmente logueado!";
                    $this->loginView($message);
                }
            }
			catch(PDOException $ex)
			{
				$message="Error al verificar sesion";
            }
    }

    public function logout()
    {
        //session_start();
			if(isset($_SESSION['loggedUser']))
			{
    			unset($_SESSION["loggedUser"]);
    			header("Location:".ROOT_VIEW);
    			$this->loginView();
			}
			else
			{
				$message = "Ningún usuario logueado";
				$this->loginView();
			}
    }

    public function register2($userName, $password, $firstName, $lastName, $email)
    {
        try
        {
            $objectRol = $this->roleDAODB->buscarRol("cliente");
            try
            {
                $search=$this->userDAODB->getUserByName($userName);
            }
            catch(Exception $error)
            {
                $_SESSION['BD']="Error al buscar datos del login en la base de datos.Exception";
            }
            if ($search == null)
            {
                $user = new User();
            }
        }
        catch(Exception $e)
        {

        }
    }

    public function AdminView()
    {
      $showsActive = $this->showDAO->GetAllActiveOrderByName();
      $showsActive = $this->SetCompleteShows($showsActive);
      $genreList = $this->genreDAO->GetAll();
      require_once(VIEWS_PATH."admin-view.php");
    }

    public function FilterShowsByGenre($idGenre)
    {
        $genre = $this->genreDAO->getGenreById($idGenre);
        $showsActive = $this->showDAO->getShowsByIdGenre($genre);
        $showsActive = $this->SetCompleteShows($showsActive);

        $genreList = $this->genreDAO->GetAll();
        require_once(VIEWS_PATH."admin-view.php");
    }

    public function userExist($userToSearch)
        {
            $userList = $this->userDAO->getAll();
            foreach($userList as $user){
                if($user->getUserName() == $userToSearch->getUserName()|| $user->getEmail() == $userToSearch->getEmail()){
                    return 1;
                }
            }
            return 0;
        }
}

?>
