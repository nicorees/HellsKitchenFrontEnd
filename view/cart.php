<?php
$cart = Order::getCart($_SESSION['customerID']);
?>

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
					<td><?php echo "IMG"; ?></td>
					<td><?php echo $p->getName(); ?></td>
					<td><?php echo $orderline->getPrice(); ?></td>
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