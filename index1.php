<?php
    ob_start();
    123
	$conn = @mysqli_connect("localhost","root","","comp3334");
	if (mysqli_connect_errno()) {
		die("Failed to connect to MySQL: " . mysqli_connect_error());
	}

    $sql ="SELECT * FROM artwork";

    $div ="";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $file ="artT/".$row['file_name'];
        #echo $file."<br>";
        $div .= '<div class="art" style="background-image: url('.$file.')"> </div>';
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
    </head>
    <body>   
        <div class="navbar">
            <div class="logo">NFT Market</div>
            <div>
                <a href="index1.php">Market</a>
                <div class="dropdown">
                    <button class="dropbtn">Log In/Sign In 
                    <div class="dropdown-content">
                        <a href="login.html">Log In</a>
                        <a href="register.html">Sign In</a>
                    </div>
                    </button>
                
                </div> 
            </div>
        </div>
		<div class="user-art">
			<?php	echo $div; ?>
		</div>
    </body> 
</html>

<?php
	mysqli_close($conn);
	ob_end_flush();
?>