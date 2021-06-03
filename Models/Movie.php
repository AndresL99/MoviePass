<?php 

namespace Models;

class Movie
{

	private $id_movie;
	//private $id_api_movie;
	private $title;
	private $poster_path;
	private $overview;	//descripcion
	private $release_date; // fecha de estreno
	private $genre_ids = array();
	private $original_language;
	private $vote_counts;
	private $popularity;
	private $runtime; // tiempo de duracion
	private $vote_average;
	private $is_active;

	public function getIdMovie()
	{
		return $this->id_movie;
	}

	public function setIdMovie($id_movie)
	{
		$this->id_movie = $id_movie;
	}

	/*public function getIdApiMovie(){
		return $this->id_api_movie;
	}

	public function setIdApiMovie($id_api_movie){
		$this->id_api_movie = $id_api_movie;
	}*/

	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getPosterPath()
	{
		return $this->poster_path;
	}

	public function setPosterPath($poster_path)
	{
		$this->poster_path = $poster_path;
	}

	public function getOverview()
	{
		return $this->overview;
	}

	public function setOverview($overview)
	{
		$this->overview = $overview;
	}

	public function getReleaseDate()
	{
		return $this->release_date;
	}

	public function setReleaseDate($release_date)
	{
		$this->release_date = $release_date;
	}

	public function getGenreIds()
	{
		return $this->genre_ids;
	}

	public function setGenreIds($genre_ids)
	{
		$this->genre_ids = $genre_ids;
	}

	public function getOriginalLanguage()
	{
		return $this->original_language;
	}

	public function setOriginalLanguage($original_language)
	{
		$this->original_language = $original_language;
	}

	public function getVoteCounts()
	{
		return $this->vote_counts;
	}

	public function setVoteCounts($vote_counts)
	{
		$this->vote_counts = $vote_counts;
	}

	public function getPopularity()
	{
		return $this->popularity;
	}

	public function setPopularity($popularity)
	{
		$this->popularity = $popularity;
	}

	public function getRuntime()
	{
		return $this->runtime;
	}

	public function setRuntime($runtime)
	{
		$this->runtime = $runtime;
	}

	public function getVoteAverage()
	{
		return $this->vote_average;
	}

	public function setVoteAverage($vote_average)
	{
		$this->vote_average = $vote_average;
	}

	public function getIsActive()
	{
		return $this->is_active;
	}

	public function setIsActive($is_active)
	{
		$this->is_active = $is_active;
	}

}

 ?>