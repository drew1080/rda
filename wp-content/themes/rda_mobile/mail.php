<?php


	//declare our variables
	$name = $_GET['name'];
	$email = $_GET['email'];
	$subject = $_GET['subject'];
	$comment = $_GET['comment'];	
	$todayis = date("l, F j, Y, g:i a") ;
	$message = "Message: $comment \r \n From: $name  \r \n Reply to: $email";
	$headers = "From: $name <$email> \r\n ";
	$emailTo = $_GET['mailto'];
	$greetingsMessage = $_GET['greetingsmessage']; 
	
	//put your email address here
	mail($emailTo, $subject, $message, $headers); 
?>
<?php // You can put your message do display after the email was sent
 ?>
	<p><strong>Thank you  <?php echo $name ?></strong><br />
        <?php echo $greetingsMessage; ?></p>
		
	
