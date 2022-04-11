<?php
	session_start();
	ob_start();
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
	
    $v = hash('sha256',$_POST['valid']);
    
	if ($row['email_validate'] == $v){
        $sql = "UPDATE user SET email_validated = '$v' WHERE uid = '$uid';";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo'
                <html>
                    <body>
                        <script>
                            if (confirm("Success validated!")) {
                                window.location.href = "info.php"
                            } else {
                                window.location.href = "info.php"
                            }
                        </script>
                    </body>
                </html>
        ';   
        }  
	}else {
		echo'
        <html>
            <body>
                <script>
                    if (confirm("Validation not success! Please try again")) {
                        window.location.href = "validation.php"
                    } else {
                        window.location.href = "validation.php"
                    }
                </script>
            </body>
        </html>
        ';
	}

	#echo $file."<br>";

    
	mysqli_close($conn);
	ob_end_flush();
?>

