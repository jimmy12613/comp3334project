<?php
	session_start();
	ob_start();
	$uid =$_SESSION['uid'];
?>

<?php
			
	$conn = @mysqli_connect("localhost","root","","comp3334");
	if (mysqli_connect_errno()) {
		die("Failed to connect to MySQL: " . mysqli_connect_error());
	}
	
	$sql = "Select * from user where uid='{$uid}'";
	$result = mysqli_query($conn, $sql) or die("Failed to query database".mysqli_error($conn));
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	
	$fn =$row['first_name'];
	$email =$row['email'];
	$money =$row['money'];
	
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
                        $conn = @mysqli_connect("localhost","root","","comp3334");
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
                        $conn = @mysqli_connect("localhost","root","","comp3334");
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
                    <?php
                        $conn = @mysqli_connect("localhost","root","","comp3334");
                        if (mysqli_connect_errno()) {
                            die("Failed to connect to MySQL: " . mysqli_connect_error());
                        }
                        $sql = "Select * from user where uid='{$uid}'";
                        $result = mysqli_query($conn, $sql) or die("Failed to query database".mysqli_error($conn));
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        if ($row['email_validate'] != $row['email_validated']) {
                            echo '
                            <div class="info-art">
                            <div class="info-art-header">
                                <div>
                                    <h3>Validation</h3>
                                </div>
                            </div>
    
                            <div class="validation">
                                <form action="validation2.php" method ="POST">
                                    <label>Please enter the 6-digits number</label><br><br>
                                    <input type="text" name ="valid" id="valid" required><p></p>
                                    <div class="submit-button">
                                        <button value="submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                            ';
                        }else{
                            echo'
                            <h3>&nbsp;&nbsp;&nbsp;&nbsp;You already validated. </h3>
                            ';
                        }
                    ?>
                </div>
                

            </div>
        </body>
</html>


<?php
	mysqli_close($conn);
	ob_end_flush();
?>