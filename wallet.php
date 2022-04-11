<?php
	session_start();
?>
<?php
	$uid =$_SESSION['uid'];
			
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
    if ($row['email_validate'] != $row['email_validated']) {
		echo'
		<html>
            <body>
                <script>
                    if (confirm("Please validate first!")) {
                        window.location.href = "validation.php"
                    } else {
                        window.location.href = "validation.php"
                    }
                </script>
            </body>
        </html>
		';
	}
	$money =$row['money'];
	
	if(!empty($_POST['addMoney'])){			#use when form post value to php
		$uid =$_SESSION['uid'];
		$addMoney =$_POST['addMoney'];
			
		$conn = @mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);
		if (mysqli_connect_errno()) {
			die("Failed to connect to MySQL: " . mysqli_connect_error());
		}
		$money =$money +$addMoney;
		$sql = "update user set money ='{$money}' where uid ='{$uid}'";
		$result = mysqli_query($conn, $sql) or die("Failed to query database".mysqli_error($conn));
		if ($result){
			echo "ok";
		} else {
			echo"failed";
		}
	mysqli_close($conn);}
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="nav.css">
        <link rel="stylesheet" type="text/css" href="wallet.css">
        <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.11.1/css/all.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script>
            $(document).ready(function(){
                $("p").click(function(){
                    $(".top-up").show();
                });
            });
        </script>
      
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
                            <a href="wallet.php">Wallet</a>
                            <a href="#upload">Upload</a>
                            <a href="logout.php">Log out</a>
                        </div>
                    </button>
                </div> 
            </div>
        </div>

        <div class="balance">
            <h2 style="color:#597AAD; font-size:2.5em; font-weight:normal; font-family:'Oswald'">Balance of Your Account: $</h2>
			<h2 style="color:#597AAD; font-size:2.5em; font-weight:normal; font-family:'Oswald'">$ <?php echo $money; ?> </h2>  <!--show money-->
            <p>Top Up</p>
            <div class="top-up">
                <form action ="wallet.php" method ="POST">
                    <label>Enter the amount:</label><br>
                    <input type="number" min="1" name ="addMoney">
                    <div class="submit-button">
                        <input type="submit" value="Enter">
                    </div>
                </form>
            </div>

        </div>


    </body>