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

	<table id="products">
		<thead>
			<tr>
				<th>Product Name</th>
				<th>Price</th>
				<th>Description</th>
				<th>Ingredients</th>
				<th>Public?</th>
			
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
							echo $ingredient->getName();
						}
					?>
				</td>
				<td> <?php  
						// private or not? - set the right combination of radiobuttons
						$private = $p->getPrivate();

						if($private == '0'){

						echo 'ja: <input onclick="update(this.name+this.value);" type="radio" checked ="checked"  name="private'.$p->getID().'" value="0" /> nein: <input onclick="update(this.name+this.value);" type="radio" name="private'.$p->getID().'" value="1" /> ';

						} else {

						echo 'ja: <input onclick="update(this.name+this.value);" type="radio"   name="private'.$p->getID().'" value="0" /> nein: <input onclick="update(this.name+this.value);" type="radio" checked ="checked" name="private'.$p->getID().'" value="1" /> ';

						}
					

						?> </td>
				
			</tr>
			<?php } ?>
		</tbody>
	</table>
	


<?php
	}
?>