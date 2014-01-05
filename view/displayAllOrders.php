<?php
	$orders = Order::getAllOrders($_SESSION['customerID']);
?>

<h1>Alle deine Bestellungen</h1>
<br/>
<table class="table striped">
	<thead>
		<tr>
			<th class="text-left">BestellNr.</th>
			<th class="text-left">Datum &amp Uhrzeit</th>
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
			<td>€ <?php echo $o->getTotalPrice(); ?></td>
			<td>€ <?php echo $o->getDeliveryCosts(); ?></td>
			<td>€ <?php echo ($o->getTotalPrice() + $o->getDeliveryCosts()); ?></td>
			<td><?php echo $o->getStatusText(); ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>