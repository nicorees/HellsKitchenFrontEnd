<?php

	/**
	 * Diese View lädt und zeigt den Warenkorb des Kunden an
	 * @author Andreas Nenning, Christian Vogg, Nicholas Rees, Steffen Schenk
	 */

	$cart = Order::getCart($_SESSION['customerID']);
	
	if(empty($cart->getOrderLines())) {
		
		echo "
			<p class='readable-text'>Dein Warenkorb ist derzeit leer</p>
			<p>
				Durchst&oumlbere doch unser  
				<a href='.?p=displayAllProducts'>Angebot an Pizzen</a>
				, dort wirst du sicherlich fündig!
			</p>		
		";
		
		exit;
	}
?>

<h1>Warenkorb</h1>
<br/>
<table class="table striped">
	<thead>
		<tr>
			<th class="text-left"></th>
			<th class="text-left">Pizza Name</th>
			<th class="text-left">Preis</th>
			<th class="text-left">Anzahl</th>
			<th class="text-left"></th>
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
				<td>
					<a href='<?php echo '?c=cartRemove&pid=' . $p->getId() ; ?>' class='fg-crimson'>
        				<i class="icon-cancel-2"></i>
					</a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<a href="?p=checkout1" class="button" >Bestellen</a>