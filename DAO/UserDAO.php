<?php
    namespace DAO;

    use \Exception as Exception;
    use \PDOException as PDOException;
    use DAO\IDAO as IDAO;
    use Models\User as User;
    use DAO\Connection as Connection;

    class UserDAO implements IDAO
    {
        private $connection;
        private $tableName = "users";

        public function add($user)
        {
            $query = "INSERT INTO ".$this->tableName." (userName, password, firstName, lastName, email, idRol) VALUES (:userName, :password, :firstName, :lastName, :email, :idRol);";
        	try
            {
                $parameters["userName"] = $user->getUserName();
                $parameters["password"] = $user->getPassword();
                $parameters["firstName"] = $user->getFirstName();
                $parameters["lastName"] = $user->getLastName();
                $parameters["email"] = $user->getEmail();
                $parameters['idRol'] = $user->getRole();

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getUserByName($userName)
        {
            $sql = "SELECT * FROM users WHERE userName = :userName";
            $parameters["userName"] = $userName;

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

        public function getUserById($idUser)
        {
            $sql = "SELECT * FROM users WHERE idUser = :idUser";
            $parameters["idUser"] = $idUser;

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
            $sql = "SELECT * FROM users";
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
		       throw $e;
		    }
		    return $result;
        }

        public function remove($id)
        {
            $query = "DELETE FROM ".$this->tableName." WHERE idUser = :idUser";

        	try
            {
                $parameters["idUser"] = $id;

                $this->connection = Connection::GetInstance();
                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }


        protected function mapear($value)
        {
		    $value = is_array($value) ? $value : [];

            $resp = array_map(function($p)
            {

		    $user = new User();
            $user->setIdUser($p["idUser"]);
            $user->setUserName($p["userName"]);
            $user->setPassword($p["password"]);
            $user->setFirstName($p["firstName"]);
            $user->setLastName($p["lastName"]);
            $user->setEmail($p["email"]);
            $user->setRole($p["idRol"]);

		      return $user;
		    }, $value);
		    return count($resp) > 1 ? $resp : $resp[0];
		  }
    }

?>
