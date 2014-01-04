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