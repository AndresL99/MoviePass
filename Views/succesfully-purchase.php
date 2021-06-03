<?php require_once(VIEWS_PATH."nav.php"); ?>
<body>
    <header>
        <div class="movie-h account-head">
            <h3 class="section-title text-s">
                <i class="icon ion-md-happy"></i>
            </h3>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="text-white">

                <i class="icon ion-md-checkmark-circle"></i>
                <h2>Compra realizada con éxito</h2>

                <div class="purchase-details">
                    <!--<h3>ID Purchase: <?php //echo $purchase->getId(); ?></h3>-->
                    <h3>Total: $<?php echo $purchase->getTotal(); ?> </h3>
                    <h3>Cantidad de Tickets: <?php echo $purchase->getTicketQuantity(); ?> </h3>
                    <h3>Descuento: $<?php echo $purchase->getDiscount(); ?></h3>
                    <h3>Dia de la compra: <?php  echo $purchase->getDate(); ?></h3>
                </div>

            </div>
        </div>
    </main>
