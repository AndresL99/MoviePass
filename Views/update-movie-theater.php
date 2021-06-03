<?php require_once(VIEWS_PATH."nav.php");?>
<div class="container">
     <h2 class="mb-0 text-white">Editar Sala</h2>
     <label for=""></label>
     <form class="bg-light-alpha p-5" action="<?php echo FRONT_ROOT ?>MovieTheater/UpdateMovieTheater" method="POST">
          <div class="row font-weight-bold">
               <input type="hidden" name="id_movie_theater" value="<?php echo $movieTheaterSearch->getIdMovieTheater(); ?>">
               <div class="col-lg-3">
                    <div class="form-group">
                         <label for="">Nombre de la sala</label>
                         <input type="text" name="name" value="<?php echo $movieTheaterSearch->getName(); ?>" class="form-control" placeholder="Nombre" required>
                    </div>
               </div>
               <div class="col-lg-3">
                    <div class="form-group">
                         <label for="">Capacidad</label>
                         <input type="number" name="total_capacity" value="<?php echo $movieTheaterSearch->getTotalCapacity(); ?>" class="form-control" placeholder="Capacidad" required>
                    </div>
               </div>
               <div class="col-lg-3">
                    <div class="form-group">
                         <label for="">Precio</label>
                         <input type="number" name="price" value="<?php echo $movieTheaterSearch->getPrice(); ?>" class="form-control" placeholder="Precio" required>
                    </div>
               </div>

               <input type="hidden" name="id_cinema" value="<?php echo $movieTheaterSearch->getCinema()->getIdCinema(); ?>">
          </div>
          <button type="submit" name="button" class="btn btn-dark ml-auto d-block">Editar</button>
     </form>
</div>
