<?php
	session_start();
	ob_start();
	$uid =$_SESSION['uid'];
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
	
	$sql = "Select * from user where uid='{$uid}'";
	$result = mysqli_query($conn, $sql) or die("Failed to query database".mysqli_error($conn));
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

	if (isset($_POST["psw"])){
		
		$salt = $row['salt'];
		$hash = xor_string($_POST['psw'],$salt);
		$psw = hash('sha256',$hash);
		if ($psw ==$row['psw']){
			$sale = false;
			if ($_POST['sale'] ==1){
				$sale =true;
			}
			
			$sql = "update artwork set price ='{$_POST['new_price']}' , sale ='{$sale}' where aid ='{$_POST['aid']}'";
			$result = mysqli_query($conn, $sql) or die("Failed to query database".mysqli_error($conn));
			
			if ($result){
				echo "<script>alert('updated!')</script>";
			} else {
				echo "<script>alert('failed')</script>";
			}
		} else {
			echo "<script>alert('wrong password')</script>";
		}
	} 

?>
<?php
			
	$conn = @mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);
	if (mysqli_connect_errno()) {
		die("Failed to connect to MySQL: " . mysqli_connect_error());
	}
	
	$sql = "Select * from user where uid='{$uid}'";
	$result = mysqli_query($conn, $sql) or die("Failed to query database".mysqli_error($conn));
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	
	$fn =$row['first_name'];
	$email =$row['email'];
	$money =$row['money'];
	
	$sql ="SELECT * FROM artwork where uid ='{$uid}' and approve =true";
	$div ="";
	$result = mysqli_query($conn, $sql);
	
	
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		$file ="artT/".$row['file_name'];
		$name = $row['name'];
		$aid = $row['aid'];
		$price = $row['price'];
		$desc = $row['description'];
		
		if ($row['sale'] ==1){$check ="checked =1";} else {$check ="";}
		#echo $file."<br>";
		$div .= '<div>
					<button class ="art" style="background-image: url('.$file.')"></button>
					<div id="Modal" class="modal">
						<div class="modal-content">
                                        
							<div class="modal-content-art" style="background-image: url('.$file.')"> </div>
							<div class="modal-content-detail">
								<h2>'.$name.'</h2>
								<h2>#</h2> 
								<h2>'.$aid.'</h2>
								<br>
								<p>'.$desc.'</p>
								<form action="" method ="POST" >
									<input type ="hidden" name ="filename" value ="'.$row["file_name"].'" readonly>
									<input type ="submit" value ="Download Original">
								</form>

								<div class="sell-info">
									<h4>Sell Infomation</h4>
									<form action "" method ="POST"> 
										<div class="pt1">
											<input type ="hidden" value ="'.$aid.'" name ="aid">
											<input type ="hidden" name ="sale" value =0>
											<input type="checkbox" name ="sale" value =1 '.$check.'">
											<label>For Sale</label><br><br>
											<label>Price:</label>
											<input type="number" value="'.$price.'" name ="new_price"><br>
										</div>
										<button type="button" class="collapse">Confirm</button>
										<div class="pt2">
											<label>Password:</label>
											<input type="password" class="pwd" name ="psw"><br>
											<input type="submit" id="sell-submit" value="Enter" >
										</div>
									</form>
								</div>

							</div>
							<span class="close">&times;</span>
						</div>
					</div>
				</div>';
		
	}
	
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="nav.css">
        <link rel="stylesheet" type="text/css" href="info.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.1/css/all.css"> 
    </head>
        <body>
            <div class="navbar">
                <div class="logo">NFT Market</div>
                <div>
                    <a href="index2.php">Market</a>
                    <div class="dropdown">
                        <button class="dropbtn">
                        <i class="far fa-user-circle fa-lg"></i>User
                            <div class="dropdown-content">
                                <a href="info.php">View Account</a>
								<?php
                        $conn = @mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);
                        if (mysqli_connect_errno()) {
                            die("Failed to connect to MySQL: " . mysqli_connect_error());
                        }
                        $sql = "Select * from user where uid='{$uid}'";
                        $result = mysqli_query($conn, $sql) or die("Failed to query database".mysqli_error($conn));
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        if ($row['email_validate'] == $row['email_validated']) {
                            echo'
                            	<a href="wallet.php">Wallet</a>
                            	<a href="upload.php">Upload</a>
                            ';
                        }
                    ?>
								<a href="validation.php">Validation</a>
                                <a href="logout.php">Log out</a>
                            </div>
                        </button>
                    </div> 
                </div>
            </div>

            <div class="info-wrapper">
                <div class="info">
                    <div class="info-header">
                        <h2>Personal Info</h2>
                        <hr class="solid">
                    </div>
                    <div class="info-person">
                        <h3>Hello, </h3>
                        <!--Fetch user's first name from database-->
                        <h3><?php echo $fn; ?></h3>

                        <ul>
                            <li>Email: <?php echo $email; ?></li>
                            <li>Password: ********<br>
                                <a href="#changePassword">Change Password</a>
                            </li>
                            <li>Balance: $<?php echo $money; ?><br>
							<?php
                        $conn = @mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);
                        if (mysqli_connect_errno()) {
                            die("Failed to connect to MySQL: " . mysqli_connect_error());
                        }
                        $sql = "Select * from user where uid='{$uid}'";
                        $result = mysqli_query($conn, $sql) or die("Failed to query database".mysqli_error($conn));
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        if ($row['email_validate'] == $row['email_validated']) {
                            echo'
                            <a href="wallet.php">Top Up</a>
                            ';
                        }
                    ?>
                            </li>
                        </ul>
                    </div>
                    <div class="info-art">
                        <div class="info-art-header">
                            <div>
                                <h3>Your Art</h3>
                            </div>
							<?php
                        $conn = @mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);
                        if (mysqli_connect_errno()) {
                            die("Failed to connect to MySQL: " . mysqli_connect_error());
                        }
                        $sql = "Select * from user where uid='{$uid}'";
                        $result = mysqli_query($conn, $sql) or die("Failed to query database".mysqli_error($conn));
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        if ($row['email_validate'] == $row['email_validated']) {
                            echo'
							<div class="upload-button">
							<input type="button" onclick="location.href=\'upload.php\';" value="Upload">
						</div>
                            ';
                        }
                    ?>
                        </div>

                        <div class="user-art">
							<?php
								echo $div;
							?>
                        </div>
                    </div>
                </div>
                

            </div>
			<script src="info.js"></script>
        </body>
</html>


<?php
	if(isset($_POST['filename'])){

		$file ="artT/".$_POST['filename'];
		$fn = $_POST['filename'];
  
		if (file_exists($file)) {			#https://stackoverflow.com/questions/11315951/using-the-browser-prompt-to-download-a-file
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($fn));
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			ob_clean();
			flush();
			readfile($file);
			exit;
		}

	}
?>

<?php function xor_string($string, $key) {
    for($i = 0; $i < strlen($string); $i++) 
        $string[$i] = ($string[$i] ^ $key[$i % strlen($key)]);
    return $string;
}?>

<?php
	mysqli_close($conn);
	ob_end_flush();
?>