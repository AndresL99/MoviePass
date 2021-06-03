<?php
    namespace Controllers;

    use DAO\MovieDAOJson as MovieDAOJson;
    use DAO\MovieDAO as MovieDAO;
    use DAO\GenreDAO as GenreDAO;
    use Models\Movie as Movie;

    class MovieController
    {
        private $movieDAOJson;
        private $movieDAO;
        private $genreDAO;

        public function __construct()
        {
            //$this->movieDAOJson = new MovieDAOJson();
            $this->movieDAO = new MovieDAO();
            $this->genreDAO = new GenreDAO();
        }

        public function MoviesView($movieList, $genreList, $message = '')
        {
            require_once(VIEWS_PATH."movie-list.php");
        }

        public function RenewMovies()
        {
            $this->movieDAO->GetMoviesApi();

            $this->ListMovies($message = 'La lista de peliculas se ha actualizado correctamente');
        }

        public function ListMovies($message = '')
        {
            $genreList = $this->genreDAO->GetAll();
            //$genreList = $this->genreDAO->getGenresApi(); // descomentar la primera vez para traer la lista de generos

            $movieList = $this->movieDAO->GetAll();
            //$movieList = $this->movieDAO->GetMoviesApi(); // descomentar la primera vez para traer la lista de peliculas

            $this->MoviesView($movieList, $genreList, $message);
        }

        public function ChangeMovieState($id_movie, $is_active)
        {
            $this->movieDAO->changeActive($id_movie, !$is_active); // hago esto para cambiar true por false y false por true.

            $this->ListMovies();
            require_once(VIEWS_PATH."movie-list.php");
        }

        public function FilterMoviesByGenre($idGenre)
        {
            $genre = $this->genreDAO->getGenreById($idGenre);
            $movieList = $this->movieDAO->getMoviesByIdGenre($genre);
            $this->MoviesView($movieList, $this->genreDAO->GetAll(), "");
        }

    }
?>
