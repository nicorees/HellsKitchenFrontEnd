<?php
$customer = new Customer($_SESSION["customerID"]);
// fehlt noch: letztes bestelldatum der privaten pizza anzeigen --> evtl model ORDER einfügen?
$products = Product::getCustomerProducts($customer->getCustomerID());

if(empty($products)){

	echo "<span class='readable-text'>
			Du hast noch keine eigene Pizza erstellt, 
			aber du kannst jederzeit mit dem
			<a href='.?p=configurePizza'>PizzaKonfigurator</a> 
			eine zu kreieren!
		</span>";
}
	else {
?>

			<h1>Deine Pizzen</h1>
			<br/>

			<form action="?c=cartAdd" method="post">
				<table class="table striped">
					<thead>
						<tr>
							<th>Name</th>
							<th>Preis</th>
							<th>Beschreibung</th>
							<th>Zutaten</th>
							<th>Veröffentlicht</th>
							<th>Bestellen</th>		
						</tr>
					</thead>
					<tbody>
						<?php foreach ($products as $p){ ?>
						<tr>
							<td><?php echo $p->getName(); ?></td>
							<td class="nowrap">€ <?php echo number_format($p->getPrice(),2,'.',''); ?></td>
							<td><?php echo $p->getDescription(); ?></td>
							<td>
								<?php 
									foreach ($p->getIngredients() as $i) {
										$ingredient = new Ingredient($i);
										echo "" . $ingredient->getName() . " ";
									}
								?>
							</td>
							<td>
								<?php 
									if ($p->getPrivate())
										echo "Nein <i class='icon-locked'></i>";
									else
										echo "Ja <i class='icon-unlocked'></i>";
								?>
								<br/>
								<a href='<?php echo '?c=changeProductVisibility&pid=' . $p->getId() ; ?>' class='fg-crimson'>
				    				&aumlndern
								</a>
							</td>
							<td class="short-column">
								<div class="input-control checkbox" data-role="input-control">
									<label>
										<input type="checkbox" name="<?php echo $p->getID() ?>"/>
										<span class="check"></span>
									</label>
								</div>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<input type="submit" id="submit" name="submit" value="Zum Warenkorb hinzufügen" />
			</form>	
<?php
	}
?>