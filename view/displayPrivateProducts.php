<?php
$customer = new Customer($_SESSION["customerID"]);
// fehlt noch: letztes bestelldatum der privaten pizza anzeigen --> evtl model ORDER einfügen?
$products = Product::getCustomerProducts($customer->getCustomerID());

?>


<script language="JavaScript"> 
/* output of alert: private+id of product+value of radiobutton
* eg: private71 means: product number 7 was changed to status 1 
* status 0 : public
* status 1 : private
* TODO: no ouput on screen: update database, so customer can change their privatestatus
*/ 
	function update(val) {
    	alert("Radio button changed to " + val);
	} 
</script>

<?php
if(empty($products)){

	echo "<span class='readable-text'>
			Schade, du hast noch keine eigene Pizza erstellt, 
			aber du kannst den 
			<a href='.?p=configurePizza'>PizzaKonfigurator</a> 
			wählen um eine zu kreieren!
		</span>";
}
	else{
?>

	<table id="products" class="table striped">
		<thead>
			<tr>
				<th>Name</th>
				<th>Preis</th>
				<th>Beschreibung</th>
				<th>Zutaten</th>
				<th>Veröffentlicht</th>			
			</tr>
		</thead>
		<tbody>
			<?php foreach ($products as $p){ ?>
			<tr>
				<td><?php echo $p->getName(); ?></td>
				<td><?php echo $p->getPrice(); ?></td>
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
				
			</tr>
			<?php } ?>
		</tbody>
	</table>
	


<?php
	}
?>