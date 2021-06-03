<?php
	namespace DAO;

    use \Exception as Exception;
    use \PDOException as PDOException;
    use DAO\IShowDAO as IShowDAO;
    use DAO\IDAO as IDAO;
    use Models\Show as Show;
    use Models\MovieTheater as MovieTheater;
    use Models\Movie as Movie;
    use Models\Cinema as Cinema;
    use DAO\Connection as Connection;

    class ShowDAO implements IShowDAO, IDAO
    {
        private $connection;
        private $tableName = "shows";

        public function Add(Show $newShow)
        {
            $query = "INSERT INTO ".$this->tableName." (state, day, hour, id_movie, id_movie_theater) VALUES (:state, :day, :hour, :id_movie, :id_movie_theater);";

            try
            {
                $parameters["state"] = true;
                $parameters["day"] = $newShow->getDay();
                $parameters["hour"] = $newShow->getHour();

                $parameters["id_movie"] = $newShow->getMovie()->getIdMovie();
                $parameters["id_movie_theater"] = $newShow->getMovieTheater()->getIdMovieTheater();

                $this->connection = Connection::GetInstance();

                $rowCount = $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }

            return $rowCount;
        }

        public function GetAll()
        {
            $sql = "SELECT * FROM ". $this->tableName;
            $result = array();

            try {
              $this->connection = Connection::getInstance();
              $resultSet = $this->connection->execute($sql);

              if(!empty($resultSet))
              {
                $result = $this->mapear($resultSet);

                if(!is_array($result))
                    $result = array($result);
              }
            }
            catch(Exception $ex){
               throw $ex;
            }
            return $result;
        }

        public function GetAllActive()
        {
            $sql = "SELECT * FROM " .$this->tableName." WHERE ".$this->tableName.".state = 1";

            $result = array();

            try {
              $this->connection = Connection::getInstance();
              $resultSet = $this->connection->execute($sql);

              if(!empty($resultSet))
              {
                $result = $this->mapear($resultSet);

                if(!is_array($result))
                    $result = array($result);
              }
            }
            catch(Exception $ex){
               throw $ex;
            }
            return $result;
        }

				public function GetAllActiveOrderByName()
        {
            $sql = "SELECT shows.* FROM " .$this->tableName."
						INNER JOIN movies ON movies.id_movie = " .$this->tableName.".id_movie
						WHERE ".$this->tableName.".state = 1 ORDER BY movies.title";

            $result = array();

            try {
              $this->connection = Connection::getInstance();
              $resultSet = $this->connection->execute($sql);

              if(!empty($resultSet))
              {
                $result = $this->mapear($resultSet);

                if(!is_array($result))
                    $result = array($result);
              }
            }
            catch(Exception $ex){
               throw $ex;
            }
            return $result;
        }

        public function GetAllShowsByMovieTheater(MovieTheater $movieTheater)
        {
            $query ="select *
                    from shows
                    where shows.id_movie_theater = :id_movie_theater";
            $result = array();

            try{

                $this->connection = Connection::GetInstance();

                $parameters['id_movie_theater'] = $movieTheater->getIdMovieTheater();

                $resultSet = $this->connection->execute($query, $parameters);

                if(!empty($resultSet))
                {
                    $result = $this->mapear($resultSet);
                }

            }catch(Exception $e) {
                throw $e;
            }

            return $result;
        }

				public function GetAllShowsActiveByMovieTheaterAndDayOrderByHour(Show $show)
        {
            $query ="select shows.*
                    from shows
										inner join movie_theaters mt ON shows.id_movie_theater = :id_movie_theater
                    where shows.state = :state and shows.day = :day
										group by shows.id_show
										order by shows.hour";
            $result = array();

            try{

                $this->connection = Connection::GetInstance();

								$parameters['state'] = TRUE;
								$parameters['day'] = $show->getDay();
								$parameters['id_movie_theater'] = $show->getMovieTheater()->getIdMovieTheater();

                $resultSet = $this->connection->execute($query, $parameters);

                if(!empty($resultSet))
                {
                    $result = $this->mapear($resultSet);

		                if(!is_array($result))
		                    $result = array($result);
                }

            }catch(Exception $e) {
                throw $e;
            }

            return $result;
        }

        //si me devuelve el registro debere elejir otro dia para esa pelicula
        public function findShowByDayAndMovie($newShow)
        {
            $query = "select *
                     from shows
                     where day = :day and id_movie = :id";

            $result = null;

            try{
                $this->connection = Connection::GetInstance();

                $parameters = array();

                $parameters['id'] = intval($newShow->getMovie()->getIdMovie());
                $parameters['day'] = $newShow->getDay();

                $resultSet = $this->connection->execute($query, $parameters);

                if(!empty($resultSet))
                {
                    $result = $this->mapear($resultSet);
                }

            }catch(Exception $e) {
                throw $e;
            }

            return $result;
        }

				public function findShowByDayAndMovieForUpdate($newShow)
        {
            $query = "select *
                     from shows
                     where day = :day and id_movie = :id";

            $result = null;

            try{
                $this->connection = Connection::GetInstance();

                $parameters = array();

                $parameters['id'] = intval($newShow->getMovie()->getIdMovie());
                $parameters['day'] = $newShow->getDay();

                $resultSet = $this->connection->execute($query, $parameters);

                if(!empty($resultSet))
                {
                    $result = $this->mapear($resultSet);
                }

            }catch(Exception $e) {
                throw $e;
            }

            return $result;
        }

        public function Disable(Show $show)
        {
            $query = "UPDATE ".$this->tableName." SET state = :state WHERE id_show = :id_show";

            try
            {
                $parameters["id_show"] = $show->getIdShow();
                $parameters["state"] = false;

                $this->connection = Connection::GetInstance();

                $rowCount = $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }

            return $rowCount;
        }

        public function Enable(Show $show)
        {
            $query = "UPDATE ".$this->tableName." SET state = :state WHERE id_show = :id_show";

            try
            {
                $parameters["id_show"] = $show->getIdShow();
                $parameters["state"] = true;

                $this->connection = Connection::GetInstance();

                $rowCount = $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }

            return $rowCount;
        }

        public function Update(Show $newShow)
        {
            $query = "UPDATE ".$this->tableName." SET state = :state, day = :day, hour = :hour, id_movie = :id_movie, id_movie_theater = :id_movie_theater WHERE id_show = :id_show";
        	try
            {
            	$parameters["id_show"] = $newShow->getIdShow();
                $parameters["state"] = $newShow->getState();
                $parameters["day"] = $newShow->getDay();
                $parameters["hour"] = $newShow->getHour();
                $parameters["id_movie"] = $newShow->getMovie()->getIdMovie();
                $parameters["id_movie_theater"] = $newShow->getMovieTheater()->getIdMovieTheater();

                $this->connection = Connection::GetInstance();

                $rowCount = $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }

            return $rowCount;
        }

        public function GetShowById($idShow)
        {
            $sql = "SELECT * FROM " . $this->tableName . " WHERE id_show = :id_show";
            $result = null;

            try {
                    $parameters["id_show"] = $idShow;

                    $this->connection = Connection::getInstance();
                    $resultSet = $this->connection->Execute($sql,$parameters);

                    if(!empty($resultSet))
                    {
                      $result = $this->mapear($resultSet);
                    }
            }
            catch(Exception $ex){
               throw $ex;
            }

            return $result;
        }

				public function getShowsByIdGenre($genre)
        {
            try
            {
                $result = null;

                $query = "SELECT shows.* FROM shows
                INNER JOIN movies ON shows.id_movie = movies.id_movie
                INNER JOIN movies_x_genres ON movies.id_movie = movies_x_genres.id_movie
                WHERE :id_genre  = movies_x_genres.id_genre AND :show_active = shows.state
                group by shows.id_show;";

                $parameters["id_genre"] = $genre->getIDGenre();
                $parameters["show_active"] = true;

                $this->connection = Connection::getInstance();

                $resultSet= $this->connection->execute($query, $parameters);

                if(!empty($resultSet))
                    {
                      $result = $this->mapear($resultSet);
                      if(!is_array($result))
                        $result = array($result);
                    }
              }
            catch(Exception $ex){
               throw $ex;
            }
            return $result;
        }

        public function getByIdMovieTheater($id_show)
        {
           try {
               $query = "SELECT * FROM shows
               INNER JOIN movie_theaters ON  movie_theaters.id_movie_theater = shows.id_movie_theater 
               WHERE shows.id = :id_show;";
               $parameters ["id_show"] = $id_show;
               $this->connection = Connection::GetInstance();
               $results = $this->connection->Execute($query, $parameters);				
               $movieTheater = new MovieTheater();
               foreach ($results as $row) {					
                   $movieTheater->setTotalCapacity($row["total_capacity"]);
               }
               return $movieTheater;
           }
           catch (Exception $e) {
               return false;
           }
       }

        protected function mapear($value) {
            $value = is_array($value) ? $value : [];

            $resp = array_map(function($p){

            $show = new Show();
            $show->setIdShow($p["id_show"]);
            $show->setState($p["state"]);
            $show->setDay($p["day"]);
            $show->setHour($p["hour"]);

            /*$movieSearch = new Movie();
            $movieSearch = $this->GetMovieById($p["id_movie"]);
            $show->setMovie($movieSearch);*/

            //solo cargo el id para luego buscarlo en la controladora y conformar el objeto como es debido y no tener que hacer repeticion de codigo ya que no puedo acceder a otros daos
            $movieSearch = new Movie();
            $movieSearch->setIdMovie($p["id_movie"]);
            $show->setMovie($movieSearch);

            /*$movieTheaterSearch = new MovieTheater();
            $movieTheaterSearch = $this->GetMovieTheaterById($p["id_movie_theater"]);
            $show->setMovieTheater($movieTheaterSearch);*/

            //aqui tambien solo cargo el id para luego buscarlo en la controladora y conformar el objeto como es debido  y no tener que hacer repeticion de codigo ya que no puedo acceder a otros daos
            $movieTheaterSearch = new MovieTheater();
            $movieTheaterSearch->setIdMovieTheater($p["id_movie_theater"]);
            $show->setMovieTheater($movieTheaterSearch);

            //$cinemaSearch = new Cinema();
            //$cinemaSearch->setIdCinema()

              return $show;
            }, $value);
            return count($resp) > 1 ? $resp : $resp[0];
          }
    }

?>
