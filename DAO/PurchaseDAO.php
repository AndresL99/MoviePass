<?php

namespace DAO;

    use Models\Purchase as Purchase;
    use DAO\QueryType as QueryType;
    use DAO\Connection as Connection;
    use \Exception as Exception;
    use \PDOException as PDOException;

class PurchaseDAO
{
    private $tableName = "purchases";
        private $connection;

        public function add(Purchase $purchase)
        {
            $query = "INSERT INTO ".$this->tableName." (ticket_quantity, discount, date_purchase, total,idUser) VALUES (:ticket_quantity, :discount, :date_purchase, :total,:idUser);";
        	try
            {
                $parameters["ticket_quantity"] = $purchase->getTicketQuantity();
                $parameters["discount"] = $purchase->getDiscount();
                $parameters["date_purchase"] = $purchase->getDate();
                $parameters["total"] = $purchase->getTotal();
                $parameters["idUser"] = $purchase->getUser()->getIdUser();

                $this->connection = Connection::GetInstance();

                $rowCount = $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
            return $rowCount;
        }

        public function getAll()
         {
            $sql = "SELECT * FROM purchases";
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

        public function getById($idPurchase)
        {
            $sql = "SELECT * FROM purchases WHERE idPurchase = :idPurchase";
            $parameters["idPurchase"] = $idPurchase;

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

        public function totalSoldByMovie($movieId)
    {
        $query = "SELECT sum(mt.price) as suma  from purchases p
                    inner join tickets t on t.idPurchase= p.idPurchase
                    inner join shows s on s.id_show=t.id_show
                    inner join movie_theaters mt on s.id_movie_theater= mt.id_movie_theater
                    where s.id_movie=$movieId 
                    group by id_movie";
        try {
            $this->connection = Connection::getInstance();
            $results = $this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        if(!empty($results)){
            return $results[0]["suma"];
        }
        else
        {
            return 0;
        }
    }

    public function totalSoldByCinema($cinemaId)
    {
        $query = "SELECT sum(mt.price) as suma from purchases p
                    inner join tickets t on t.idPurchase=p.idPurchase
                    inner join shows s on s.id_show=t.id_show
                    inner join movie_theaters mt on mt.id_movie_theater=s.id_movie_theater
                    inner join cinemas c on c.id_cinema=mt.id_cinema
                    where c.id_cinema=$cinemaId 
                    group by c.id_cinema";
        try {
            $this->connection = Connection::getInstance();
            $results = $this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        if(!empty($results)){
            return $results[0]["suma"];
        }
        else
        {
            return 0;
        }
    }

        protected function mapear($value)
        {
		    $value = is_array($value) ? $value : [];

            $resp = array_map(function($p)
            {

		    $purchase = new Purchase();
            $purchase->setId($p["idPurchase"]);
            $purchase->setTicketQuantity($p["userName"]);
            $purchase->setDiscount($p["password"]);
            $purchase->setDate($p["firstName"]);
            $purchase->setTotal($p["lastName"]);
            $purchase->setIdUser($p["idUser"]);

		      return $purchase;
		    }, $value);
		    return count($resp) > 1 ? $resp : $resp[0];
		  }
}

?>
