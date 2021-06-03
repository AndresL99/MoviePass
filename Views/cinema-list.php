<?php require_once(VIEWS_PATH."nav.php");?>
<main class="py-5">
     <section id="listado" class="mb-5">
          <form action="<?php echo FRONT_ROOT ?>Cinema/RemoveCinema" method="POST">
               <div class="container">
               <?php if(isset($message)){ ?>
                    <label class="text-white" for=""> <strong> <?php echo $message ?> </strong> </label>
               <?php } ?>
                    <h2 class="mb-4 text-white">Lista de cines</h2>
                    <table class="table bg-light-alpha">
                         <thead>
                              <th>Nombre</th>
                              <th>Direccion</th>
                              <th>Salones</th>
                              <th colspan="2">Acciones</th>
                         </thead>
                         <tbody>
                              <?php foreach ($listCinema as $cinema) { 
                                   if($cinema->getState()){  ?>
                                   <tr>
                                        <td><?php echo $cinema->getName(); ?></td>
                                        <td><?php echo $cinema->getAddress(); ?></td>
                                        <td><a type="submit" name="button_saloon" value="" class="btn btn-dark" href="<?php echo FRONT_ROOT ?>MovieTheater/ShowMovieTheaterView/?id=<?php echo $cinema->getIdCinema(); ?>">Salon</a> </td>
                                        <td> <button type="submit" name="button_delete" value="<?php echo $cinema->getIdCinema(); ?>" class="btn btn-danger">Dar de baja</button> </td>
                                        <td><a class="btn btn-info" href="<?php echo FRONT_ROOT ?>Cinema/ShowUpdateCinemaView/?id=<?php echo $cinema->getIdCinema(); ?>"> Editar</a></td>
                                   </tr>
                              <?php }
                              } ?>
                         </tbody>
                    </table>
               </div>
          </form>
          <form action="<?php echo FRONT_ROOT ?>Cinema/EnableCinema" method="POST">
               <div class="container">
                    <h2 class="mb-4 text-white">Lista de cines inactivos</h2>
                    <table class="table bg-light-alpha">
                         <thead>
                              <th>Nombre</th>
                              <th>Direccion</th>
                              <th>Accion</th>
                         </thead>
                         <tbody>
                              <?php foreach ($listCinema as $cinema) { 
                                   if(!$cinema->getState()){  ?>
                                   <tr>
                                        <td><?php echo $cinema->getName(); ?></td>
                                        <td><?php echo $cinema->getAddress(); ?></td>
                                        <td> <button type="submit" name="button_delete" value="<?php echo $cinema->getIdCinema(); ?>" class="btn btn-success">Alta</button> </td>
                                   </tr>
                              <?php }
                              } ?>
                         </tbody>
                    </table>
               </div>
          </form>
     </section>
     
     <!-- no eliminar, puede servir a futuro como otro editar -->
     <!--<section id="eliminar">
          <div class="container">
               <form action="<?php //echo FRONT_ROOT ?>Cinema/ShowUpdateCinemaView" method="POST" class="form-inline bg-light-alpha p-3">
                    <div class="form-group text-white">
                         <label for="">Ingrese nombre del cine a editar</label>
                         <input type="text" name="name" value="" class="form-control ml-3" required="">
                    </div>
                    <button type="submit" name="button" class="btn btn-dark ml-3">Editar</button>
               </form>
          </div>
     </section> -->

</main>
