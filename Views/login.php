<?php require_once(VIEWS_PATH."nav.php");?>
<main class="d-flex align-items-center justify-content-center height-50">
     <div class="content">
          <header class="text-center text-white">
               <h2>Bienvenido a MoviePass</h2>
          </header>
          <form action="<?php echo FRONT_ROOT ?>User/login" method="POST" class="login-form bg-light-alpha p-5 text-dark font-weight-bold">
               <div class="form-group">
               <?php if(isset($message)){ ?>
                    <label for=""> <strong> <?php echo $message ?></strong> </label>
               <?php } ?>
               </br>
                    <label for="">Nombre de usuario</label>
                    <input type="text" name="userName" class="form-control form-control-lg" placeholder="Ingrese nombre de usuario">
               </div>
               <div class="form-group">
                    <label for="">Contraseña</label>
                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Ingrese contraseña">
               </div>

               <button class="btn btn-primary btn-block btn-lg mt-4" type="submit">Iniciar sesión</button>
          </form>
          <form action="<?php echo FRONT_ROOT?>User/register" method="POST" class="login-form bg-light-alpha p-5 text-dark font-weight-bold">
          <div class="form-group">
              <a class="btn btn-danger btn-block btn-lg " href="<?php echo FRONT_ROOT ?>Home/register">Registrar usuario</a>
          </div>
     </form>
     </div>
</main>
