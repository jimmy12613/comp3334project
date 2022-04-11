<?php
	
	//mail
	require 'PHPMailer/src/PHPMailer.php';
	require 'PHPMailer/src/SMTP.php';
	require 'PHPMailer/src/Exception.php';
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->Mailer = "smtp";
	$mail->Host = "smtp.gmail.com";
	$mail->SMTPAuth = TRUE;
	$mail->SMTPSecure = "tls";
	$mail->Port = '587';
	$mail->Username = 'comp3334pj@gmail.com';
	$mail->Password = 'connect.polyu.hk';
	$mail->setFrom('comp3334pj@gmail.com');
	


	$fn = $_POST['fn'];
	$ln = $_POST['ln'];
	$email = $_POST['email'];
	$salt = strval(rand(10000,99999));
	$hash = xor_string($_POST['psw'],$salt);
	$psw = hash('sha256',$hash);			#hash
	$conn = mysqli_connect("localhost","root","","comp3334");
	
	if (mysqli_connect_errno()) {
	die("Failed to connect to MySQL: " . mysqli_connect_error());}
	
	#check duplicate email
	$email_sql = "SELECT * From user WHERE email='{$email}'";
	$result = mysqli_query($conn, $email_sql);
	
	if ( $_POST['psw'] == $_POST['psw2'] ) {

		if (mysqli_fetch_array($result, MYSQLI_ASSOC) > 0){
			$return['error_msg'] = "Email has been used !";
			echo '
				<html>
					<head>
						<link rel="preconnect" href="https://fonts.googleapis.com">
						<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
						<link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet">
						<link rel="stylesheet" type="text/css" href="nav.css">
					</head>
					<body>   
						<div class="navbar">
							<div class="logo">NFT Market</div>
						</div>
						<p>This email has been used.</p>
					</body> 
				</html>
				';
	
			header("refresh:2; url=register.html");
		}
		else{

			//Validate password
			$uppercase = preg_match('@[A-Z]@', $_POST['psw']);
			$lowercase = preg_match('@[a-z]@', $_POST['psw']);
			$number    = preg_match('@[0-9]@', $_POST['psw']);
	
			if(!$uppercase || !$lowercase || !$number || strlen($_POST['psw']) < 8) {
	
				echo 'Password should be at least 8 characters in length and should include at least one upper case letter, one lower case letter, and one number.';
				header("refresh:2; url=register.html");
	
			}else{
	
				$sql ="SELECT COUNT(*) AS count FROM user";
				$result = mysqli_query($conn, $sql);
				$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
				$uid = $row['count'] +1;
				$mail->Subject = 'Validation';
				$randnumber = rand(100000,999999);
				$mail->Body = $randnumber;
				$randnumber = hash('sha256',$randnumber);
				$sql = "INSERT INTO user (uid, email, first_name, last_name, psw, salt, email_validate) Values ('{$uid}', '{$email}', '{$fn}', '{$ln}', '{$psw}', '{$salt}', '{$randnumber}')";
				$result = mysqli_query($conn, $sql);
		
				if($result){
					$mail->addAddress($email);
					if ($mail->Send()) {
						echo 'Success! Please validate by input the number from email';
					}else{
						echo'error';
					}
					$mail->smtpClose();
					echo '
					<html>
						<head>
							<link rel="preconnect" href="https://fonts.googleapis.com">
							<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
							<link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet">
							<link rel="stylesheet" type="text/css" href="nav.css">
						</head>
						<body>   
							<div class="navbar">
								<div class="logo">NFT Market</div>
							</div>
							<p> Registered. You can log in now. </p>
						</body> 
					</html>
					';
				}	
	
				header("refresh:2; url=login.html");
	
			}
		}
	}else {

		echo 'Two password are not the same.';
		header("refresh:2; url=register.html");
		
	}
	
	#header("refresh:2; url=login.html");
	#mysqli_free_result($result);
	mysqli_close($conn);
?>

<?php function xor_string($string, $key) {
    for($i = 0; $i < strlen($string); $i++) 
        $string[$i] = ($string[$i] ^ $key[$i % strlen($key)]);
    return $string;
}?>