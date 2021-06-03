<?php
    namespace DAO;

    use DAO\IDAO as IDAO;
    use Models\Movie as Movie;
    use Models\Genre as Genre;
    use Models\MovieXGenre as MovieXGenre;
    use \Exception as Exception;
    use \PDOException as PDOException;
    use DAO\Connection as Connection;

    class MovieDAO implements IDAO
    {
        private $connection;
        private $tableName = "movies";

        public function GetMoviesApi()
        {

        	$movieListJson = file_get_contents('https://api.themoviedb.org/3/movie/now_playing?api_key=' . TMDB_KEY . '&language=es-MX&page=1');

			$movieListApi = ($movieListJson) ? json_decode($movieListJson, true) : array();

            foreach ($movieListApi["results"] as $movie) {

                $movieDataJson = file_get_contents('https://api.themoviedb.org/3/movie/' . $movie["id"] . '?api_key=' . TMDB_KEY . '&language=en-US');

                $movieData = ($movieDataJson) ? json_decode($movieDataJson, true) : array();

                $newMovie = new Movie();
                $newMovie->setIdMovie($movie["id"]);
                $newMovie->setTitle($movie["title"]);
                $newMovie->setPosterPath($movie["poster_path"]);
                $newMovie->setOverview($movie["overview"]);
                $newMovie->setReleaseDate($movie["release_date"]);
                $newMovie->setGenreIds($movie["genre_ids"]);
                $newMovie->setOriginalLanguage($movie["original_language"]);
                $newMovie->setVoteCounts($movie["vote_count"]);
                $newMovie->setPopularity($movie["popularity"]);
                $newMovie->setRuntime($movieData["runtime"]);
                $newMovie->setVoteAverage($movie["vote_average"]);

                if ($this->Add($newMovie))
                {
                    $this->AddMovieGenre($newMovie);
                } 
            }
            return $this->GetAll();
        }

        public function GetAllGenres()
        {
            $sql = "SELECT * FROM genres";
		    $genreList = array();

		    try {
		      $this->connection = Connection::getInstance();
              $resultSet = $this->connection->execute($sql);
              $genre = new Genre();

		      if(!empty($resultSet))
		      {
                if(!is_array($resultSet))
                    $resultSet = array($resultSet);

                    foreach ($resultSet as $result)
                    {
                        $genre->setIDGenre($result["id_genre"]);
                        $genre->setName($result["genre_name"]);

                        array_push($genreList, $genre);
                    }
              }
              
		  	}
		    catch(Exception $ex){
		       throw $ex;
		    }
		    return $genreList;
        }

        public function AddMovieGenre (Movie $movie)
        {

            $genreListByMovie = $movie->getGenreIds();  
            $genreList = $this->GetAllGenres();
            $newMovieGenre = new MovieXGenre();

            foreach ($genreListByMovie as $key => $genre)
            {
                if ($genre){
                    $query = "INSERT INTO movies_x_genres (id_movie, id_genre) VALUES (:id_movie, :id_genre);";

                    try
                    {

                        $parameters = array();

                        $parameters["id_movie"] = $movie->getIdMovie();
                        $parameters["id_genre"] = $genre;

                        $this->connection = Connection::GetInstance();

                        $rowCount = $this->connection->ExecuteNonQuery($query, $parameters);
                        
                    }
                    catch(Exception $ex)
                    {
                        throw $ex;
                    }
                }
            }
        }

        public function Add(Movie $movie)
        {
            $query = "INSERT INTO ".$this->tableName." (id_movie, title, poster_path, overview, release_date, original_language, vote_count, popularity, runtime, 
            vote_average, is_active) VALUES (:id_movie, :title, :poster_path, :overview, :release_date, :original_language, :vote_count, :popularity, :runtime, :vote_average, :is_active);";
            
            try
            {

                $parameters = array();

                $parameters["id_movie"] = $movie->getIdMovie();
                $parameters["title"] = $movie->getTitle();
                $parameters["poster_path"] = $movie->getPosterPath();
                $parameters["overview"] = $movie->getOverview();
                $parameters["release_date"] = $movie->getReleaseDate();
                $parameters["original_language"] = $movie->getOriginalLanguage();
                $parameters["vote_count"] = $movie->getVoteCounts();
                $parameters["popularity"] = $movie->getPopularity();
                $parameters["runtime"] = $movie->getRuntime();
                $parameters["vote_average"] = $movie->getVoteAverage();
                $parameters["is_active"] = false;

                $this->connection = Connection::GetInstance();

                $rowCount = $this->connection->ExecuteNonQuery($query, $parameters);
                
            }
            catch(Exception $ex)
            {
            	throw $ex;
            }
            return $rowCount;
        }

        public function Remove($id)
        {

        }

        public function GetAll()
        {
            $sql = "SELECT * FROM " .$this->tableName;

		    $result = array();

		    try {
		      $this->connection = Connection::getInstance();
		      $resultSet = $this->connection->execute($sql);

		      if(!empty($resultSet))
		      {
		        $result = $this->mapear($resultSet);
                
                if(!is_array($result))
                    $result = array($result);
		      }
		  	}
		    catch(Exception $ex){
		       throw $ex;
		    }
		    return $result;
        }

        public function GetAllActive()
        {
            $sql = "SELECT * FROM " .$this->tableName." WHERE ".$this->tableName.".is_active = 1";

            $result = array();

            try {
              $this->connection = Connection::getInstance();
              $resultSet = $this->connection->execute($sql);

              if(!empty($resultSet))
              {
                $result = $this->mapear($resultSet);
                
                if(!is_array($result))
                    $result = array($result);
              }
            }
            catch(Exception $ex){
               throw $ex;
            }
            return $result;
        }       

        public function changeActive($id, $is_active)
        {
            $query = "UPDATE ".$this->tableName." SET is_active = :is_active WHERE id_movie = :id_movie";
            try
            {
                $parameters["id_movie"] = $id;
                $parameters["is_active"] = $is_active;
                
                $this->connection = Connection::GetInstance();

                $rowCount = $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }

            return $rowCount; 
        }

        public function getMoviesByIdGenre($genre)
        {
            try{
                $result = null;
    
                $query = "SELECT movies.* FROM movies INNER JOIN movies_x_genres ON movies.id_movie = movies_x_genres.id_movie WHERE :id_genre = movies_x_genres.id_genre
                group by movies.id_movie;";
    
                $parameters["id_genre"] = $genre->getIdGenre();
    
                $this->connection = Connection::getInstance();
    
                $resultSet= $this->connection->execute($query, $parameters);
                
           
                if(!empty($resultSet))
            	    {
                      $result = $this->mapear($resultSet);
                      if(!is_array($result))
                        $result = array($result);
            	    }
		  	}
		    catch(Exception $ex){
		       throw $ex;
            }
            
            return $result;
            
        }

        // no borrar este metodo
        public function GetMovieById($idMovie)
        {
            $sql = "SELECT * FROM " . $this->tableName . " WHERE id_movie = :id_movie";
            $result = null;

            try {
                    $parameters["id_movie"] = $idMovie;

                    $this->connection = Connection::getInstance();
                    $resultSet = $this->connection->Execute($sql,$parameters);

                    if(!empty($resultSet))
                    {
                      $result = $this->mapear($resultSet);
                    }
            }
            catch(Exception $ex){
               throw $ex;
            }

            return $result;
        }

        protected function mapear($value)
        {
		    $value = is_array($value) ? $value : [];

		    $resp = array_map(function($p){

		    $movie = new Movie();
            $movie->setIdMovie($p["id_movie"]);
            $movie->setTitle($p["title"]);
            $movie->setPosterPath($p["poster_path"]);
            $movie->setOverview($p["overview"]);
            $movie->setReleaseDate($p["release_date"]);
            $movie->setOriginalLanguage($p["original_language"]);
            $movie->setVoteCounts($p["vote_count"]);
            $movie->setPopularity($p["popularity"]);
            $movie->setRuntime($p["runtime"]);
            $movie->setVoteAverage($p["vote_average"]);
            $movie->setIsActive($p["is_active"]);

		      return $movie;
		    }, $value);
		    return count($resp) > 1 ? $resp : $resp[0];
		}

        public function GetMoviesByGenreApi($idGenre){
            $listMovies = array();
            for($i=1; $i<=3;$i++){///traer mas de una pagina en este caso 60 peliculas
                $movieListJson = file_get_contents('https://api.themoviedb.org/3/discover/movie?api_key=' . TMDB_KEY . '&language=es-MX&page=' . $i .'&with_genres=' . $idGenre);
                $movieListApi = ($movieListJson) ? json_decode($movieListJson, true) : array();

                foreach ($movieListApi["results"] as $movie) {

                    $movieDataJson = file_get_contents('https://api.themoviedb.org/3/movie/' . $movie["id"] . '?api_key=' . TMDB_KEY . '&language=en-US');

                    $movieData = ($movieDataJson) ? json_decode($movieDataJson, true) : array();

                    $newMovie = new Movie();
                    $newMovie->setIdMovie($movie["id"]);
                    $newMovie->setTitle($movie["title"]);
                    $newMovie->setPosterPath($movie["poster_path"]);
                    $newMovie->setOverview($movie["overview"]);
                    $newMovie->setReleaseDate($movie["release_date"]);
                    $newMovie->setOriginalLanguage($movie["original_language"]);
                    $newMovie->setVoteCounts($movie["vote_count"]);
                    $newMovie->setPopularity($movie["popularity"]);
                    $newMovie->setRuntime($movieData["runtime"]);
                    $newMovie->setVoteAverage($movie["vote_average"]);

                    array_push($listMovies,$newMovie);

                    
                }
            }
            return $listMovies;
        }
    }
?>