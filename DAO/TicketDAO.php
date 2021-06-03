<?php

    namespace DAO;

    use Models\Ticket as Ticket;
    use DAO\QueryType as QueryType;
    use DAO\Connection as Connection;
    use \Exception as Exception;
    use \PDOException as PDOException;
    use Models\Show as Show;
    use Models\Purchase as Purchase;
    use Models\Movie as Movie;

    class TicketDAO
    {

        private $tableName = "tickets";
        private $tableNameMovieTheater = "movie_theaters";
        private $tableNameCinema = "cinemas";
        private $tableNameShow = "shows";
        private $connection;

        public function add(Ticket $ticket)
        {
            try
            {
                $query = "INSERT INTO ".$this->tableName." (ticket_number,qr, idPurchase, id_show) VALUES (:ticket_number,:qr, :idPurchase, :id_show);";
                $parameters["ticket_number"] = $ticket->getTicketNumber();
                $parameters["qr"] = $ticket->getQr();
                $parameters["idPurchase"] = $ticket->getPurchase()->getId();
                $parameters["id_show"] = $ticket->getShow()->getIdShow();
                $this->connection = Connection::GetInstance();
                $this->connection->ExecuteNonQuery($query, $parameters);
                return true;
            }
            catch(Exception $e)
            {
                throw $e;
                return false;
            }
        }

        public function getByNumber($ticket_number)
        {
            $sql = "SELECT * FROM tickets WHERE ticket_number = :ticket_number";
            $parameters["ticket_number"] = $ticket_number;

            try
            {
                $this->connection = Connection::getInstance();
                $resultSet = $this->connection->execute($sql, $parameters);
            }
            catch(Exception $e)
            {
                throw $e;
            }

            if(!empty($resultSet))
                return $this->mapear($resultSet);
            else
                return false;
        }

        public function getAll()
        {
              $sql = "SELECT * FROM tickets";
  		    $result = array();

              try
              {
  		      $this->connection = Connection::getInstance();
  		      $resultSet = $this->connection->execute($sql);

  		      if(!empty($resultSet))
  		      {
  		        $result = $this->mapear($resultSet);
  		      }
  		  	}
              catch(Exception $e)
              {
  		       throw $ex;
  		    }
  		    return $result;
        }

        public function getTicketsOfShows($id_show)
        {
            try
            {
                $query = "SELECT * FROM tickets WHERE .id_show = :id_show";
                $parameters['id_show'] = $id_show;
                $this->connection = Connection::getInstance();
                $results = $this->connection->Execute($query, $parameters);
                $results = $results[0];

                return $results["count(id_show)"];

            }
            catch(Exception $e)
            {
                return false;
            }
        }

        public function getSoldTicketsByShow($id_show)
        {
            $query="SELECT count(*) as count from $this->tableName where id_show = $id_show";
            try 
            {
                $this->connection = Connection::getInstance();
                $results=$this->connection->execute($query);
            } 
            catch (Exception $ex) 
            {
                throw $ex;
            }
            return $results[0]["count"];
        }

        

        public function getShowIdByTicketId($idTicket)
        {
            $query="SELECT t.id_show from $this->tableName t where idTicket=$idTicket";
            try {
                $this->connection = Connection::getInstance();
                $results=$this->connection->execute($query);
            } catch (Exception $ex) {
                throw $ex;
            }
            return $results[0]["id_show"];
        }

        public function getByShowId($id_show)
        {
            $query="SELECT * from $this->tableName where id_show = $id_show";
            try
            {
                $this->connection = Connection::getInstance();
                $results=$this->connection->execute($query);
            } catch (Exception $ex) {
                throw $ex;
            }
            $newArr=array();
            foreach ($results as $value) {
                $newArr[]=new Ticket($value["idTicket"],$value["ticket_number"]);
            }
            return $newArr;
        }

        public function getByUserId($idUser)
        {
            $query="SELECT * from $this->tableName t
            inner join purchases p on t.idPurchase=p.idPurchase
            where p.idUser=$idUser";
            try {
                $this->connection = Connection::getInstance();
                $results=$this->connection->execute($query);
            } catch (Exception $ex) {
                throw $ex;
            }
            $newArr=array();
            foreach ($results as $ticket) {
                $newArr[]=new Ticket($ticket["idTicket"],$ticket["ticket_number"]);
            }
            return $newArr;
        }
    
        public function getByPurchaseId($idPurchase)
        {
            $query="SELECT * from $this->tableName where idPurchase = $idPurchase";
            try {
                $this->connection = Connection::getInstance();
                $results=$this->connection->execute($query);
            } catch (Exception $ex) {
                throw $ex;
            }
            $newArr=array();
            foreach ($results as $value) {
                $newArr[]=new Ticket($value["idTicket"],$value["ticket_number"]);
            }
            return $newArr;
        }
    

        /*private function GetNextTicketNumber()
        {
                $id = 0;

                $ticketList = $this->getAll();

                foreach($ticketList as $ticket)
                {
                    $id = ($ticket->getIdTicket() > $id) ? $ticket->getIdTicket() : $id;
                }

                return $id + 1;
        }*/

        public function getTicketsByShow($id_show)
        {
            $query = "SELECT ". $this->tableName .".* 
            FROM ". $this->tableName ." INNER JOIN ". 
            $this->tableNameShow . " ON ". $this->tableName .".id_show =  ". $this->tableNameShow .".id_show".
            " INNER JOIN ". $this->tableNameMovieTheater ." ON ". $this->tableNameShow .".id_movie_theater = " . $this->tableNameMovieTheater .".id_movie_theater".
            " INNER JOIN ". $this->tableNameCinema ." ON ". $this->tableNameMovieTheater .".id_cinema = ". $this->tableNameCinema .".id_cinema
            WHERE ". $this->tableName .".id_show = :id_show
            GROUP BY ". $this->tableName .".idPurchase;";
        
            $parameters['id_show'] = $id_show;
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query,$parameters);
            
            return $this->toArray($this->mapear($resultSet));
        }

        public function getTicketsByCinema($id_cinema)
        {
            $query = "SELECT ". $this->tableName .".* FROM ". $this->tableName ." INNER JOIN ". 
            $this->tableNameShow . " ON ". $this->tableName .".id_show =  ". $this->tableNameShow .".id_show".
            " INNER JOIN ". $this->tableNameMovieTheater ." ON ". $this->tableNameShow .".id_movie_theater = " . $this->tableNameMovieTheater .".id_movie_theater".
            " INNER JOIN ". $this->tableNameCinema ." ON ". $this->tableNameMovieTheater .".id_cinema = ". $this->tableNameCinema .".id_cinema
            WHERE ". $this->tableNameMovieTheater .".id_cinema = :id_cinema
            GROUP BY ". $this->tableNameTicket .".idpurchase;";
        
            $parameters['id_cinema'] = $id_cinema;
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query,$parameters);
            
            return $this->toArray($this->mapear($resultSet));
        }

        public function getTicketsByMovieTheater($id_movie_theater)
        {
            $query = "SELECT ". $this->tableName .".* FROM ". $this->tableName ." INNER JOIN ". 
            $this->tableNameShow . " ON ". $this->tableName .".id_show =  ". $this->tableNameShow .".id_show".
            " INNER JOIN ". $this->tableNameMovieTheater ." ON ". $this->tableNameShow .".id_movie_theater = " . $this->tableNameMovieTheater .".id_movie_theater".
            " INNER JOIN ". $this->tableNameCinema ." ON ". $this->tableNameMovieTheater .".id_cinema = ". $this->tableNameCinema .".id_cinema
            WHERE ". $this->tableNameMovieTheater .".id_movie_theater = :id_movie_theater
            GROUP BY ". $this->tableName .".idPurchase;";
        
            $parameters['id_movie_theater'] = $id_movie_theater;
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query,$parameters);
            
            return $this->toArray($this->mapear($resultSet));
        }

        public function calcCantMoneyForMovie()
        {
          /*SELECT COUNT(tickets.idTicket) * movie_theaters.price as cant_pesos_recaudados_por_peli FROM tickets
            INNER JOIN shows ON tickets.id_show = shows.id_show
            INNER JOIN movie_theaters ON movie_theaters.id_movie_theater = shows.id_movie_theater
            INNER JOIN movies on shows.id_movie = movies.id_movie
            WHERE 446893 = movies.id_movie;*/
        }

        protected function mapear($value)
        {
		    $value = is_array($value) ? $value : [];

            $resp = array_map(function($p)
            {

		    $ticket = new Ticket();
            $ticket->setId($p["idTicket"]);
            $ticket->setTicketNumber($p["ticket_number"]);
            $ticket->setQr($p["qr"]);

            $show = new Show();
            $show->setIdShow($p["id_show"]);
            $ticket->setShow($show);

            /*echo "<pre>";
            var_dump($ticket);
            echo "<pre>";

            $movie = new Movie();
            $movie->setIdMovie($ticket->getShow()->getMovie()->getIdMovie());
            $show->setMovie($movie);

            $movieTheater = new MovieTheater();
            $movieTheater->setIdMovieTheater($ticket->getShow()->getMovieTheater()->getIdMovieTheater());
            $show->setMovieTheater($movieTheater);*/

            $purchase = new Purchase();
            $purchase->setId($p["idPurchase"]);
            $ticket->setPurchase($purchase);

		      return $ticket;
		    }, $value);
		    return count($resp) > 1 ? $resp : $resp[0];
		  }

          private function toArray($value)
          {
            if(is_array($value))
                return $value;
            else
                return array($value);
        }
    }

?>
