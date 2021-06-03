<?php
    namespace DAO;

    use DAO\IDAO as IDAO;
    use DAO\ICinemaDAO as ICinemaDAO;
    use Models\Cinema as Cinema;

    class CinemaDAOJson implements IDAO, ICinemaDAO 
    {
        private $cinemaList = array();

        public function Add(Cinema $cinema)
        {
            $this->RetrieveData();

            $cinema->setIdCinema($this->GetNextId());
            $cinema->setState(true); 
            
            array_push($this->cinemaList, $cinema);

            $this->SaveData();
        }

        public function GetAll()
        {
            $this->RetrieveData();

            return $this->cinemaList;
        }

        public function Remove($id)
        {
            $this->RetrieveData();

            foreach($this->cinemaList as $key => $cinema){
                if($cinema->getIdCinema() == $id){
                    $this->cinemaList[$key]->setState(false);
                }
            }

            $this->SaveData();                        
        }

        public function Enable($id)
        {
            $this->RetrieveData();

            foreach($this->cinemaList as $key => $cinema){
                if($cinema->getIdCinema() == $id){
                    $this->cinemaList[$key]->setState(true);
                }
            }

            $this->SaveData();                        
        }


        public function Update(Cinema $newCinema)
        {
            $this->RetrieveData();

            $flag = 0;
            foreach($this->cinemaList as $key => $cinema){
                if($cinema->getIdCinema() == $newCinema->getIdCinema()){
                    $this->cinemaList[$key] = $newCinema;
                    $flag = 1;
                }
            }

            $this->SaveData();
            return $flag;                        
        }

        public function GetCinemaById($id){
            $this->RetrieveData();

            $cinemaSearch = null;
            foreach($this->cinemaList as $cinema) 
            {
                if($id == $cinema->getIdCinema()) 
                {
                    $cinemaSearch = $cinema;
                }
            }

            return $cinemaSearch;
        }

        private function SaveData()
        {
            $arrayToEncode = array();

            foreach($this->cinemaList as $cinema)
            {
                $valuesArray["idCinema"] = $cinema->getIdCinema();
                $valuesArray["state"] = $cinema->getState();
                $valuesArray["name"] = $cinema->getName();
                $valuesArray["address"] = $cinema->getAddress();

                array_push($arrayToEncode, $valuesArray);
            }

            $jsonContent = json_encode($arrayToEncode, JSON_PRETTY_PRINT);
            
            file_put_contents('Data/cinemas.json', $jsonContent);
        }

        private function RetrieveData()
        {
            $this->cinemaList = array();

            if(file_exists('Data/cinemas.json'))
            {
                $jsonContent = file_get_contents('Data/cinemas.json');

                $arrayToDecode = ($jsonContent) ? json_decode($jsonContent, true) : array();

                foreach($arrayToDecode as $valuesArray)
                {
                    $cinema = new Cinema();
                    $cinema->setIdCinema($valuesArray["idCinema"]);
                    $cinema->setState($valuesArray["state"]);
                    $cinema->setName($valuesArray["name"]);
                    $cinema->setaddress($valuesArray["address"]);

                    array_push($this->cinemaList, $cinema);
                }
            }
        }

        private function GetNextId()
        {
            $id = 0;

            foreach($this->cinemaList as $cinema)
            {
                $id = ($cinema->getIdCinema() > $id) ? $cinema->getIdCinema() : $id;
            }

            return $id + 1;
        }
    }
?>