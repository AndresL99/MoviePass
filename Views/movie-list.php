<?php require_once(VIEWS_PATH."nav.php");?>
<div class="container">
  <?php if(isset($message)){ ?>
          <div class="text-white ml-3 mb-1" for=""> <strong> <?php echo $message ?> </strong> </div>
  <?php } ?>
  <label class="col-lg-3 mb-2">
  <form action="<?php echo FRONT_ROOT ?>Movie/FilterMoviesByGenre" method="POST">
    <select name="career" class="form-control form-control-ml">
        <option selected="" value="" disabled="" required>Seleccione el genero</option>
        <?php foreach ($genreList as $genre){ ?>
          <option name="movieGenre" value="<?php echo($genre->getIDGenre()); ?>"> <?php echo ($genre->getName()); ?></option>
        <?php } ?>
    </select>
    <button class="btn btn-primary btn-block btn-lg mt-4" name="button" type="submit">Filtrar por genero</button>
</form>
  </label>
  <!--<a class="btn btn-info mb-2" href="<?php echo FRONT_ROOT ?>Movie/RenewMovies">Renovar lista peliculas</a> -->


  <?php if (isset($movieList)){
    foreach ($movieList as $movie) { ?>
    <form action="<?php echo FRONT_ROOT ?>Movie/ChangeMovieState" method="POST">
    <div class="col-md-12">
      <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-300 position-relative color-pelis">

        <div class="col p-4 d-flex flex-column position-static text-white">
          <strong class="d-inline-block mb-2 text-primary"> Dia de estreno: <?php echo ($movie->getReleaseDate());?></strong>
          <h3 class="mb-0">Titulo: <?php echo ($movie->getTitle());?> </h3>
          <div class="mb-1 text-muted"> Promedio de votos: <?php echo ($movie->getVoteAverage());?></div>
          <p class="card-text mb-auto"><strong>Descripcion:</strong> <?php echo ($movie->getOverview());?></p>
          <p class="card-text mb-auto"><strong>Popularidad:</strong> <?php echo ($movie->getPopularity());?></p>
          <p class="card-text mb-auto"><strong>Duracion:</strong> <?php echo ($movie->getRuntime());?> minutos </p>
          <input type="hidden" name="id_movie" value="<?php echo $movie->getIdMovie(); ?>">
          <input type="hidden" name="is_active" value="<?php echo $movie->getIsActive(); ?>">
          <!--<a href="#" class="stretched-link">Continue reading</a>-->
          <div style="width: 25%; float: right"> <?php
          if (isset($_SESSION["loggedUser"]) && $_SESSION["loggedUser"]->getRole() == 1){
            if ($movie->getIsActive()){ ?>
              <button class="btn btn-danger btn-block btn-lg mt-1"  name="button" type="submit">Desactivar</button>
            <?php } else { ?>
              <button class="btn btn-primary btn-block btn-lg mt-4" name="button" type="submit">Activar</button>
            <?php }
            }?>

          </div>

        </div>

        <div class="col-auto <!--d-none--> d-lg-block">
        <?php echo ('<img src="https://image.tmdb.org/t/p/w185' . $movie->getPosterPath() .  '">'); ?>
        </div>

      </div>
    </div>
    </form>
  <?php }
  } elseif (!isset($movieList)){ ?>
      <h3 class="mb-0 text-white mt-4 ml-2">No hay ninguna película cargada para el género seleccionado.</h3>
  <?php

  } ?>


</div>
