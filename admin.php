<?php
	session_start();
?>

<?php
	$conn = @mysqli_connect("localhost","root","","comp3334");
	if (mysqli_connect_errno()) {
		die("Failed to connect to MySQL: " . mysqli_connect_error());
	}
	if (isset($_POST['approval'])){
		$aid =$_POST['aid'];
		if ($_POST['approval'] =='yes'){
			$sql ="update artwork set approve ='accepted' where aid ='{$aid}'";
			$result = mysqli_query($conn, $sql);
		} else {
			$sql ="update artwork set approve ='rejected' where aid ='{$aid}'";
			$result = mysqli_query($conn, $sql);
		}
	}
?>

<?php
	#$uid =$_SESSION['uid'];
	
	$sql ="SELECT * FROM artwork where approve ='waiting'";
	
	$result = mysqli_query($conn, $sql);
	$table ="<table id='table' border='1'>
			<tr> 
			<th>AID</th>
			<th>artwork</th>
			<th>justification</th>
			<th>approve</th></tr>";
			
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		$file ="artT/".$row['file_name'];
		$justification ="justification/".$row['justification'];
		
		$table .="<tr>
					<td>{$row['aid']}</td>
					<td><div class='art' style='background-image: url(".$file.")'> </div></td>
					<td><iframe src=\"".$justification."\" width=\"100%\" style=\"height:100%\"></iframe></td>
					<td><form action='' method ='POST' >
							<input type ='hidden' name ='aid' value ='".$row['aid']."'>
							<input type ='radio' name ='approval' value ='no'> <label> rejected</label> <br>
							<input type ='radio' name ='approval' value ='yes'><label> accept</label> <br>
							<input type ='submit' name ='submit' value ='Submit'>
						</form>
					</td>
				</tr>";
	}				
			
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="info.css">
	</head>
    <body>   
		<a href="logout.php">Log out</a>
		<?php echo $table; ?>
    </body> 
</html>