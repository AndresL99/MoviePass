<?php require_once(VIEWS_PATH."nav.php");?>
<main class="py-5">
     <div class="container">
               <?php if(isset($message)){ ?>
                    <label class="text-white" for=""> <strong> <?php echo $message ?> </strong> </label>
               <?php } ?>
                    <h2 class="mb-4 text-white">Agregando salas a cine:</h2>
                    <table class="table bg-light-alpha">
                         <thead>
                              <th>Nombre</th>
                              <th>Direccion</th>
                         </thead>
                         <tbody>
                              <tr>
                                   <td><?php echo $cinemaSearch->getName(); ?></td>
                                   <td><?php echo $cinemaSearch->getAddress(); ?></td>
                              </tr>
                         </tbody>
                    </table>
               </div>
     <section id="listado" class="mb-5">
          <div class="container">
               <h2 class="mb-0 text-white">Agregar Sala</h2>
               <label for=""></label>
               <form class="bg-light-alpha p-5" action="<?php echo FRONT_ROOT ?>MovieTheater/AddMovieTheater" method="POST">
                    <div class="row font-weight-bold">
                         <div class="col-lg-3">
                              <div class="form-group">
                                   <label for="">Nombre de la sala</label>
                                   <input type="text" name="name" value="" class="form-control" placeholder="Nombre" required>
                              </div>
                         </div>
                         <div class="col-lg-3">
                              <div class="form-group">
                                   <label for="">Capacidad</label>
                                   <input type="number" name="total_capacity" value="" class="form-control" placeholder="Capacidad" required>
                              </div>
                         </div>
                         <div class="col-lg-3">
                              <div class="form-group">
                                   <label for="">Precio</label>
                                   <input type="number" name="price" value="" class="form-control" placeholder="Precio" required>
                              </div>
                         </div>
                         <input type="hidden" name="id_cinema" value="<?php echo $cinemaSearch->getIdCinema(); ?>">
                    </div>
                    <button type="submit" name="button" class="btn btn-dark ml-auto d-block">Agregar</button>
               </form>
          </div>
          <form action="<?php echo FRONT_ROOT ?>MovieTheater/RemoveMovieTheater" method="POST">
               <div class="container">
                    <h2 class="mb-4 mt-4 text-white">Lista de salas</h2>
                    <table class="table bg-light-alpha">
                         <thead>
                              <th>Nombre</th>
                              <th>Capacidad actual</th>
                              <th>Precio</th>
                              <th>Capacidad total</th>
                              <th colspan="2">Acciones</th>
                         </thead>
                         <tbody>
                              <?php 
                           if($movieTheaterList):
                              if ($movieTheaterList[0]):
                               foreach ($movieTheaterList as $movieTheater) { 
                                   if($movieTheater->getState()){  ?>
                                   <tr>
                                        <td><?php echo $movieTheater->getName(); ?></td>
                                        <td><?php echo $movieTheater->getCurrentCapacity(); ?></td>
                                        <td><?php echo $movieTheater->getPrice(); ?></td>
                                        <td><?php echo $movieTheater->getTotalCapacity(); ?></td>
                                        <td> <button type="submit" name="button_delete" value="<?php echo $movieTheater->getIdMovieTheater(); ?>" class="btn btn-danger">Dar de baja</button> </td>
                                        <td><a class="btn btn-info" href="<?php echo FRONT_ROOT ?>MovieTheater/ShowUpdateMovieTheaterView/?id=<?php echo $movieTheater->getIdMovieTheater(); ?>"> Editar</a></td>
                                   </tr>
                              <?php }
                              } 
                              endif;
                            endif;?>
                         </tbody>
                    </table>
               </div>
          </form>
          <form action="<?php echo FRONT_ROOT ?>Show/DisplayShowView" method="POST">
               <div class="container">
                    <h3 class="mb-4 text-white">Ingrese el nombre de la sala en la que quiere ingresar una funcion</h3>
                    <div class="col-lg-3">
                         <div class="form-group">
                              <input type="text" name="name" value="" class="form-control" placeholder="Nombre de sala" required>

                              <input type="hidden" name="id_cinema" value="<?php echo $cinemaSearch->getIdCinema(); ?>">
                         <input type="submit" name="button" value="Agregar funcion" class="btn btn-primary">
                         </div>
                    </div>
               </div>
          </form>
          <form action="<?php echo FRONT_ROOT ?>MovieTheater/EnableMovieTheater" method="POST">
               <div class="container">
                    <h2 class="mb-4 text-white">Lista de salas inactivas</h2>
                    <table class="table bg-light-alpha">
                         <thead>
                              <th>Nombre</th>
                              <th>Capacidad actual</th>
                              <th>Precio</th>
                              <th>Capacidad total</th>
                              <th >Accion</th>
                         </thead>
                         <tbody>
                           <?php 
                            if($movieTheaterList):
                              if ($movieTheaterList[0]):
                               foreach ($movieTheaterList as $movieTheater) { 
                                   if(!$movieTheater->getState()){  ?>
                                   <tr>
                                        <td><?php echo $movieTheater->getName(); ?></td>
                                        <td><?php echo $movieTheater->getCurrentCapacity(); ?></td>
                                        <td><?php echo $movieTheater->getPrice(); ?></td>
                                        <td><?php echo $movieTheater->getTotalCapacity(); ?></td>
                                        <td> <button type="submit" name="button_delete" value="<?php echo $movieTheater->getIdMovieTheater(); ?>" class="btn btn-success">Alta</button> </td>
                                   </tr>
                              <?php }
                              } 
                              endif;
                            endif;?>
                         </tbody>
                    </table>
                    <a class="btn btn btn-light d-block btn-sm" href="<?php echo FRONT_ROOT ?>Cinema/ListCinema">Volver</a>
               </div>
          </form>
     </section>
</main>

