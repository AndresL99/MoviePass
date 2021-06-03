<?php require_once(VIEWS_PATH."nav.php");?>
<main class="py-5">
     <section id="listado" class="mb-5">
          <div class="container">
               <h2 class="mb-4 text-white">Editar cine</h2>

               <form class="bg-light-alpha p-5" action="<?php echo FRONT_ROOT ?>Cinema/UpdateCinema" method="POST">
                    <input type="hidden" name="idCinema" value="<?php echo $cinemaSearch->getIdCinema(); ?>">
                    <div class="row">
                         <div class="col-lg-3">
                              <div class="form-group">
                                   <label for="">Nombre del cine</label>
                                   <input type="Text" name="name" value="<?php echo $cinemaSearch->getName(); ?>" class="form-control" placeholder="Nombre" required>
                              </div>
                         </div>
                         <div class="col-lg-3">
                              <div class="form-group">
                                   <label for="">Direccion</label>
                                   <input type="Text" name="address" value="<?php echo $cinemaSearch->getAddress(); ?>" class="form-control" placeholder="Direccion" required>
                              </div>
                         </div>
                    </div>
                    <button type="submit" name="button" class="btn btn-dark ml-auto d-block">Editar</button>
               </form>
          </div>
     </section>
</main>

