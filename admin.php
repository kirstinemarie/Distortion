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
<?php

$sql = "SELECT Kunder.idKunder AS kundeId,
Kunder.Kunder_fNavn AS kundeFornavn,
Kunder.Kunder_eNavn AS kundeEfternavn,
Kunder.Kunder_email AS kundeEmail,
Ordredetaljer.idOrdredetaljer AS ordredetaljerId,
Ordredetaljer.produkt1 AS produkt1,
Ordredetaljer.produkt2 AS produkt2,
Ordredetaljer.produkt3 AS produkt3,
Ordredetaljer.produkt4 AS produkt4,
Ordredetaljer.produkt5 AS produkt5,
Ordredetaljer.produkt6 AS produkt6,
Ordre.idOrdre as idOrdre
FROM Ordredetaljer
JOIN Ordre ON Ordre.ordreId = Ordredetaljer.idOrdredetaljer
JOIN Kunder ON Kunder.idKunder = Ordre.kundeId
ORDER BY idKunder Desc;";
      $stmt = $forbindelse->prepare($sql);
      $stmt->bind_result($kundeId, $kundeFornavn, $kundeEfternavn, $kundeEmail ,$ordredetaljerId, $produkt1, $produkt2, $produkt3, $produkt4, $produkt5, $produkt6, $idOrdre);
      $stmt->execute();
      
   
 
	
	
		
	while($stmt->fetch()){
	?>	  
	 
   
<table class="book">
  <tr>
    <th width='150px'>Navn </th>
    <th width='150px'>Email </th>
    <th width='50px'>Gadearmbånd </th>
    <th width='50px'>Pass </th>
    <th width='60px'>Distortion Ø</th>
	  <th width='80px'>Distortion Ø Friday</th>
	  <th width='80px'>Distortion Ø Saturday</th>
	  <th width='50px'>Camping</th>
 
  </tr>
	<tr>
		
		<td><?=$kundeFornavn?> <?=$kundeEfternavn?> </td>
		<td><?=$kundeEmail?> </td>
		<td><?=$produkt1?> </td>
    <td><?=$produkt2?> </td>
		<td><?=$produkt3?></td>
		<td><?=$produkt4?></td>
		<td><?=$produkt5?></td>
		<td><?=$produkt6?></td>
	</tr>
</table>

<?php
}
?>
 









