<?php

	/**
	 * Diese View zeigt dem User alle seine Bestellungen an
	 * @author Nicholas Rees
	 */

	$orders = Order::getAllOrders($_SESSION['customerID']);

	if(empty($orders))
		header("Location: .?e=noOrders");

?>

<h1>Alle deine Bestellungen</h1>
<br/>
<table class="table striped">
	<thead>
		<tr>
			<th class="text-left">BestellNr.</th>
			<th class="text-left">Datum &amp; Uhrzeit</th>
			<th class="text-left">Adresse / Selbstabholung</th>
			<th class="text-left">Warenwert</th>
			<th class="text-left">Lieferkosten</th>
			<th class="text-left">Gesamtsumme</th>
			<th class="text-left">Status der Bestellung</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($orders as $o): ?>
		<tr>
			<td><?php echo $o->getOrderID(); ?></td>
			<td><?php echo date('d.m.Y H:i',strtotime($o->getTime())); ?></td>
			<td>
				<?php 
					if($o->getPickup())
						echo "Selbstabholung";
					else {
						$address = new Address($o->getAddressID());
						$street = $address->getStreet();
						$city = $address->getCity();
						echo "$street, $city";
					}
				?>
			</td>
			<td>€ <?php echo number_format($o->getTotalPrice(),2,'.',''); ?></td>
			<td>€ <?php echo number_format($o->getDeliveryCosts(),2,'.',''); ?></td>
			<td>€ <?php echo number_format(($o->getTotalPrice() + $o->getDeliveryCosts()),2,'.',''); ?></td>
			<td><?php echo $o->getStatusText(); ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>