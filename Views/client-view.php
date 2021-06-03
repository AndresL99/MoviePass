<?php
require_once(VIEWS_PATH."nav-client.php");
?>
<div class="container">
          <section id="listado" class="mb-5">
               <h2 class="mb-4 text-white">Bienvenido! Gracias por elegirnos</h2>
               <?php if(isset($message)){ ?>
                    <label class="text-white" for=""> <strong> <?php echo $message ?> </strong> </label>
               <?php } ?>
               <label for=""></label>
               <h3 class="mb-4 text-white">Funciones actualmente en cartelera en MoviePass!</h3>
               <?php if(isset($showsActive))
               {
               ?>
               <form action="<?php echo FRONT_ROOT ?>User/FilterShowsByGenre" method="POST">
	               <select name="career" class="form-control form-control-ml">
		               <option selected="" value="" disabled="">Seleccione el genero</option>
		               <?php foreach ($genreList as $genre){ ?>
		               	<option name="movieGenre" value="<?php echo($genre->getIDGenre()); ?>"> <?php echo ($genre->getName()); ?></option>
		               <?php } ?>
	               </select>
               <button class="btn btn-primary btn-block btn-lg mt-4" name="button" type="submit">Filtrar por genero</button>
               </form>
               <div class="row mt-3">
                 <?php   foreach ($showsActive as $show) { ?>
                 <div class="col-md-6">
                 <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-300 position-relative color-pelis">
                    <div class="col p-4 d-flex flex-column position-static text-white color-pelis" >
                         <strong class="d-inline-block mb-2 text-primary"> Dia de estreno: <?php echo ($show->getDay());?></strong>
                         <h3 class="mb-0">Hora: <?php echo ($show->getHour());?> </h3>
                         <div class="mb-1 text-muted"> Movie: <?php echo ($show->getMovie()->getTitle());?></div>
                          <p class="card-text mb-auto"><strong>Cine:</strong> <?php echo ($show->getMovieTheater()->getCinema()->getName() . " - " .$show->getMovieTheater()->getName());?></p>
                          <a class="btn btn-info" href="<?php echo FRONT_ROOT ?>Show/buyTicketView/?id_show=<?php echo $show->getIdShow(); ?>">Comprar</a>
                    </div>
                    <div class="col-auto d-lg-block">
                    <?php echo ('<img src="https://image.tmdb.org/t/p/w185' . $show->getMovie()->getPosterPath() .  '">'); ?>
                    </div>
                    </div>
               </div>
               <?php } ?>
               </div>
               <?php } else if(!isset($showsActive))
                    { ?>
                    <h3 class="text-white">No hay funciones cargadas!</h3>
                 <?php } ?>
          </section>
</div>
