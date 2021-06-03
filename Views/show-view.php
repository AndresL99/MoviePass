<?php

 //echo '<pre>';
                //var_dump($showsOfMovieTheater);
   //             echo '<pre>';
                //die();

 ?>
<?php require_once(VIEWS_PATH."nav.php");?>
<div class="container">
	<?php if(isset($message)){ ?>
                    <label class="text-white" for=""> <strong> <?php echo $message ?> </strong> </label>
               <?php } ?>
	<form action="<?php echo FRONT_ROOT ?>Show/AddShow" method="POST" class="p-3 mb-1 bg-dark rounded">
		<div class="form-group">
		    <label for="select-cinema" class="text-light"> Seleccionar pelicula: </label>
		    <select class="form-control" id="select-add-cinema" name="cinema" required="">
		    	<option value="" selected="" disabled="">Peliculas activas</option>
		    	<?php foreach ($movieList as $movie){ ?>
          			<option name="movieId" value="<?php echo($movie->getIdMovie()); ?>"> <?php echo ($movie->getTitle()); ?></option>
        		<?php } ?>
		   	</select>
		   	<div id="3" class="mt-3 text-dark row" style="background-color: rgba(0, 0, 0, 0.25);">
		   		<div class="form-group col m-2">
		   			<label for="date" class="text-light"> Añadir fecha: </label>
		   			<input type="date" name="day" value="" min="<?php echo date("Y-m-d"); ?>" max="" required>
		   		</div>
		   		<div class="form-group col m-2">
		   			<label for="time" class="text-light"> Seleccionar horario: </label>
		   			<input type="time" name="hour" min="13:00" max="21:30" required>
		   		</div>
		   		<input type="hidden" name="idMovieTheater" value="<?php echo($movieTheaterSearch->getIdMovieTheater());  ?>">
			</div>

			<button type="submit" name="button" class="mt-2 btn btn-light ml-auto d-block">Agregar</button>
	</form>
