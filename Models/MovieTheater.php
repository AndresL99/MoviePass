<?php 

namespace Models;

use Models\Cinema as Cinema;

class MovieTheater
{

	private $id_movie_theater;
	private $state;
	private $name;
	private $current_capacity;
	private $price;
	private $total_capacity;
	private $cinema;

	public function getIdMovieTheater()
	{
		return $this->id_movie_theater;
	}

	public function setIdMovieTheater($id_movie_theater)
	{
		$this->id_movie_theater = $id_movie_theater;
	}

	public function getState(){
		return $this->state;
	}

	public function setState($state){
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

	public function getCurrentCapacity()
	{
		return $this->current_capacity;
	}

	public function setCurrentCapacity($current_capacity)
	{
		$this->current_capacity = $current_capacity;
	}

	public function getPrice()
	{
		return $this->price;
	}

	public function setPrice($price)
	{
		$this->price = $price;
	}

	public function getTotalCapacity()
	{
		return $this->total_capacity;
	}

	public function setTotalCapacity($total_capacity)
	{
		$this->total_capacity = $total_capacity;
    }

    public function getCinema()
	{
		return $this->cinema;
	}

	public function setCinema(Cinema $cinema)
	{
		$this->cinema = $cinema;
    }

}

?>