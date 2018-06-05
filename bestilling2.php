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

function mail_utf8($to, $subject = '(No subject)', $message = '', $header = '') {
  $header_ = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/plain; charset=UTF-8' . "\r\n";
  mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $message, $header_ . $header);
}

if(isset($_GET["bestil"])){
	$firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING) or die("Ugyldig fornavn");
	$lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING) or die("Ugyldig efternavn");
	$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) or die("Ugyldig email");
	$age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT) or die("Ugyldig age");
	
	$produkt1 = filter_input(INPUT_POST, 'produkt1', FILTER_VALIDATE_INT);
	$produkt2 = filter_input(INPUT_POST, 'produkt2', FILTER_VALIDATE_INT);
	$produkt3 = filter_input(INPUT_POST, 'produkt3', FILTER_VALIDATE_INT);
	$produkt4 = filter_input(INPUT_POST, 'produkt4', FILTER_VALIDATE_INT);
	$produkt5 = filter_input(INPUT_POST, 'produkt5', FILTER_VALIDATE_INT);
	$produkt6 = filter_input(INPUT_POST, 'produkt6', FILTER_VALIDATE_INT);
	$totalprodukt = $produkt1+$produkt2+$produkt3+$produkt4+$produkt5+$produkt6;
	
	mysqli_query($forbindelse, "INSERT INTO Kunder (Kunder_fnavn, Kunder_enavn, Kunder_email, Kunder_alder) VALUES ('{$firstname}', '{$lastname}', '{$email}', '{$age}')");
	if(mysqli_affected_rows($forbindelse) > 0){
		$kundeId = mysqli_insert_id($forbindelse);
		
		mysqli_query($forbindelse, "INSERT INTO Ordredetaljer (produkt1, produkt2, produkt3, produkt4, produkt5, produkt6) VALUES ('{$produkt1}', '{$produkt2}', '{$produkt3}', '{$produkt4}', '{$produkt5}', '{$produkt6}')");
		
		if(mysqli_affected_rows($forbindelse) > 0){
			$ordreId = mysqli_insert_id($forbindelse);
			
			mysqli_query($forbindelse, "INSERT INTO Ordre (kundeId, ordreId) VALUES ('{$kundeId}', '{$ordreId}')");
			
			if(mysqli_affected_rows($forbindelse) > 0){
				?>
<div class="oprettet">Your order is registered</div>
<?php
$to      = "booking@sathinimipradit.dk";
$subject = 'Distortion - Order confirmation';
$message = "Your order is registered. You have order number: {$ordreId}";
$headers = 'From: booking@sathinimipradit.dk' . "\r\n" .
    'Reply-To: booking@sathinimipradit.dk' . "\r\n" .
    'Cc: ' . $email . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
			}
		}
	} else {
		echo "Kunne ikke oprette kunde " . mysqli_error($forbindelse);
	}
}
?>
<div class="indhold">
<div class="ordreDetaljer">
	<h3>Ordre</h1>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
<?php
	$pris = 0;
	foreach($_GET as $produkt => $val){
		if($val != 0){
			$hentProdukter = mysqli_query($forbindelse, "SELECT * FROM Produkter WHERE Produkt_Alias='{$produkt}'");
			$produktet = mysqli_fetch_assoc($hentProdukter);
			echo "<tr><td>{$produktet["Produkter_type"]}</td><td>{$val} stk.</td></tr>";
			$egenPris = $produktet["Produkter_pris"] * $val;
			$pris = $pris + $egenPris;
		}
	}
		if(!isset($_GET["bestil"]) && $pris == 0){
			?>
		<script type="text/javascript">
	alert("Please select min. 1 ticket");
	window.history.back();
</script>
		<?php
		}
?>
	</table>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr><td>Total pris:</td><td><?=$pris?> DKK.</td></tr>
	</table>
</div>
<div class="kundeOplysninger">
	<h3>Kundeoplysninger</h3>
	<form action="?bestil" method="post" id="kunder" name="betalingsForm">
	<label>First name</label>
  <input type="text" name="firstname" placeholder="First Name" required>
  <br>
  <label>Last name</label>
  <input type="text" name="lastname"  placeholder="Last Name" required>
  <br>
<label>Email</label>
  <input type="email" name="email"  placeholder="Email" required>
  <br>
<label>Age</label>
  <input type="number" name="age" min="18"  placeholder="18" required><br>
  <div class="form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
    <label class="form-check-label" for="exampleCheck1">I accept my information is correct</label>
  </div>
        </label>
		<input type="hidden" name="produkt1" value="<?=$_GET["produkt1"]?>">
		<input type="hidden" name="produkt2" value="<?=$_GET["produkt2"]?>">
		<input type="hidden" name="produkt3" value="<?=$_GET["produkt3"]?>">
		<input type="hidden" name="produkt4" value="<?=$_GET["produkt4"]?>">
		<input type="hidden" name="produkt5" value="<?=$_GET["produkt5"]?>">
		<input type="hidden" name="produkt6" value="<?=$_GET["produkt6"]?>">
  <br>
  <div class="form-row">
    <label for="card-element">
      Credit or debit card
    </label>
    <div id="card-element">
      <!-- A Stripe Element will be inserted here. -->
    </div>

    <!-- Used to display form errors. -->
    <div id="card-errors" role="alert"></div>
  </div>
		<input type="submit" value="payment" name="betal">
	</form>
</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <!-- Stripe JS -->
        <script src="https://js.stripe.com/v3/"></script>
        <!-- Your JS File -->
        <script src="/charge.js"></script>