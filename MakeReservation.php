<?php

// Set email variables
$email_to = 'privatejctransport@gmail.com';
$email_subject = 'JC Reservation';

$form_complete = FALSE;
// check form submittal
if(!empty($_POST)) {
	// Sanitise POST array
		$pCity = $_POST['pCity'];
		$dCity = $_POST['dCity'];
		$pZip = $_POST['pZip'];
		$dZip = $_POST['dZip'];
		$passengers = $_POST['passengers'];
		
		$from_address = $_POST['pAddress'] . ', '. $pCity . ', FL ' . $pZip;
		$to_address = $_POST['dAddress'] . ', ' . $dCity . ', FL ' . $dZip;		
		$from = urlencode($from_address);
		$to = urlencode($to_address);
		$data = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false");
		$data = json_decode($data);
		$time = 0;
		$distance = 0;

		foreach($data->rows[0]->elements as $road) {
    		$time += $road->duration->value;
    		$distance += $road->distance->value;
		}
		$miles = $distance * 0.000621371192237328;
		$base_price = round($miles * 2.45 * $passengers, 2);

		$email_to = filter_var($email_to, FILTER_VALIDATE_EMAIL);

		if(filter_var($email_to, FILTER_VALIDATE_EMAIL)){

			$email_info = 'Name: '. $_POST['fullName'] . "\r\r" . 'Email: '. $_POST['email'] 
			. "\r\r" . 'Phone: ' . $_POST['phone'] . "\r\r";
			
			if(isset($_POST['Apt']))
				$pick_up = "From: " . $from_address . ", Apt: " . $_POST['Apt'] ."\r\r";
			else
				$pick_up = "From: " . $from_address ."\r\r";

			$to_address = "To: " . $to_address . "\r\r";
			$price = "Price: $" . (string) $base_price;

			$email_info .= $pick_up . $to_address . $price;

			mail($email_to, $email_subject, $email_info);

			$form_complete = TRUE;

		}
	
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<title>Reservations</title>
	<link rel="stylesheet" type="text/css" href="styles/stylesheet.css">
</head>

<body>

	<?php include 'Header.php' ?>

	<div class='content' id='Reserve_Content'>

        <article>
            <p><b>Quote:</b>&nbsp;To obtain a quote please call us at 407-272-9853 or email us at <a style = "font-size:20px" href="mailto:privatejctransport@gmail.com"> privatejctransport@gmail.com</a>. </p>
            
            <p><b>Payments:</b>&nbsp;Payments can be made through cash, check, or credit/debit cards. A $25.00 return check fee will be applied to checks returned by the bank NSF.  </p>
            
            <p><b>Opening Hours:</b> Our opening hours are from 6AM to 6PM.</p>
            <p><b>Reservation:</b>&nbsp;Please fill out our form if you wish to make a reservation. </p>
            <h2></h2>
        </article>

		<?php if($form_complete === FALSE): ?>
		<div id="form" method="post">
		  <form method="post">
			<div class="contactInfo">
				<div class="contact_title"> <h4>Contact Information</h4> </div>
				<div class="label">
					<div class="field">Name:</div>
					<div class="input"><input id="fullName" name="fullName" value="" required/></div>
				</div>
				
				<div class="label">
					<div class="field">Phone:</div>
					<div class="input"><input id="Phone" name="phone" value="" required/></div>
				</div>
				
				<div class="label">
					<div class="field">Email:</div>
					<div class="input"><input id="email" name="email" value="" required/></div>
				</div>
			</div>

			<div class="tripInfo">
				<div class="trip_title"> <h4>Reservation Information</h4></div>

				<div class="label">
					<div class="t_field">Passengers:</div>
					<div class="t_input"> 
						<select name="passengers">
							<option>1</option>
							<option>2</option>
							<option>3</option>
							<option>4</option>
							<option>5</option>
							<option>6</option>
							<option>7</option>
						</select>
					</div>
				</div>

				<div class="label">
					<div class="t_field">Pickup Address:</div>
					<div class="t_input"> <input name="pAddress" value="" required/></div>
				</div>

				<div class="label">
					<div class="t_field">Pickup City:</div>
						<div class="t_input">
							<select name="pCity">
								<option>Altamonte Springs</option>
								<option>Casselberry</option>
								<option>Kissimme</option>
								<option>Lake Buena Vista</option>
								<option>Lake Mary</option>
								<option>Maitland</option>
								<option>Ocoee</option>
								<option>Orlando</option>
								<option>Oviedo</option>
								<option>Saint Cloud</option>
								<option>Windermere</option>
								<option>Winter Garden</option>
								<option>Winter Park</option>
							</select>
						</div>
				</div>

				<div class="label">
					<div class="t_field">Pickup Apt:</div>
					<div class="t_input"> <input value="" /></div>
				</div>

				<div class="label">
					<div class="t_field">Pickup Zip Code:</div>
					<div class="t_input"> <input name="pZip" value="" required/></div>
				</div>

				<div class="label">
					<div class="t_field">Dropoff Address:</div>
					<div class="t_input"> <input name="dAddress" value="" required/></div>
				</div>

				<div class="label">
					<div class="t_field">Dropoff City:</div>
					<div class="t_input"> 
						<select name="dCity">
							<option>Altamonte Springs</option>
							<option>Casselberry</option>
							<option>Kissimme</option>
							<option>Lake Buena Vista</option>
							<option>Lake Mary</option>
							<option>Maitland</option>
							<option>Ocoee</option>
							<option>Orlando</option>
							<option>Oviedo</option>
							<option>Saint Cloud</option>
							<option>Windermere</option>
							<option>Winter Garden</option>
							<option>Winter Park</option>
						</select>
					</div>
				</div>

				<div class="label">
					<div class="t_field">Dropoff Zip Code:</div>
					<div class="t_input"> <input name="dZip" value="" required/></div>
				</div>



				<div id="Rbutton">
					<button id="submit">Make Reservation</button>
				</div>
		
		<?php else: ?>
			<script type="text/javascript">alert("Thank You for using our services. We will contact you soon about your reservation.")</script>
		<?php endif; ?>
			
		</div>
		</form>

		</div>
	</div>

	<?php include 'Footer.php' ?>
</body>
</html>