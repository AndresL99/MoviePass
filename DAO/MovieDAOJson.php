<?php
    namespace DAO;

    use DAO\IDAO as IDAO;
    use Models\Movie as Movie;

    class MovieDAOJson implements IDAO
    {
        private $movieList = array();

        public function GetMoviesApi()
        {

            $this->DestroyJson();

        	$movieListJson = file_get_contents('https://api.themoviedb.org/3/movie/now_playing?api_key=' . TMDB_KEY . '&language=es-MX&page=1');

			$movieListApi = ($movieListJson) ? json_decode($movieListJson, true) : array();

            foreach ($movieListApi["results"] as $movie) {

                //hago esta request a la api para obtener el tiempo de duracion de la pelicula
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
                $newMovie->setRuntime($movieData["runtime"]); //aqui le asigno el tiempo con la segunda request
                $newMovie->setVoteAverage($movie["vote_average"]);

                $this->Add($newMovie);
            }

        }

        public function Add(Movie $movie)
        {

            $this->RetrieveData();

            //$movie->setIdMovie($this->GetNextId()); 
            
            array_push($this->movieList, $movie);

            $this->SaveData();

        }

        public function Remove($id)
        {

        }

        public function GetAll()
        {
            $this->RetrieveData();

            return $this->movieList;
        }

        private function DestroyJson()
        {
            file_put_contents('Data/movies.json', $jsonContent = array());
        }

        private function SaveData()
        {
            $arrayToEncode = array();

            foreach($this->movieList as $movie)
            {
                $valuesArray["id"] = $movie->getIdmovie();
                //$valuesArray["id_api"] = $movie->getIdApimovie();
                $valuesArray["title"] = $movie->getTitle();
                $valuesArray["poster_path"] = $movie->getPosterPath();
                $valuesArray["overview"] = $movie->getOverview();
                $valuesArray["release_date"] = $movie->getReleaseDate();
                $valuesArray["genre_ids"] = $movie->getGenreIds();
                $valuesArray["original_language"] = $movie->getOriginalLanguage();
                $valuesArray["vote_count"] = $movie->getVoteCounts();
                $valuesArray["popularity"] = $movie->getPopularity();
                $valuesArray["runtime"] = $movie->getRuntime(); 
                $valuesArray["vote_average"] = $movie->getVoteAverage();

                array_push($arrayToEncode, $valuesArray);
            }

            $jsonContent = json_encode($arrayToEncode, JSON_PRETTY_PRINT);
            
            file_put_contents('Data/movies.json', $jsonContent);
        }

        private function RetrieveData()
        {
            $this->movieList = array();

            if(file_exists('Data/movies.json'))
            {
                $jsonContent = file_get_contents('Data/movies.json');

                $arrayToDecode = ($jsonContent) ? json_decode($jsonContent, true) : array();

                foreach($arrayToDecode as $valuesArray)
                {
                    $movie = new Movie();
                    $movie->setIdMovie($valuesArray["id"]);
                    //$movie->setIdApiMovie($valuesArray["id_api"]);
                    $movie->setTitle($valuesArray["title"]);
                    $movie->setPosterPath($valuesArray["poster_path"]);
                    $movie->setOverview($valuesArray["overview"]);
                    $movie->setReleaseDate($valuesArray["release_date"]);
                    $movie->setGenreIds($valuesArray["genre_ids"]);
                    $movie->setOriginalLanguage($valuesArray["original_language"]);
                    $movie->setVoteCounts($valuesArray["vote_count"]);
                    $movie->setPopularity($valuesArray["popularity"]);
                    $movie->setRuntime($valuesArray["runtime"]);
                    $movie->setVoteAverage($valuesArray["vote_average"]);

                    array_push($this->movieList, $movie);
                }
            }
        }

        private function GetNextId()
        {
            $id = 0;

            foreach($this->movieList as $movie)
            {
                $id = ($movie->getIdMovie() > $id) ? $movie->getIdMovie() : $id;
            }

            return $id + 1;
        }
    }
?>