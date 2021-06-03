<?php
    namespace DAO;

    use Models\MovieTheater as MovieTheater;

    interface IMovieTheaterDAO
    {
        function Add(MovieTheater $movieTheater);
        function Update(MovieTheater $movieTheater);
    }
?>