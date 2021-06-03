<?php

    namespace DAO;

    use \Exception as Exception;
    use \PDOException as PDOException;
    use Models\Role as Role;
    use DAO\Connection as Connection;

    class RolesDAO 
    {
        private $connection;
        private $tableName = "roles";

        public function Add($role)
        {
            $query = "INSERT INTO ".$this->tableName." (role) VALUES (:role);";
        	try
            {
                $parameters["role"] = $role->getRol();

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
            
        }

        public function GetAll()
        {
            $sql = "SELECT * FROM roles";
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


        public function buscarRol($data)
        {
            try 
            {
                $object = null;
    
                $query = 'SELECT * FROM '.$this->table.' WHERE role = :role';
    
                $pdo = new Connection();
                $connection = $pdo->Connect();
                $command = $connection->prepare($query);			
    
                $command->bindParam(':role', $data);
    
                $command->execute();
    
                while ($row = $command->fetch())
                {
                    $nombreRol=($row['role']);
                    
    
                    $object = new Role($nombreRol);
                    $object->setIdRol($row['idRol']);	
                }
    
                return $object;
    
            }
            catch (PDOException $ex) {
                throw $ex;
            }
            catch (Exception $e) {
                throw $e;
            }
        }

        protected function mapear($value) 
        {
		    $value = is_array($value) ? $value : [];

            $resp = array_map(function($p)
            {

		    $role = new Role();
            $role->setIdRol($p["idRol"]);
            $role->setRol($p["role"]);
		      return $role; 
		    }, $value);
		    return count($resp) > 1 ? $resp : $resp[0]; 
		  }
    }

?>
