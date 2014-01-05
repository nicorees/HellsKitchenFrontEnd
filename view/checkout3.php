<?php
	$cart = Order::getCart($_SESSION['customerID']);
	
	if(empty($cart->getOrderLines())) {
		header("Location: .");
	}

	$orderTime = (empty($_SESSION['orderTime']) ? date('m/d/Y h:i:s A') : $_SESSION['orderTime'] );
	$pickup = (isset($_POST['pickup']) ? TRUE : FALSE );
	$addressID = $_POST['dropdown'];

	$cart->prepareCheckout($orderTime,$pickup,$addressID);
?>

<h2>Schritt3: Zusammenfassung</h2>
<br/>

<table class="table striped">
	<thead>
		<tr>
			<th class="text-left"></th>
			<th class="text-left">Pizza Name</th>
			<th class="text-left">Preis</th>
			<th class="text-left">Anzahl</th>
			<th class="text-left">Gesamt</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($cart->getOrderLines() as $orderline): ?>
			<?php $p = new Product($orderline->getProductID()); ?>
			<tr>
				<td>
					<img width="100px" height="100px" src=
						<?php
							if (file_exists(DOC_ROOT . "assets/img/pizza/" . $p->getID() . ".jpg"))
								echo "assets/img/pizza/" . $p->getID() . ".jpg";
							else
								echo "assets/img/pizza/default.jpg";
						?>
					>
				</td>
				<td><?php echo $p->getName(); ?></td>
				<td>€ <?php echo $orderline->getPrice(); ?></td>
				<td><?php echo $orderline->getQuantity(); ?></td>
				<td>€ <?php echo ($orderline->getPrice()*$orderline->getQuantity()) ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<h3>Zwischensumme: € <?php echo $cart->getTotalPrice(); ?></h3>
<h3>Lieferkosten: € <?php echo $cart->getDeliveryCosts(); ?></h3>
<h3>Gesamtsumme: € <?php echo ($cart->getTotalPrice() + $cart->getDeliveryCosts()); ?></h3>
<br/>

<a href="?c=checkout" class="button">Bestellung abschließen</a>
