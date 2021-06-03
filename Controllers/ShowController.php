<?php
    namespace Controllers;

    use DAO\ShowDAO as ShowDAO;
    use Models\Show as Show;
    use DAO\MovieTheaterDAO as MovieTheaterDAO;
    use Models\MovieTheater as MovieTheater;
    use DAO\CinemaDAO as CinemaDAO;
    use Models\Cinema as Cinema;
    use DAO\MovieDAO as MovieDAO;
    use Models\Movie as Movie;
    use DAO\GenreDAO as GenreDAO;
    use Models\Genre as Genre;

    class ShowController
    {
        private $showDAO;
        private $movieTheaterDAO;
        private $cinemaDAO;
        private $movieDAO;
        private $genreDAO;

        public function __construct()
        {
            $this->showDAO = new ShowDAO();
            $this->cinemaDAO = new CinemaDAO();
            $this->movieTheaterDAO = new MovieTheaterDAO();
            $this->movieDAO = new MovieDAO();
            $this->genreDAO = new GenreDAO();
        }

        public function DisplayShowView($movieTheaterName, $idCinema)
        {
            $movieTheaterSearch = $this->movieTheaterDAO->GetMovieTheaterByName($movieTheaterName);

            if($movieTheaterSearch && $movieTheaterSearch->getState())
            {
                if($this->movieTheaterDAO->IsMovieTheaterInCinema($movieTheaterSearch, $idCinema))
                {
                  $showsOfMovieTheater = $this->showDAO->GetAllShowsByMovieTheater($movieTheaterSearch);

                  //aqui seteo la funcion por completo
                  $showsOfMovieTheater = $this->SetCompleteShows($showsOfMovieTheater);

                  //$movieList = $this->movieDAO->GetAll();
                  $movieList = $this->movieDAO->GetAllActive();
                  if(!$movieList)
                  {
                    $message = 'No hay peliculas activas, dirijase a la lista de peliculas para activar las que desee';
                  }
                  require_once(VIEWS_PATH."show-view.php");
                }
                else
                {
                  $cinemaSearch = $this->cinemaDAO->GetCinemaById($idCinema);
                  $movieTheaterList = $this->movieTheaterDAO->GetMovieTheatersByIdCinema($idCinema);

                  $message = 'La sala que busca no se encuentra en este cine';

                  if(!is_array($movieTheaterList))
                          $movieTheaterList = array($movieTheaterList);

                  require_once(VIEWS_PATH."movie-theater.php");
                }
            }
            else
            {
                $cinemaSearch = $this->cinemaDAO->GetCinemaById($idCinema);
                $movieTheaterList = $this->movieTheaterDAO->GetMovieTheatersByIdCinema($idCinema);
                $message = 'El nombre de la sala es incorrecto o la sala buscada esta dada de baja';

                if(!is_array($movieTheaterList))
                        $movieTheaterList = array($movieTheaterList);

                require_once(VIEWS_PATH."movie-theater.php");
            }

        }

        public function DisplayShowViewAfterAction($idMovieTheater, $showsOfMovieTheater, $message = "")
        {
            $movieTheaterSearch = $this->movieTheaterDAO->GetMovieTheaterById($idMovieTheater);

            $movieList = $this->movieDAO->GetAllActive();
            require_once(VIEWS_PATH."show-view.php");
        }

        /*public function GetAllActiveShows()
        {
            $showsActive = $this->showDAO->GetAllActive();
            $showsActive = $this->SetCompleteShows($showsActive);

            require_once(VIEWS_PATH."intro-moviepass.php");
        }*/

        public function AddShow($idMovie, $date, $hour, $idMovieTheater)
        {
            $movieSearch = $this->movieDAO->GetMovieById($idMovie);
            $movieTheaterSearch = $this->movieTheaterDAO->GetMovieTheaterById($idMovieTheater);

            if($movieSearch)
            {
                if($movieTheaterSearch)
                {
                    $newShow = new Show();
                    $newShow->setState(true);
                    $newShow->setMovie($movieSearch);
                    $newShow->setDay($date);

                    $hour = strtotime($hour);
                    $hour = date('H:i', $hour);
                    $newShow->setHour($hour);

                    $newShow->setMovieTheater($movieTheaterSearch);

                    //die();

                    //$movieInUse = $this->MovieIsUseInOtherMovieTheater($newShow);
                    if(!$this->MovieIsUseInOtherMovieTheater($newShow))
                    {
                      if($this->CalculateTime($newShow))
                      {
                        $this->showDAO->Add($newShow);
                        $message = 'Se ha creado una nueva funcion correctamente';
                      }
                      else {
                        $message = "Superposicion de horarios entre funciones al intentar crear una para el dia: $date";
                      }
                    }
                    else
                    {
                        $message = "La pelicula '" . $movieSearch->getTitle() . "' ya se encuentra dada de alta en una sala para el dia: $date";
                    }
                }
                else
                {
                    $message = 'La sala seleccionada no ha sido encontrada';
                }
            }
            else
            {
                $message = 'La pelicula selecionada no ha sido encontrada';
            }
            $this->GetAllShowsByIdMovieTheater($idMovieTheater, $message);
        }

        public function GetAllShowsByIdMovieTheater($idMovieTheater, $message = "")
        {
            $movieTheaterSearch = $this->movieTheaterDAO->GetMovieTheaterById($idMovieTheater);
            $showsOfMovieTheater = array();

            if($movieTheaterSearch)
            {
                $showsOfMovieTheater = $this->showDAO->GetAllShowsByMovieTheater($movieTheaterSearch);
                $showsOfMovieTheater = $this->SetCompleteShows($showsOfMovieTheater);
            }
            else
            {
                $message = "hubo un error al buscar las funciones";
            }

            $this->DisplayShowViewAfterAction($idMovieTheater, $showsOfMovieTheater, $message);
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

        //verifico que la pelicula de la funcion solo se encuentre en una sala de un cine por dia
        public function MovieIsUseInOtherMovieTheater(Show $newShow)
        {
            $movieInUse = 0; // la peli NO se esta utilizando
            // NO BORRAR, dejar por si falla el findShowByDayAndMovie
            /*$allShows = $this->showDAO->GetAll();
            $allShows = $this->SetCompleteShows($allShows);

            foreach ($allShows as $show)
            {
                if($show->getMovie()->getIdMovie() == $newShow->getMovie()->getIdMovie() && $show->getDay() == $newShow->getDay())
                {
                    $flag = 0;
                }
            }

            return $flag;*/

            $showSearch = $this->showDAO->findShowByDayAndMovie($newShow);
            if($showSearch)
            {
                //$showSearch = $this->SetCompleteShows($showSearch);
                $movieInUse = 1; // la peli se esta utilizando
            }

            return $movieInUse;
        }

        public function ReturnShowIfMovieIsUseInOtherMovieTheater(Show $newShow)
        {
          $showSearch = $this->showDAO->findShowByDayAndMovie($newShow);
          if($showSearch)
          {
              $showSearch = $this->SetCompleteShows($showSearch);
          }

          return $showSearch;
        }

        public function CalculateTime(Show $newShow)
        {
            $shows = $this->showDAO->GetAllShowsActiveByMovieTheaterAndDayOrderByHour($newShow);
            $this->SetCompleteShows($shows);

            /*PELICULA NUEVAAAAAAAAAAA*/
            $newShowStartHour = strtotime($newShow->getHour());
            $newShowStartHour = date ('H:i', $newShowStartHour);

            $newShowEndTime = strtotime('+'. $newShow->getMovie()->getRuntime() .' minute', strtotime($newShowStartHour));
            $newShowEndTime = date ('H:i', $newShowEndTime);

            $newShowEndTime = strtotime('+15 minute', strtotime($newShowEndTime));
            $newShowEndTime = date ('H:i', $newShowEndTime);
            /*PELICULA NUEVAAAAAAAAAA*/

            if(count($shows) == 0)
            {
              return 1;
            }
            elseif (count($shows) == 1) {
                $showStartHour = strtotime($shows[0]->getHour());
                $showStartHour = date ('H:i', $showStartHour);

                $showEndTime = strtotime('+'. $shows[0]->getMovie()->getRuntime() .' minute', strtotime($showStartHour));
                $showEndTime = date ('H:i', $showEndTime);

                $showEndTime = strtotime('+15 minute', strtotime($showEndTime));
                $showEndTime = date ('H:i', $showEndTime);

                //echo "<br>El horario de comienzo de la nueva pelicula $newShowStartHour debera ser mayor o igual al horario de fin $showEndTime
                //O el horario de fin de la nueva pelicula $newShowEndTime debera ser menor o igual al horario de inicio $showStartHour";

                if($newShowStartHour >= $showEndTime || $newShowEndTime <= $showStartHour)
                {
                  return 1;
                }
                else
                {
                  return 0;
                }
            }
            elseif (count($shows) > 1) {
              $showStartHour = strtotime($shows[0]->getHour());
              $showStartHour = date ('H:i', $showStartHour);

              $showEndTime = strtotime('+'. $shows[0]->getMovie()->getRuntime() .' minute', strtotime($showStartHour));
              $showEndTime = date ('H:i', $showEndTime);

              $showEndTime = strtotime('+15 minute', strtotime($showEndTime));
              $showEndTime = date ('H:i', $showEndTime);

              // si el horario de fin de la peli nueva es menor al horario de comienzo de la primera pelicula retorna 1
              if ($newShowEndTime <= $showStartHour) {
                  return 1;
              }
              else {
                $flag = 0;
                for ($i=0; $i < count($shows); $i++) {
                  $showStartHour = strtotime($shows[$i]->getHour());
                  $showStartHour = date ('H:i', $showStartHour);

                  $showEndTime = strtotime('+'. $shows[$i]->getMovie()->getRuntime() .' minute', strtotime($showStartHour));
                  $showEndTime = strtotime('+15 minute', $showEndTime);
                  $showEndTime = date ('H:i', $showEndTime);

                  $t = $i+1;
                  if(!empty($shows[$t]))
                  {
                    if($newShowStartHour >= $showEndTime && $newShowEndTime <= $shows[$t]->getHour())
                    {
                      return 1;
                    }
                  }
                  else
                  {
                    /* si es el ultimo show en la lista y el horario de inicio de la nueva pelicula es mayor al horario de
                    fin de la pelicula comparada que se inserte*/
                    if ($newShowStartHour >= $showEndTime) {
                        return 1;
                    }
                  }
                }
                if($flag == 0)
                {
                  return $flag;
                }
              }
            }
        }

        public function DisableShow($idShow)
        {
            $showSearch = $this->showDAO->GetShowById($idShow);
            $this->SetCompleteShows($showSearch);

            $this->showDAO->Disable($showSearch);

            $this->GetAllShowsByIdMovieTheater($showSearch->getMovieTheater()->getIdMovieTheater(), $message = "Se ha dado de baja la funcion correctamente");
        }

        public function EnableShow($idShow)
        {
            //buscar sala y pasarla
            $showSearch = $this->showDAO->GetShowById($idShow);
            $this->SetCompleteShows($showSearch);

            if($showSearch->getDay() >= date("Y-m-d"))
            {
              if($this->CalculateTime($showSearch))
              {
                $this->showDAO->Enable($showSearch);
                $message = "Se ha dado de alta la funcion, si su funcion sigue dada de baja intente activar la pelicula";
              }
              else
              {
                $message = 'Superposicion de horarios al dar de alta la funcion';
              }
            }
            else
            {
                $message = "La pelicula que quiere dar de alta ya expiró, se eliminara en una siguiente actualizacion !";
            }


            $this->GetAllShowsByIdMovieTheater($showSearch->getMovieTheater()->getIdMovieTheater(), $message);
        }


        public function BackToMovieTheaterView($id_cinema)
        {
            $cinemaSearch = $this->cinemaDAO->GetCinemaById($id_cinema);
            $movieTheaterList = $this->movieTheaterDAO->GetMovieTheatersByIdCinema($id_cinema);

            if(!is_array($movieTheaterList))
                    $movieTheaterList = array($movieTheaterList);

            require_once(VIEWS_PATH."movie-theater.php");
        }

        public function UpdateShow($id_show, $day, $hour, $id_movie, $id_movie_theater)
        {
            $show = new Show();
            $show->setIdShow((int)$id_show);
            $show->setState(true);
            $show->setDay($day);
            $show->setHour($hour);

            $movie = new Movie();
            $movie = $this->movieDAO->getMovieById($id_movie);

            $show->setMovie($movie);

            $movieTheater = new MovieTheater();
            $movieTheater = $this->movieTheaterDAO->getMovieTheaterById($id_movie_theater);
            $show->setMovieTheater($movieTheater);


            $movieInUse = $this->MovieIsUseInOtherMovieTheater($show);
            $showSearch = $this->ReturnShowIfMovieIsUseInOtherMovieTheater($show);
            //echo "$movieInUse uno o cero ?<br>";

            if($showSearch)
            {
              //echo "trae el show ?,". $showSearch[0]->getIdShow() ." <br>";
              //echo "trae el show<br>";
              $this->showDAO->Disable($showSearch[0]);
            }

            //se encontro la movie en otro show
            //if($showSearch[0])
            //{
              //si trae la pelicula y el dia es distinto al del show
              if($movieInUse && $showSearch[0]->getDay() != $day){
                  $message = "La pelicula ya esta siendo utilizada por otra funcion para el dia $day!";
              }else{
                if($this->CalculateTime($show)){
                    $rowCount = $this->showDAO->Update($show);
                    if($rowCount > 0)
                    {
                        $message = "El show se ha editado correctamente !!!";
                    }
                    else
                    {
                        $message = "El show no se ha editado correctamente";
                    }
                 }
                 else
                 {
                   $message = 'Superposicion de horarios al editar la funcion';
                 }
              }
            //}
            //else
            //{
            //  $message = 'No se encontro el show a modificar';
            //}
            if($showSearch)
            {
              $this->showDAO->Enable($showSearch[0]);
            }
            $this->GetAllShowsByIdMovieTheater($id_movie_theater, $message);
        }

        public function ShowExists($showToSearch, $id_movie_theater)
        {
            $showList = $this->showDAO->GetAllShowsByMovieTheater($id_movie_theater);
             if(!is_array($showList))
                    $showList = array($showList);

            foreach($showList as $show){
                if($show->getName() == $showToSearch->getName()){
                    return 1;
                }
            }
            return 0;
        }

        public function ShowExistsUpdate($showToSearch, $id_movie_theater)
        {
            $showList = $this->showDAO->GetAllShowsByMovieTheater($id_movie_theater);
             if(!is_array($showList))
                    $showList = array($showList);

            foreach($showList as $show){
                if($show->getIdShow() == $showToSearch->getName() && $show->getIdShow() != $showToSearch->getIdShow()){
                    return 1;
                }
            }
            return 0;
        }

        public function ShowUpdateShowsView()
        {
            $idShow = $_GET['id'];
            $showSearch = $this->showDAO->GetShowById($idShow);
            $this->SetCompleteShows($showSearch);

            if($showSearch){
                require_once(VIEWS_PATH."update-shows.php");
            }else{
                $message = 'El show que busca no se encuentra, intente de nuevo';

                $this->GetAllShowsByIdMovieTheater($idMovieTheater, $message);
            }
        }

        public function GetAllActiveShows()
        {
            $showsActive = $this->showDAO->GetAllActive();
            $showsActive = $this->SetCompleteShows($showsActive);
        }

        public function userView($message = "")
        {
            $showsActive = $this->showDAO->GetAllActiveOrderByName();
            $showsActive = $this->SetCompleteShows($showsActive);
            $genreList = $this->genreDAO->GetAll();
            require_once(VIEWS_PATH."client-view.php");
        }

        public function buyTicketView()
        {
            $id_show = $_GET['id_show'];
            $show = $this->showDAO->getShowById($id_show);
            $show = $this->SetCompleteShows($show);
            $show = $show[0];

            require_once(VIEWS_PATH."purchase-view.php");
        }
    }
?>
