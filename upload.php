<?php
	session_start();
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
?>


<?php

	session_start();
	$uid =$_SESSION['uid'];
	$conn = @mysqli_connect("localhost","root","","comp3334");
		if (mysqli_connect_errno()) {
			die("Failed to connect to MySQL: " . mysqli_connect_error());
		}
	if(isset($_POST['submit'])){
		
		$sql ="select count(*) as count from artwork";
		$result =mysqli_query($conn, $sql);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		$aid = $row['count'] +1;
		
		$name =$_POST['name'];
		$price =$_POST['price'];
		$desc =$_POST['desc'];
		$sale =false;
		if ($_POST['sale'] ==1){
			$sale =true;
		}

		$fileName =$_FILES["art"]["name"];
		$targetFilePath ="artT/". $fileName;
		$tempName =$_FILES["art"]["tmp_name"];
		
		$fileType =pathinfo($targetFilePath, PATHINFO_EXTENSION);
		$allowType =array('jpg', 'png', 'jpeg');
		if (in_array($fileType, $allowType)){
			//upload to server (localhost)
			if (move_uploaded_file($tempName, $targetFilePath)){
				//input filename to db
				$sql ="insert into artwork (aid, uid, file_name, uploaded_on, name, price, description, sale) 
					values ('{$aid}', '{$uid}', '{$fileName}', NOW(), '{$name}', '{$price}', '{$desc}', '{$sale}')";
					
				$result =mysqli_query($conn, $sql);
				echo "<script>alert('Uploaded')</script>";
				header("refresh:2; url=info.php");
			}
		}

	}
	mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
    <body>   
		<form action ="upload.php" method ="POST" enctype="multipart/form-data">
			<input type ="file" name ="art"><br>
			<label> input file name </label><br>
			<input type ="text" name ="name"><br>
			<label> input price </label><br>
			<input type ="number" name ="price"><br>
			<label> input description </label><br>
			<input type ="text" name ="desc"><br>
			
			<input type ="hidden" value =0 name ="sale">
			<input type ="checkbox" value =1 name ="sale">
			<label> For sale? </label><br>
			<input type="submit" value="Submit" name ="submit">
		</form>
		<a href="info.php">back</a>
    </body> 
</html>




