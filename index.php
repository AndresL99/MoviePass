<?php
 
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	require "Config/Autoload.php";
	require "Config/Config.php";

	use Config\Autoload as Autoload;
	use Config\Router 	as Router;
	use Config\Request 	as Request;
		
	Autoload::Start();
		
	//$genres = file_get_contents('https://api.themoviedb.org/3/genre/movie/list?api_key=6fef728cda826ca3e07c760bb8960523&language=en-US');
	


	
	session_start();
	//session_destroy();
		
	//$genres = file_get_contents('https://api.themoviedb.org/3/genre/movie/list?api_key=6fef728cda826ca3e07c760bb8960523&language=en-US');
	
	//$movies = file_get_contents('https://api.themoviedb.org/3/movie/990?api_key=' . TMDB_KEY . '&language=en-US');
	

	require_once(VIEWS_PATH."header.php");

	//require_once(VIEWS_PATH."nav.php");


	Router::Route(new Request());

	require_once(VIEWS_PATH."footer.php");
?>