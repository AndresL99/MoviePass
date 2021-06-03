<?php
	namespace DAO;

    use \Exception as Exception;
    use \PDOException as PDOException;
    use DAO\ICinemaDAO as ICinemaDAO;
    use DAO\IDAO as IDAO;
    use Models\Cinema as Cinema;
    use Models\Show as Show;
    use Models\MovieTheater as MovieTheater;
    use DAO\Connection as Connection;
    use DAO\QueryType as QueryType;

    class MovieTheaterDAO implements IMovieTheaterDAO, IDAO
    {
        private $connection;
        private $tableName = "movie_theaters";

        public function Add(MovieTheater $movieTheater)
        {
            $query = "INSERT INTO ".$this->tableName." (state, name, current_capacity, price, total_capacity, id_cinema) VALUES (:state, :name, :current_capacity, :price, :total_capacity, :id_cinema);";

        	try
            {
                $parameters["state"] = true;
                $parameters["name"] = $movieTheater->getName();
                $parameters["current_capacity"] = $movieTheater->getCurrentCapacity();
                $parameters["price"] = $movieTheater->getPrice();
                $parameters["total_capacity"] = $movieTheater->getTotalCapacity();

                $parameters["id_cinema"] = $movieTheater->getCinema()->getIdCinema();

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
                    $result = array($result); // hago esto para que cuando devuelva un solo valor de la base lo convierta en array para no tener problemas al querer recorrer la variable con un foreach
		      }
		  	}
		    catch(Exception $ex){
		       throw $ex;
		    }
		    return $result;
        }

        public function Remove($id)
        {
            $query = "UPDATE ".$this->tableName." SET state = :state WHERE id_movie_theater = :id_movie_theater";
            try
            {
                $parameters["id_movie_theater"] = $id;
                $parameters["state"] = 0;

                $this->connection = Connection::GetInstance();

                $rowCount = $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }

            return $rowCount;
        }

        public function Enable($id)
        {
            $query = "UPDATE ".$this->tableName." SET state = :state WHERE id_movie_theater = :id_movie_theater";
            try
            {
                $parameters["id_movie_theater"] = $id;
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

        public function Update(MovieTheater $movieTheater)
        {
            $query = "UPDATE ".$this->tableName." SET :state = state, name = :name, current_capacity = :current_capacity, price = :price, total_capacity = :total_capacity, id_cinema = :id_cinema  WHERE id_movie_theater = :id_movie_theater";

            $parameters = array();

        	try
            {
                $parameters["id_movie_theater"] = $movieTheater->getIdMovieTheater();

                $parameters["state"] = $movieTheater->getState();
                $parameters["name"] = $movieTheater->getName();
                $parameters["current_capacity"] = $movieTheater->getCurrentCapacity();
                $parameters["price"] = $movieTheater->getPrice();
                $parameters["total_capacity"] = $movieTheater->getTotalCapacity();

                $parameters["id_cinema"] = $movieTheater->getCinema()->getIdCinema();

                $this->connection = Connection::GetInstance();

                $rowCount = $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }

            return $rowCount;
        }

        public function GetMovieTheaterById($idMovieTheater)
        {
            $sql = "SELECT * FROM " . $this->tableName . " WHERE id_movie_theater = :id_movie_theater";
            $result = array();

            try {
                    $parameters["id_movie_theater"] = $idMovieTheater;

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

        public function GetMovieTheaterByName($movieTheaterName)
        {
            $sql = "SELECT * FROM " . $this->tableName . " WHERE name = :name";
            $result = null;

            try {
                    $parameters["name"] = $movieTheaterName;

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

        public function GetMovieTheatersByIdCinema($id_cinema)
        {
            $query ="select movie_theaters.*
                    from movie_theaters
                    where movie_theaters.id_cinema = :id_cinema";
            $result = array();

            try{

                $this->connection = Connection::GetInstance();

                $parameters['id_cinema'] = $id_cinema;

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

				public function IsMovieTheaterInCinema($movieTheater, $idCinema)
				{
				    //$sql = "SELECT * FROM " . $this->tableName . " WHERE name = :name";

				    $sql = "select mt.* from " . $this->tableName . " mt
				    inner join cinemas c on c.id_cinema = mt.id_cinema
				    where mt.id_cinema = :id_cinema and mt.id_movie_theater = :id_movie_theater_search";

				    $result = null;
				    try {
				            $parameters["id_cinema"] = $idCinema;
										$parameters["id_movie_theater_search"] = $movieTheater->getIdMovieTheater();

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

        public function GetCinemaById($idCinema)
        {
            $sql = "SELECT * FROM cinemas WHERE id_cinema = :id_cinema";
            $cinema = null;

            try {
                    $parameters["id_cinema"] = $idCinema;

                    $this->connection = Connection::getInstance();
                    $resultSet = $this->connection->Execute($sql,$parameters);

                    if(!empty($resultSet))
                    {
                        foreach($resultSet as $value)
                        {
                            $cinema = new Cinema();
                            $cinema->setIdCinema($value["id_cinema"]);
                            $cinema->setState($value["state"]);
                            $cinema->setName($value["name"]);
                            $cinema->setAddress($value["address"]);
                        }
                    }
                }
            catch(Exception $ex){
               throw $ex;
            }

            return $cinema;
        }

        public function getByIdShow(Show $show)
         {
			try {
				$query = "CALL movieTheater_getByIdShow(?)";
				$parameters ["id_show"] = $show->getIdShow();
				$this->connection = Connection::GetInstance();
				$results = $this->connection->Execute($query, $parameters,QueryType::StoredProcedure);				
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

		    $movieTheater = new MovieTheater();
            $movieTheater->setIdMovieTheater($p["id_movie_theater"]);
            $movieTheater->setState($p["state"]);
            $movieTheater->setName($p["name"]);
            $movieTheater->setCurrentCapacity($p["current_capacity"]);
            $movieTheater->setPrice($p["price"]);
            $movieTheater->setTotalCapacity($p["total_capacity"]);

            $cinemaSearch = new Cinema();
            $cinemaSearch = $this->GetCinemaById($p["id_cinema"]);
            $movieTheater->setCinema($cinemaSearch);

            /*$cinemaSearch = new Cinema();
            $cinemaSearch->setIdCinema($p["id_cinema"]);
            $movieTheater->setCinema($cinemaSearch);*/

		      return $movieTheater;
		    }, $value);
		    return count($resp) > 1 ? $resp : $resp[0];
		  }
    }

?>
