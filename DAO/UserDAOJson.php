<?php

namespace DAO;

use Models\User as User;
use DAO\IDAO as IDAO;

class UserDAOJson implements IDAO
{
    private $userList = array();
    
    public function add($user)
    {
        $this->RetrieveData();
        $user->setIdUser($this->GetNextId()); 
        array_push($this->userList,$user);
        $this->SaveData();
    }

    public function getAll()
    {
        $this->RetrieveData();
        return $this->userList;
    }

    private function SaveData()
    {
        $arrayToEncode = array();
        foreach($this->userList as $user)
        {
            $valueArray = array();
            $valueArray["userName"] = $user->getUserName();
            $valueArray["password"] = $user->getPassword();
            $valueArray["firstName"] = $user->getFirstName();
            $valueArray["lastName"] = $user->getLastName();
            $valueArray["email"] = $user->getEmail();
            
            array_push($arrayToEncode,$valueArray);
        }
        $jsonContent = json_encode($arrayToEncode,JSON_PRETTY_PRINT);
        file_put_contents('Data/Users.json',$jsonContent);
    }

    private function RetrieveData()
    {
        $this->userList = array();
        if(file_exists('Data/Users.json'))
        {
            $jsonContent = file_get_contents('Data/Users.json');
            $arrayToDecode = ($jsonContent) ? json_decode($jsonContent,true) : array();
            
            foreach($arrayToDecode as $valueArray)
            {
                $user = new User();
                $user->setUserName($valueArray["userName"]);
                $user->setPassword($valueArray["password"]);
                $user->setFirstName($valueArray["firstName"]);
                $user->setLastName($valueArray["lastName"]);
                $user->setEmail($valueArray["email"]);

                array_push($this->userList, $user);
            }
        }
    }

    public function remove($id)
    {
        $this->RetrieveData();

            foreach($this->userList as $key => $user){
                if($user->getIdUser() == $id){
                    unset($this->userList[$key]);
                }
            }

            $this->SaveData();    
    }

    private function GetNextId()
        {
            $id = 0;

            foreach($this->userList as $user)
            {
                $id = ($user->getIdUser() > $id) ? $user->getIdUser() : $id;
            }

            return $id + 1;
        }
}

?>