<?php

	/**
	 * Diese View zeigt dem User alle öffentlich Sichtbaren Produkte an
	 * @author Andreas Nenning, Nicholas Rees
	 */

	$products = Product::getAllPublicAvailableProducts();
?>

<h1>Alle Pizzen</h1>
<br/>
<form action="?c=cartAdd" method="post">
	<table class="table striped">
		<thead>
			<tr>
				<th class="text-left"></th>
				<th class="text-left">Pizza Name</th>
				<th class="text-left">Beschreibung</th>
				<th class="text-left">Zutaten</th>
				<th class="text-left">Bewertung</th>
				<th class="text-left">Preis</th>
				<th class="text-left">Bestellen</th>
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
					<div class="rating small active fg-amber" style="height: auto;">
						<ul>
							<?php
								for ($i=1; $i <= 5; $i++) {
									$id = $p->getID();
									if($i <= round($p->getRating()))
										echo "<li class='rated' id='$id"."_"."$i' onclick='javascript:rate(this);'/>";
									else
										echo "<li id='$id"."_"."$i' onclick='javascript:rate(this);'/>";
								}
							?>
						</ul>
						<pre class="tertiary-text-secondary" style="text-align: center;">(<?php echo round($p->getRating(), 2) . " aus " . $p->getNumberOfRatings() . " Bewertungen"; ?>)</pre>
					</div>
				</td>
				<td class="nowrap">€ <?php echo number_format($p->getPrice(), 2, '.', ''); ?></td>
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
	<input type="submit" id="submit" name="submit" value="Zum Warenkorb hinzufügen" />
</form>

<script type="text/javascript">

	function rate(element) {
		var string = element.getAttribute('id').split('_');
		var id = string[0];
		var rating = string[1];
		var url = "?c=rateProduct&i=" + id + "&r=" + rating;
		
		window.location.href = url;
	}

</script>