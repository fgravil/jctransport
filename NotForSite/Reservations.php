<?php

// Set email variables
$email_to = 'youremail@address.com';
$email_subject = 'Form submission';

// Set required fields
$required_fields = array('fullName','phone','email','pAddress','pZip','dAddress','dZip');

// set error messages
$error_messages = array(
	'fullName' => '*required field.',
	'phone' => '*required field.',
	'email' => '*Invalid email.',
	'pAddress' => '*required field.',
	'pZip' => '*required field.',
	'dAddress' => '*required field.',
	'dZip' => '*required field.',
);

// Set form status
$form_complete = FALSE;
$confirm = FALSE;
// configure validation array
$validation = array();

// check form submittal
if(!empty($_POST)) {
	// Sanitise POST array
	foreach($_POST as $key => $value) $_POST[$key] = remove_email_injection(trim($value));
	
	// Loop into required fields and make sure they match our needs
	foreach($required_fields as $field) {		
		// the field has been submitted?
		if(!array_key_exists($field, $_POST)) array_push($validation, $field);
		
		// check there is information in the field?
		if($_POST[$field] == '') array_push($validation, $field);
		
		// validate the email address supplied
		if($field == 'email') if(!validate_email_address($_POST[$field])) array_push($validation, $field);
	}
	
	// basic validation result
	if(count($validation) == 0) {

		$pCity = $_POST['pCity'];
		$dCity = $_POST['dCity'];
		$pZip = $_POST['pZip'];
		$dZip = $_POST['dZip'];
		
		$from = $_POST['pAddress'] . ', '. $pCity . ', FL ' . $pZip;
		$to = $_POST['dAddress'] . ', ' . $dCity . ', FL ' . $dZip;		
		$from = urlencode($from);
		$to = urlencode($to);
		$data = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false");
		$data = json_decode($data);
		$time = 0;
		$distance = 0;
		// Prepare our content string
		$email_content = 'New Website Comment: ' . "\n\n";
		
		// simple email content
		foreach($_POST as $key => $value) {
			if($key != 'submit') $email_content .= $key . ': ' . $value . "\n";
		}

		foreach($data->rows[0]->elements as $road) {
    		$time += $road->duration->value;
    		$distance += $road->distance->value;
		}
		$miles = $distance * 0.000621371192237328;
		$base_price = round($miles * 2.50, 2);

		if($confirm == TRUE){
			// if validation passed ok then send the email
			mail($email_to, $email_subject, $email_content);
		}
		// Update form switch
		$form_complete = TRUE;
	}
}

function validate_email_address($email = FALSE) {
	return (preg_match('/^[^@\s]+@([-a-z0-9]+\.)+[a-z]{2,}$/i', $email))? TRUE : FALSE;
}

function remove_email_injection($field = FALSE) {
   return (str_ireplace(array("\r", "\n", "%0a", "%0d", "Content-Type:", "bcc:","to:","cc:"), '', $field));
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Reservations</title>
	<link rel="stylesheet" type="text/css" href="styles/stylesheet.css">

	<script>
		function myFunction() {
		    var x;
		    if (confirm("You're price will be $<?php echo $base_price?> Press Ok to confirm.") == true) {
		        x = "<?php $confirm = TRUE; ?>";
		    } else {
		        x = "You pressed Cancel!";
		    }
		    document.getElementById("demo").innerHTML = x;
		}
		</script>
</head>

<body>

	<?php include 'Header.php' ?>

	<div class='content'>
		
		<div class="res_title">
			<h4>Please Call (407) 272-9853 to make a reservation</h4>
		</div>

		<div id="form">
		  <form method="post">
			<div class="contactInfo">
				<div class="contact_title"> <h4>Contact Information</h4> </div>
				<div class="label">
					<div class="field">Name:</div>
					<div class="input"><input id="fullName" name="fullName" value="<?php echo isset($_POST['fullName']) ? $_POST['fullName'] : ''; ?>"/></div>
					<?php if(in_array('fullName', $validation)): ?><p class="error"><?php echo $error_messages['fullName']; ?></p><?php endif; ?>

				</div>
				
				<div class="label">
					<div class="field">Phone:</div>
					<div class="input"><input id="Phone" name="phone" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ''; ?>"/></div>
					<?php if(in_array('phone', $validation)): ?><p class="error"><?php echo $error_messages['phone']; ?></p><?php endif; ?>

				</div>
				
				<div class="label">
					<div class="field">Email:</div>
					<div class="input"><input id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>"/></div>
					<?php if(in_array('email', $validation)): ?><p class="error"><?php echo $error_messages['email']; ?></p><?php endif; ?>

				</div>
			</div>

			<div class="tripInfo">
				<div class="trip_title"> <h4>Reservation Information</h4></div>

				<div class="label">
					<div class="t_field">Vehicle:</div>
					<div class="t_input"> 
						<select name="vehicle">
							<option>Luxury Minivan</option>
							<option>Luxury SUV</option>
							<?php echo isset($_POST['vehicle']) ? $_POST['vehicle'] : '';?>
						</select>
					</div>
				</div>

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
							<?php echo isset($_POST['passengers']) ? $_POST['passengers'] : '';?>
						</select>
					</div>
				</div>

				<div class="label">
					<div class="t_field">Pickup Address:</div>
					<div class="t_input"> <input name="pAddress" value="<?php echo isset($_POST['pAddress']) ? $_POST['pAddress'] : ''; ?>"/></div>
					<?php if(in_array('pAddress', $validation)): ?><p class="error"><?php echo $error_messages['pAddress']; ?></p><?php endif; ?>
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
								<?php echo isset($_POST['pCity']) ? $_POST['pCity'] : '';?>
							</select>
						</div>
				</div>

				<div class="label">
					<div class="t_field">Pickup Apt:</div>
					<div class="t_input"> <input value="<?php echo isset($_POST['Apt']) ? $_POST['Apt'] : ''; ?>"/></div>
				</div>

				<div class="label">
					<div class="t_field">Pickup Zip Code:</div>
					<div class="t_input"> <input name="pZip" value="<?php echo isset($_POST['pZip']) ? $_POST['pZip'] : ''; ?>"/></div>
					<?php if(in_array('pZip', $validation)): ?><p class="error"><?php echo $error_messages['pZip']; ?></p><?php endif; ?>
				</div>

				<div class="label">
					<div class="t_field">Dropoff Address:</div>
					<div class="t_input"> <input name="dAddress" value="<?php echo isset($_POST['dAddress']) ? $_POST['dAddress'] : ''; ?>"/></div>
					<?php if(in_array('dAddress', $validation)): ?><p class="error"><?php echo $error_messages['dAddress']; ?></p><?php endif; ?>
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
							<?php echo isset($_POST['dCity']) ? $_POST['dCity'] : '';?>
						</select>
					</div>
				</div>

				<div class="label">
					<div class="t_field">Dropoff Zip Code:</div>
					<div class="t_input"> <input name="dZip" value="<?php echo isset($_POST['dZip']) ? $_POST['dZip'] : ''; ?>"/></div>
					<?php if(in_array('dZip', $validation)): ?><p class="error"><?php echo $error_messages['dZip']; ?></p><?php endif; ?>
				</div>



				<div id="Rbutton">
					<button id="submit" onclick="myFunction()" >Make Reservation</button>
				</div>

			
		</div>
		</form>

		</div>
	</div>

	<?php include 'footer.php' ?>
</body>
</html>