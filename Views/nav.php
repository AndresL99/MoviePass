
<nav class="navbar navbar-expand-lg  navbar-dark bg-dark">
     <span class="navbar-text">
          <a href="<?php echo FRONT_ROOT ?>" style="text-decoration: none;"><strong> Movie </strong> Pass Home</a>
     </span>
     <ul class="navbar-nav ml-auto">
          <li class="nav-item">
               <a class="nav-link" href="<?php echo FRONT_ROOT?>Home/logout">Logout</a>
          </li>
     </ul>
</nav>

<div id="sidebar" class="sidebar">
  <a href="#" class="boton-cerrar" onclick="ocultar()">&times;</a>
  <ul class="menu">
    <li class="text-white">Opciones</li>
    </br>
    <?php
          if(isset($_SESSION['loggedUser']))
          {
            if($_SESSION['loggedUser']->getRole() == 1){
               ?>
               <li><a href="<?php echo FRONT_ROOT ?>Cinema/AddCineView">Agregar cine</a></li>
               <li><a href="<?php echo FRONT_ROOT ?>Cinema/ListCinema">Listar cines</a></li>
               <li><a href="<?php echo FRONT_ROOT ?>Ticket/ticketSoldReminderView">Tickets Vendidos</a></li>
               <li><a href="<?php echo FRONT_ROOT ?>User/AdminView">Funciones</a></li>
               <?php
             }else { ?>
               <a href="<?php echo FRONT_ROOT?>Show/userView">Funciones</a>
            <?php  }
          }
     ?>
     <?php
          if(!isset($_SESSION['loggedUser']))
          {
               ?>
               <li class="text-white">Para las demas opciones se debe registrar o Iniciar sesion</li>
          <?php
          }
          ?>
          <li><a href="<?php echo FRONT_ROOT ?>Movie/ListMovies">Lista de peliculas</a></li>
    </ul>
  </div>
    <div id="contenido">
    <h1 class="intro">Menu MoviePass</h1>
        <a id="abrir" class="abrir-cerrar" href="javascript:void(0)" onclick="mostrar()">Abrir menu</a><a id="cerrar" class="abrir-cerrar" href="#" onclick="ocultar()">Cerrar menu</a>
  </div>
