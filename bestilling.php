<?php
const HNAVN = 'localhost'; //server
const DBBRUGER = 'root';  //bruger
const DBKODE = 'root';  //password
const DBDB = 'root';  //database navn 
$forbindelse = new mysqli(HNAVN, DBBRUGER, DBKODE, DBDB);
$forbindelse->set_charset ('utf8');
if($forbindelse->connect_error){
die($forbindelse->connect_error);
}
?>
<form action="/distortion/bestilling/" method="get">
<?php
$hentprodukt = mysqli_query($forbindelse, "SELECT * FROM Produkter ORDER BY Produkt_Alias ASC");
while ($produktinfo = mysqli_fetch_array($hentprodukt)){
$produkterType = $produktinfo["Produkter_type"];
$produkterPris = $produktinfo["Produkter_pris"];
$produkterAlias = $produktinfo["Produkt_Alias"];
	
?>
<div class="tic-bestil">
<label for="<?=$produkterAlias?>"><?=$produkterType . " " . $produkterPris . "DKK " ?></label>
<input name="<?=$produkterAlias?>" type="number" id="ticcounter" min="0" value="0">
</div>
<?php
}	
?>
<div>
<button name="cmd" type="submit" class="button inverted big color-white square outline">BUY NOW</button>
</div>
</form>