<?php
    namespace Models;

    use Models\Genre as Genre;
    use Models\Movie as Movie;

    class MovieXGenre
    {
        
        private $id_movie_x_genre;
        private $movie;
        private $genre;

        public function getId_movie_x_genre()
        {
            return $this->id_movie_x_genre;
        }
    
        public function setId_movie_x_genre($id_movie_x_genre)
        {
            $this->id_movie_x_genre = $id_movie_x_genre;
        }
    
        public function getMovie()
        {
            return $this->movie;
        }
    
        public function setMovie(Movie $movie)
        {
            $this->movie = $movie;
        }
    
        public function getGenre()
        {
            return $this->genre;
        }
    
        public function setGenre(Genre $genre)
        {
            $this->genre = $genre;
        }
    }

?>