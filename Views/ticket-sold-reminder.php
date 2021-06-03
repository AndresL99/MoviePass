<?php require_once(VIEWS_PATH."nav.php"); ?>
<div class="container">
    <h2 class="mb-4 mt-4 text-white">Tickets vendidos y remanentes</h2>
					<div class="filter-search">
            <div class="col-lg-3 pl-0">
                 <div class="form-group">
                      <input type="text" name="name" value="" class="form-control" placeholder="Filtrar por..." required>
                      <button type="submit" name="button" value="" class="btn btn-danger">Filtrar</button>
                 </div>
            </div>
					</div>
					<div class="filter-options text-white">
						<!--<label>Filter by:</label>
						<input type="radio" name="filter" id="input-cinema" value="cinema" checked> Cine
						<input type="radio" name="filter" id="input-movie" value="movie"> Pelicula
						<input type="radio" name="filter" id="input-date" value="date"> Dia-->
					</div>

        <table class="table bg-light-alpha">
                <thead>
                    <tr>
                        <th>Cine</th>
                        <th>Sala</th>
                        <th>Dia</th>
                        <th>Hora</th>
                        <th>Pelicula</th>
                        <th>Tickets vendidos</th>
                        <th>Remanentes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $ticket){ ?>
                        <tr>
                            <td><?php echo ($ticket->getShow()->getMovieTheater()->getCinema()->getName()); ?></td>
                            <td><?php echo ($ticket->getShow()->getMovieTheater()->getName()); ?></td>
                            <td><?php echo ($ticket->getShow()->getDay()); ?></td>
                            <td><?php echo ($ticket->getShow()->getHour()); ?></td>
                            <td><?php echo ($ticket->getShow()->getMovie()->getTitle()); ?></td>
                            <td><?php echo ($this->getSoldTicketsByShowId($ticket->getShow()->getIdShow())); ?></td>
                            <td><?php $this->getTickesRemainder($ticket->getShow()->getIdShow()); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
         </table>
         <h3 class="mt-5 text-white text-center">Informe de ventas</h3>
         <table class="table bg-light-alpha">
                 <thead>
                     <tr>
                         <th>Cine</th>
                         <th>Cantidad vendidas - pesos-</th>
                    
                     </tr>
                 </thead>
                 <tbody>
                     <?php foreach ($cinemas as $cine): ?>
                         <tr>
                             <td><?php echo($cine->getName()); ?></td>
                             <td><?php echo ($this->totalSoldByCinema($cine->getIdCinema())); ?></td>
                         </tr>
                     <?php endforeach; ?>
                 </tbody>
          </table>
          <table class="table bg-light-alpha">
                  <thead>
                      <tr>
                          <th>Pelicula</th>
                          <th>Cantidad vendidas - pesos</th>

                      </tr>
                  </thead>
                  <tbody>
                      <?php foreach ($movies as $movie): ?>
                          <tr>
                              <td><?php echo ($movie->getTitle()); ?></td>
                              <td><?php echo ($this->totalSoldByMovie($movie->getIdMovie())); ?></td>
                          </tr>
                      <?php endforeach; ?>
                  </tbody>
           </table>
    </div>
