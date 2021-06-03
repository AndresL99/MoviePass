<?php 

namespace Models;

use Models\MovieTheater as MovieTheater;
use Models\Movie as Movie;

class Show
{
	private $id_show;
	private $state;
	private $day;
	private $hour;
	private $movie;
	private $movie_theater;

	public function getIdShow(){
		return $this->id_show;
	}

	public function setIdShow($id_show){
		$this->id_show = $id_show;
	}

	public function getState(){
		return $this->state;
	}

	public function setState($state){
		$this->state = $state;
	}

	public function getDay(){
		return $this->day;
	}

	public function setDay($day){
		$this->day = $day;
	}

	public function getHour(){
		return $this->hour;
	}

	public function setHour($hour){
		$this->hour = $hour;
	}

	public function getMovie(){
		return $this->movie;
	}

	public function setMovie(Movie $movie){
		$this->movie = $movie;
	}

	public function getMovieTheater(){
		return $this->movie_theater;
	}

	public function setMovieTheater(MovieTheater $movie_theater){
		$this->movie_theater = $movie_theater;
	}
}


 ?>