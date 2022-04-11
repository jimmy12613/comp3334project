<?php
	session_start();
?>
<?php
	
	//Get Heroku ClearDB connection information
    $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
    $cleardb_server = $cleardb_url["host"];
    $cleardb_username = $cleardb_url["user"];
    $cleardb_password = $cleardb_url["pass"];
    $cleardb_db = substr($cleardb_url["path"],1);

    $active_group = 'default';
    $query_builder = TRUE;
    
	$conn = @mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);

	if (mysqli_connect_errno()) {
		die("Failed to connect to MySQL: " . mysqli_connect_error());
	}

	$email =$_POST['email'];	
	
	// to prevent mysql injection
	//$uid = mysqli_real_escape_string($conn,$uid);
	//$psd =  mysqli_real_escape_string($conn,$psw);
	
	$sql = "Select * from user where email='{$email}'";
	$result = mysqli_query($conn, $sql) or die("Failed to query database".mysqli_error($conn));
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

	if ($result-> num_rows > 0) {		# check if have user record in db		#ks maybe redundant???

		$salt = $row['salt'];
		$hash = xor_string($_POST['psw'],$salt);
		$psw = hash('sha256',$hash);
		
		if (($row['email'] == $email) and ($row['psw'] == $psw)){		
			$_SESSION['uid'] =$row['uid'];
			$_SESSION['ln'] =$row['last_name'];

			if ($row['admin'] == 1) {

				header("refresh:0; url=admin.php");

			}

			header("refresh:1; url=index2.php");
			
		}else{
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
                    	<h1>Failed to login!</h1>
                	</div>
            	</body> 
        	</html>
			';
			header("refresh:2; url=login.html");
		}
	}else{
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
                    	<h1>Failed to login!</h1>
                	</div>
            	</body> 
        	</html>
			';
		header("refresh:2; url=login.html");
		
	}
	
	mysqli_free_result($result);
	mysqli_close($conn);
?>

<?php function xor_string($string, $key) {
    for($i = 0; $i < strlen($string); $i++) 
        $string[$i] = ($string[$i] ^ $key[$i % strlen($key)]);
    return $string;
}?>