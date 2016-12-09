<?php

// Set email variables
$to_email = 'privatejctransport@gmail.com';
$email_subject = 'JCTransport';


// Set form status
$form_complete = FALSE;

// check form submittal
if(!empty($_POST)) {
	// Sanitise POST array
	$to_email = filter_var($to_email, FILTER_VALIDATE_EMAIL);

	if(filter_var($to_email, FILTER_VALIDATE_EMAIL)){

		$email_content =  'Email: ' . $_POST['email'] . "\r\r" . 'Name: ' . $_POST['fullName'] . "\r\r" . 'Message: ' . $_POST['comment'];
		$headers  = $_POST['email'];
		$headers .= "From: text/html; charset=utf-8 \r\n";
		mail($to_email,$email_subject,$email_content);
	}

	$form_complete = TRUE;
}
	

?>
<!DOCTYPE html>

<html lang="en">
	
	<head>
        <meta charset="utf-8">
		<title>Contact</title>

		<link rel="stylesheet" type="text/css" href="styles/contactInfo.css">
    
    	<style type="text/css">
    		address{
    			font-size: 24px;
    		}
    		address a{
    			font-size: 24px;
    		}
    	</style>
    	<script src="https://maps.googleapis.com/maps/api/js"></script>
    
	    <script>

		      function initialize() {
		        var mapCanvas = document.getElementById('map');
		        var mapOptions = {
		          center: new google.maps.LatLng(28.5, -81.2989),
		          zoom: 10,
		          mapTypeId: google.maps.MapTypeId.ROADMAP
		        }
		        var map = new google.maps.Map(mapCanvas, mapOptions)
		      }
		      google.maps.event.addDomListener(window, 'load', initialize);

	    </script>

	</head>
	
	<body>

		<?php  
			include 'Header.php'
		?>

		<div id="contact_body">
			
			<div id="contact_title">
				<h1>Contact Us</h1>
			</div>

			<div class="address">	
					<h2>Address</h2>
			<address>
					<p>Main Office</p>
					<p>PO Box 592611</p>
					<p>Orlando, FL 32859</p>
					Email us at <a href="mailto: privatejctransport@gmail.com"> privatejctransport@gmail.com</a>
			</address>


				<div id='map'></div> 
			</div>

			<div class="phone_numbers">
				<h2>Phone</h2>
				<p>Contact 1: 407-272-9853</p>
				<p>Contact 2: 407-590-5528</p>

				<?php if($form_complete === FALSE): ?>
				<div id="form">
				
				<form method="post">
					<div class="row">

						<div class="label">Name:</div>
						
						<div class="input">
							<input type="text" id="fullname" class="detail" name="fullName" value="" required/>
						</div>

						<div class="context">e.g. John Smith or Jane Doe</div>
					</div>

					<div class="row">

						<div class="label">Email:</div>
						
						<div class="input">
							<input type="text" id="fullname" class="detail" name="email" value="" required/>
						</div>

						<div class="context">We will not share your email with anyone or spam you either.</div>

					</div>

					<div class="row">

						<div class="label">Message:</div>
						
						<div class="input">
							<textarea type="text" id="comment" class="message" name="comment" required></textarea>
						</div>

					</div>

					<div class="submit">
						<button id="send">Send Message</button> 
					</div>

					<?php else: ?>

							<p>Message Received. Thank You.</p>
					<?php endif; ?>
				</form>
				</div>

			</div> 

			</div>


			<?php  
					include 'Footer.php';
			?>

		
	</body>

</html>