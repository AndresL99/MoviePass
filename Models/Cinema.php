<?php

namespace Models;

class Cinema
{

    private $idCinema;
    private $state;
    private $name;
    private $address;

	public function getIdCinema()
	{
		return $this->idCinema;
	}

	public function setIdCinema($idCinema)
	{
		$this->idCinema = $idCinema;
	}

	public function getState()
	{
		return $this->state;
	}

	public function setState($state)
	{
		$this->state = $state;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getAddress(){
		return $this->address;
	}

	public function setAddress($address){
		$this->address = $address;
	}
}
?>