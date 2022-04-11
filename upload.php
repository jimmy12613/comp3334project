<?php
	session_start();
	$uid =$_SESSION['uid'];
	
	$conn = @mysqli_connect("localhost","root","","comp3334");
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
?>


<?php

	if(isset($_POST['submit'])){
		
		$sql ="select count(*) as count from artwork";
		$result =mysqli_query($conn, $sql);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		$aid = $row['count'] +1;
		
		$name =$_POST['name'];
		$price =$_POST['price'];
		$desc =" ";
		if ($_POST['desc'] !=0){
			$desc =$_POST['desc'];
		}
		$sale =false;
		if ($_POST['sale'] ==1){
			$sale =true;
		}

		$fileName =explode(".", $_FILES["art"]["name"]);
		$newName =$uid . "_" . $aid . "." .end($fileName);
		$targetFilePath ="artT/". $newName;
		$tempName =$_FILES["art"]["tmp_name"];
		
		$fileType =pathinfo($targetFilePath, PATHINFO_EXTENSION);
		$allowType =array('jpg', 'png', 'jpeg');

		if (in_array($fileType, $allowType)){
			//upload to server (localhost)
			if (move_uploaded_file($tempName, $targetFilePath)){

				$fileName2 =explode(".", $_FILES["just"]["name"]);
				$newName2 =$uid . "_" . $aid . "." .end($fileName2);
				$targetFilePath2 ="justification/". $newName2;
				$tempName2 =$_FILES["just"]["tmp_name"];
				$fileType2 =pathinfo($targetFilePath2, PATHINFO_EXTENSION);
				$allowType2 ='pdf';
				if ($fileType2 == $allowType2){

					if (move_uploaded_file($tempName2, $targetFilePath2)){
						//input filename to db
						$sql ="insert into artwork (aid, uid, file_name, uploaded_on, name, price, description, sale, justification) 
							values ('{$aid}', '{$uid}', '{$newName}', NOW(), '{$name}', '{$price}', '{$desc}', '{$sale}', '{$newName2}')";
					
						$result =mysqli_query($conn, $sql);
						echo "<script>alert('Uploaded')</script>";
						header("Location: uploadSuccess.html");
					}
				}
			}
		}

	}
	mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
	<head>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="nav.css">
        <link rel="stylesheet" type="text/css" href="upload.css">
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
							<a href="wallet.php">Wallet</a>
							<a href="upload.php">Upload</a>
							<a href="logout.php">Log out</a>
						</div>
					</button>
				</div> 
			</div>
		</div>
		<h2 class="text-center">Upload New Art</h2>
		
		<form action ="upload.php" method ="POST" enctype="multipart/form-data" class="text-left">
			<div class="artUpload">
                    <div class="form-control text-center">
                        <img id="preview"><br>
                        <label for="file-upload" class="custom-file-upload ">Browse File</label>
                        <input type ="file" name ="art" id="file-upload" accept="image/*" onchange="loadFile(event)"required >
                    </div>
                    <div class="form-control ">
                        <label for="art-name" class="required">Art Name:</label>
                        <input type="text" name="name" id="art-name" required>
                    </div>
                    <div class="form-control ">
                        <label for="art-descr">Description:</label><br><br>
                        <textarea name="desc" id="art-descr" placeholder="Description of your art..." value =0></textarea>
                    </div>
                    <div class="form-control">
                        <input type ="hidden" value =0 name ="sale">
                        <input type ="checkbox" value =1 name ="sale" id="art-sale">
                        <label for="art-sale">For sale</label>
                    </div>
                    <div class="form-control">
                        <label for="art-price"> Price ($) : </label>
                        <input type ="number" name ="price" id="art-price" min="0" value="0">
                    </div>
			</div>
			<hr>

                <div class="artUpload">
                    <h3>Justifaction of ownership</h3>
                    <p>Please provide a <strong>PDF</strong> file including your justification. 
                        <br>Your file could include images, description or video link to prove your ownership. Upload without appropriate justification of ownership will not be accepted. </p>

                    <div class="form-control ">
                        <input type ="file" accept=".pdf" class="required" name ="just" required>
                    </div>

                    <input type="submit" value="Submit" name ="submit" class="spacer"><br>
                    
                </div> 
				
		</form>
		
		<a href="info.php">back</a>
		<script src="upload.js"></script>
    </body> 
</html>




