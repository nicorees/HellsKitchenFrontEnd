<?php
$products = Product::getAllPublicAvailableProducts();
?>

<form action="?c=cartAdd" method="post">
	<table class="table striped">
		<thead>
			<tr>
				<th class="text-left"></th>
				<th class="text-left">Pizza Name</th>
				<th class="text-left">Preis</th>
				<th class="text-left">Beschreibung</th>
				<th class="text-left">Zutaten</th>
				<th class="text-left">Bestellen</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($products as $p): ?>
			<tr>
				<td><img width="100px" height="100px" src=
					<?php
						if (file_exists(DOC_ROOT . "assets/img/pizza/" . $p->getID() . ".jpg"))
							echo "assets/img/pizza/" . $p->getID() . ".jpg";
						else
							echo "assets/img/pizza/default.jpg";
					?> >
				</td>
				<td><?php echo $p->getName(); ?></td>
				<td><?php echo $p->getPrice(); ?></td>
				<td><?php echo $p->getDescription(); ?></td>
				<td>
					<?php
						
						if (empty($p->getIngredients()))
							print("Keine");

						foreach ($p->getIngredients() as $i) {
							$ingredient = new Ingredient($i);
							printf("%s ",$ingredient->getName());
						}
					?>
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
			<?php endforeach; ?>
		</tbody>
	</table>
	<input type="submit" id="submit" name="submit" value="bestellen" />
</form>

<a href=".?p=chooseStatus">wähle deine Adresse, etc.</a>


<?php 
	//TEST
	//var_dump($_SESSION['addressID'], $_SESSION['pickup']); 
?>