<?php require_once(VIEWS_PATH."nav.php");?>
<main class="py-5">
     <section id="register" class="mb-5">
          <div class="container">
               <h2 class="mb-4 text-white">Registración</h2>
               <?php if(isset($message)){ ?>
                    <label class="text-white" for=""> <strong> <?php echo $message ?> </strong> </label>
               <?php } ?>
               <label for=""></label>
               <form class="bg-light-alpha p-5" action="<?php echo FRONT_ROOT ?>User/register" method="POST">
                    <div class="row font-weight-bold">
                         <div class="col-lg-3">
                              <div class="form-group">
                                   <label for="">Nombre de usuario</label>
                                   <input type="Text" name="userName" value="" class="form-control" placeholder="Nombre de usuario" required>
                              </div>
                         </div>
                         <div class="col-lg-3">
                              <div class="form-group">
                                   <label for="">Contraseña</label>
                                   <input type="password" name="password" value="" class="form-control" placeholder="Password" required>
                              </div>
                         </div>
                         <div class="col-lg-3">
                              <div class="form-group">
                                   <label for="">Nombre/s</label>
                                   <input type="Text" name="firstName" value="" class="form-control" placeholder="Nombre/s" required>
                              </div>
                         </div>
                         <div class="col-lg-3">
                              <div class="form-group">
                                   <label for="">Apellido/s</label>
                                   <input type="Text" name="lastName" value="" class="form-control" placeholder="Apellido/s" required>
                              </div>
                         </div>
                         <div class="col-lg-3">
                              <div class="form-group">
                                   <label for="">E-mail</label>
                                   <input type="email" name="email" value="" class="form-control" placeholder="E-mail" required>
                              </div>
                         </div>
                    </div>
                    <button type="submit" name="button" class="btn btn-dark ml-auto d-block">Finalizar</button>
               </form>
          </div>
     </section>
</main>
