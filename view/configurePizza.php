<?php

$ingredients = Ingredient::getAllIngredients();

?>

<form action="?c=generateProduct" method="post">
	<table id="Ingredients" class="table striped slim">
		<thead>
			<tr>
				<th class="text-left"></th>
				<th class="text-left">Zutat</th>
				<th class="text-left">Preis</th>
				<th class="text-left">Ausw&aumlhlen</th>
			</tr>
		</thead>
		<tbody>
		<p> Name der Pizza </p>
		<input class="textfield" type="text"name="txt_pizzaname" value="" />
			<?php foreach ($ingredients as $i): ?>
			<tr>
				<td><img width="70px" height="70px" src=<?php  echo "assets/img/ingredients/" . $i->getID() . ".jpg" ?> ></td>
				<td><?php echo $i->getName(); ?></td>
				<td><?php echo sprintf("%.2f",$i->getPrice()) ."€" ;?> </td>
				<td class="short-column">
					<div class="input-control checkbox" data-role="input-control">
						<label>
							<input name="IngredientID[]" type="checkbox" value="<?php echo $i->getID(); ?>"/>
							<span class="check"></span>
						</label>
					</div>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<p>
		<p class="tertiary-text">Möchtest du deine Pizza veröffentlichen?</p>
		<div class="input-control radio default-style">
			<label>
			<input type="radio" name="private" value="0" />
			<span class="check"></span>
			Ja
			</label>
		</div>
		<div class="input-control radio default-style">
			<label>
				<input type="radio" name="private" value="1" />
				<span class="check"></span>
				Nein
			</label>
		</div>
	</p>

	<input type="submit" id="submit" name="bnt_bestellen" value="Pizza Erstellen" />
</form>