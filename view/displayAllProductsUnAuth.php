<?php

	/**
	 * Diese View zeigt auch nicht eingeloggten Usern
	 * alle öffentlich Sichtbaren Produkte an
	 * @author Andreas Nenning, Nicholas Rees
	 */

	$products = Product::getAllPublicAvailableProducts();
?>

<h1>Alle Pizzen im Angebot</h1>
<br/>
<table class="table striped">
	<thead>
		<tr>
			<th class="text-left"></th>
			<th class="text-left">Pizza Name</th>
			<th class="text-left">Preis</th>
			<th class="text-left">Beschreibung</th>
			<th class="text-left">Zutaten</th>
			<th class="text-left">Bewertung</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($products as $p): ?>
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
			<td class="nowrap">€ <?php echo number_format($p->getPrice(), 2, '.', ''); ?></td>
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
			<td>
				<div class="rating small fg-amber" style="height: auto;">
					<ul>
						<?php
							for ($i=1; $i <= 5; $i++) {
								$id = $p->getID();
								if($i <= round($p->getRating()))
									echo "<li class='rated'/>";
								else
									echo "<li/>";
							}
						?>
					</ul>
					<pre class="tertiary-text-secondary" style="text-align: center;">(<?php echo round($p->getRating(), 2) . " aus " . $p->getNumberOfRatings() . " Bewertungen"; ?>)</pre>
				</div>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>