</div>
<?php
	if (isset($showsOfMovieTheater[0])) { ?>
		<h3 class="mb-3 mt-2 text-white">Funciones cargadas</h3>
	<?php } if (!isset($showsOfMovieTheater[0])) { ?>
		<h3 class="mb-3 mt-2 text-white">Ninguna función cargada aqui</h3>
	<?php } ?>

	<div class="row mt-3">
	<?php if (isset($showsOfMovieTheater)) {
	 foreach ($showsOfMovieTheater as $show){
	 	if($show->getMovie()->getIsActive() && $show->getState() && $show->getDay() >= date("Y-m-d")){ ?>
	  <div class="col-md-6">
      <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-300 position-relative color-pelis">

        <div class="col p-4 d-flex flex-column position-static text-white">
          <strong class="d-inline-block mb-2 text-primary"> <?php echo ($show->getMovieTheater()->getCinema()->getName() . " - " .$show->getMovieTheater()->getName());?></strong>
          <h3 class="mb-0">Titulo: <?php echo ($show->getMovie()->getTitle());?> </h3>
          <div class="mb-1 text-muted"> Promedio de votos: <?php echo ($show->getMovie()->getVoteAverage());?></div>
          <p class="card-text mb-auto recorta-texto"><strong>Descripcion:</strong> <?php echo ($show->getMovie()->getOverview());?></p>
          <p class="card-text mb-auto"><strong>Popularidad:</strong> <?php echo ($show->getMovie()->getPopularity());?></p>
          <p class="card-text mb-auto"><strong>Duracion:</strong> <?php echo ($show->getMovie()->getRuntime());?> minutos </p>
          <p class="card-text mb-auto"><strong>Dia de la funcion:</strong> <?php echo ($show->getDay());?></p>
          <?php
          $showEndTime = strtotime('+'. $show->getMovie()->getRuntime() .' minute', strtotime($show->getHour()));
          $showEndTime = strtotime('+15 minute', $showEndTime);
          $showEndTime = date ('H:i', $showEndTime);
           ?>
           <p class=""><strong>Hora de la funcion:</strong> <?php echo ($show->getHour() ." - ".	$showEndTime);?> </p>

          <form action="<?php echo FRONT_ROOT ?>Show/DisableShow" method="POST" class="mb-1">
          	<button type="submit" name="button_disabled" value="<?php echo $show->getIdShow(); ?>" class="btn btn-danger btn-block">Dar de baja</button>
      	  </form>
          <a class="btn btn-info" href="<?php echo FRONT_ROOT ?>Show/ShowUpdateShowsView/?id=<?php echo $show->getIdShow(); ?>"> Editar</a>

        </div>

        <div class="col-auto <!--d-none--> d-lg-block">
        <?php echo ('<img src="https://image.tmdb.org/t/p/w185' . $show->getMovie()->getPosterPath() .  '">'); ?>
        </div>



      </div>
    </div>
    <?php }
    }
   }?>
   </div>

   <br>
	<h3 class="col-md-12 pl-0 mb-3 text-white">Funciones inactivas</h3>
   <?php
   //aqui se mostraran las funciones dadas de baja ya sea porque la movie se dio de baja o porque se dio de baja la funcion en si?>
	<div class="row mt-3">
	<?php if (isset($showsOfMovieTheater)) {
	 foreach ($showsOfMovieTheater as $show){
	 	if(!$show->getMovie()->getIsActive() || !$show->getState() || $show->getDay() < date("Y-m-d")){ ?>
	  <div class="col-md-6">
      <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-300 position-relative color-pelis">

        <div class="col p-4 d-flex flex-column position-static text-white">
          <strong class="d-inline-block mb-2 text-primary"> <?php echo ($show->getMovieTheater()->getCinema()->getName() . " - " .$show->getMovieTheater()->getName());?></strong>
          <h3 class="mb-0">Titulo: <?php echo ($show->getMovie()->getTitle());?> </h3>
          <div class="mb-1 text-muted"> Promedio de votos: <?php echo ($show->getMovie()->getVoteAverage());?></div>
          <p class="card-text mb-auto recorta-texto"><strong>Descripcion:</strong> <?php echo ($show->getMovie()->getOverview());?></p>
          <p class="card-text mb-auto"><strong>Popularidad:</strong> <?php echo ($show->getMovie()->getPopularity());?></p>
          <p class="card-text mb-auto"><strong>Duracion:</strong> <?php echo ($show->getMovie()->getRuntime());?> minutos </p>
          <p class="card-text mb-auto"><strong>Dia de la funcion:</strong> <?php echo ($show->getDay());?></p>
          <?php
          $showEndTime = strtotime('+'. $show->getMovie()->getRuntime() .' minute', strtotime($show->getHour()));
          $showEndTime = strtotime('+15 minute', $showEndTime);
          $showEndTime = date ('H:i', $showEndTime);
           ?>
           <p class=""><strong>Hora de la funcion:</strong> <?php echo ($show->getHour() ." - ".	$showEndTime);?> </p>
          <form action="<?php echo FRONT_ROOT ?>Show/EnableShow" method="POST" class="mb-1">
          	<button type="submit" name="button_enable" value="<?php echo $show->getIdShow(); ?>" class="btn btn-success btn-block">Alta</button>
      	  </form>
          <!--<a href="#" class="stretched-link">Continue reading</a>-->
        </div>

        <div class="col-auto <!--d-none--> d-lg-block">
        <?php echo ('<img src="https://image.tmdb.org/t/p/w185' . $show->getMovie()->getPosterPath() .  '">'); ?>
        </div>



      </div>
    </div>
    <?php }
    }
   }?>
  </div>
	<form action="<?php echo FRONT_ROOT ?>Show/BackToMovieTheaterView" method="POST">
    <?php if (isset($movieTheaterSearch)){ ?>
			<input type="hidden" name="id_cinema" value="<?php echo ($movieTheaterSearch->getCinema()->getIdCinema()); ?>">
		<?php } ?>
		<button type="submit" class="btn btn-light d-block" href="">Volver a salas</button>
	</form>
