<?php
	session_start();
	$uid =$_SESSION['uid'];
?>

<?php
    ob_start();
	$conn = @mysqli_connect("localhost","root","","comp3334");
	if (mysqli_connect_errno()) {
		die("Failed to connect to MySQL: " . mysqli_connect_error());
	}

    $sql ="SELECT * FROM artwork where approve ='accepted'";

    $div ="";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $file ="artT/".$row['file_name'];
        #echo $file."<br>";
        $div .= '<div class="art" style="background-image: url('.$file.')"> </div>';
    }

    if (isset($_SESSION['uid'])) {
    
        if($_SESSION['uid'] != null) {
			
            $sql = "Select * from user where uid='{$uid}'";
            $result = mysqli_query($conn, $sql) or die("Failed to query database".mysqli_error($conn));
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($row['email_validate'] == $row['email_validated']) {
                echo '
            <html>
                <head>
                    <link rel="preconnect" href="https://fonts.googleapis.com">
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                    <link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet">
                    <link rel="stylesheet" type="text/css" href="nav.css">
                    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.11.1/css/all.css">
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
                                        <a href="upload.php">Upload</a>
                                        <a href="logout.php">Log out</a>
                                    </div>
                                </button>
                            </div> 
                        </div>
                    </div>
                    <div class="user-art">
                        <?php	echo $div; ?>
                    </div>
                </body> 
            </html>';
            }else {
                echo '
            <html>
                <head>
                    <link rel="preconnect" href="https://fonts.googleapis.com">
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                    <link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet">
                    <link rel="stylesheet" type="text/css" href="nav.css">
                    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.11.1/css/all.css">
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
                                        <a href="validation.php">Validation</a>
                                        <a href="logout.php">Log out</a>
                                    </div>
                                </button>
                            </div> 
                        </div>
                    </div>
                    <div class="user-art">
                        <?php	echo $div; ?>
                    </div>
                </body> 
            </html>';
            }
            
        }
    }else {		#prevent visiting this page without log in
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
                <p>You have not login!</p>
                <div class="user-art">
                        <?php	echo $div; ?>
                </div>
            </body> 
        </html>
        ';
        header("refresh:2; url=login.html");
    }
?>

<?php
	ob_end_flush();
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
            <div class="user-art">
                    <?php	echo $div; ?>
            </div>
        </body>
</html>