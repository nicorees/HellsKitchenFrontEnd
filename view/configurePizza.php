<?php

/**
 * Diese View gibt dem User die Möglichkeit eine Pizza zu erstellen
 * einer Bestellung
 * @author Andreas Nenning, Steffen Schenk
 */	

$ingredients = Ingredient::getAllAvailableIngredients();

?>

<h1>Erstelle deine eigene Pizza!</h1>
<br/>
<form action="?c=generateProduct" method="post">
	<table id="Ingredients" class="table striped slim">
		<thead>
			<tr>
				<th class="text-left">W&aumlhle deine Zutaten:</th>
				<th class="text-left">Zutat</th>
				<th class="text-left">Preis</th>
				<th class="text-left">Ausw&aumlhlen</th>
			</tr>
		</thead>
		<tbody>
		<p>Name deiner Pizza: (max. 45 Zeichen)</p>
		<input class="textfield" type="text" name="txt_pizzaname" value="" style="width: 400px;	" />
		<p>Beschreibe deine Pizza: (max. 45 Zeichen)</p>
		<input class="textfield" type="text" name="txt_pizzadesc" value="" style="width: 400px;	" />
		<br/><br/>
			<?php foreach ($ingredients as $i): ?>
			<tr>
				<td>
					<img width="70px" height="70px" src=
						<?php
							if (file_exists(DOC_ROOT . "assets/img/ingredients/" . $i->getID() . ".jpg"))
								echo "assets/img/ingredients/" . $i->getID() . ".jpg";
							else
								echo "assets/img/ingredients/default.jpg";
						?>
					>
				</td>
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