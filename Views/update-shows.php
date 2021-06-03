<div class="container">
     <h2 class="mb-0 text-white">Editar función</h2>
     <label for=""></label>
     <form class="bg-light-alpha p-5" action="<?php echo FRONT_ROOT ?>Show/UpdateShow" method="POST">
          <div class="row font-weight-bold">
               <input type="hidden" name="id_show" value="<?php echo $showSearch->getIdShow(); ?>">
               <div class="col-lg-3">
                    <div class="form-group">
                         <label for="">Día de la función</label>
                         <input type="date" name="day" value="<?php echo $showSearch->getDay(); ?>" min="<?php echo date("Y-m-d"); ?>" class="form-control" placeholder="Día" required>
                    </div>
               </div>
               <div class="col-lg-3">
                    <div class="form-group">
                         <label for="">Hora de la función</label>
                         <input type="time" name="hour" value="<?php echo $showSearch->getHour(); ?>" class="form-control" placeholder="Horario" min="13:00" max="21:30" required>
                    </div>
               </div>

               <input type="hidden" name="id_movie" value="<?php echo $showSearch->getMovie()->getIdMovie(); ?>">
               <input type="hidden" name="id_movie_theater" value="<?php echo $showSearch->getMovieTheater()->getIdMovieTheater(); ?>">
          </div>
          <button type="submit" name="button" class="btn btn-dark ml-auto d-block">Editar</button>
     </form>
</div>
