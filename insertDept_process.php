<?php

include("header.php"); 

if(isset($_POST['s'])) {		
	$dn = $_POST['DepartmentName'];
	$dp = $_POST['DepartmentPhone'];
	$on = $_POST['OfficeNumber'];
	$bc = $_POST['BudgetCode'];
	$pattern = "/\d{3}-\d{3}-\d{4}/";
	$pattern2 = "/^BC-\d{3}-10$/";

	if(empty($dn))  // enter department name  
	{
		echo "Please enter a <b>Department Name</b>  <br>";
	}
	elseif(empty($on))  // enter OfficeNumber
	{
		echo "Please enter an <b>Office Number</b>  <br>";
	}
	elseif(empty($bc))  // enter BudgetCode
	{
		echo "Please enter a <b>Budget Code</b>  <br>";
	}	
	elseif(!preg_match($pattern2, $bc ))  // BudgetCode number format
	{
		echo "<b>BudgetCode </b> not in proper format <br>";
	}	
	elseif(empty($dp))  // enter DepartmentPhone
	{
		echo "Please enter a <b>Department Phone Number</b>  <br>";
	}
	elseif(!preg_match($pattern, $dp ))  // phone number format
	{
		echo "<b>Department Phone Number</b> not in proper format <br>";
	}
	elseif(strlen($dp)<12)   // check length <12 digit error >12 digit error
	{
		echo "Not enough digits or dashes in <b>Department Phone Number</b> <br> ";
	}
	elseif(strlen($dp)>12)  
	{
		echo " Too many digits or dashes in <b>Department Phone Number</b> ";
	} 
	else{ 
		echo "<p>Your department <b>$dn</b> was successfully added.</p>";
	}	

	$DBConnect = @mysqli_connect('127.0.0.1:3306', 'root'); // ip of DB and credentials
	if ($DBConnect === FALSE) // this will display MySQL errors if a connection cannot be made.
		echo "<p>Unable to connect to the database server.</p>". "<p>Error code " . mysqli_errno($DBConnect). ": " . mysqli_error($DBConnect) . "</p>";
	else {
		$DBName = "wp"; 
		// SCHEMA name of db. if your Wedgewood Pacific DB is different, put that name here or the code will not work locally
		if (!@mysqli_select_db($DBConnect, $DBName))
		echo "<p>Cannot open the Wedgewood Pacific database schema! Is it called 'wp' on your host? </p>";
			else {    
			$TableName = "Department"; 
			$col_0="DepartmentName"; 
			$col_1="DepartmentPhone";
			$col_2="OfficeNumber";
			$col_3="BudgetCode";

			$SQLstring = "INSERT INTO $TableName ($col_0, $col_1, $col_2, $col_3) VALUES('$dn', '$dp', '$on', '$bc')";
			$QueryResult = @mysqli_query($DBConnect, $SQLstring);				
			$SQLstring = "SELECT * FROM $TableName;"; 
		
	//Generate the HTML for the Table
	$QueryResult = @mysqli_query($DBConnect, $SQLstring);
			if (mysqli_num_rows($QueryResult) == 0)
					echo "<p>There are no entries in the table $TableName.</p>"; 
				else {
					echo "<p> Here is the updated content of the $TableName table:</p>"; 
					echo "<table><tr>"; //begin table 
					echo "<th>$col_0</th>"; //these are the headers, match the # of cols in table 
					echo "<th>$col_1</th>";
					echo "<th>$col_2</th>"; 
					echo "<th>$col_3</th>"; 
	
	while ($Row = mysqli_fetch_assoc($QueryResult)) { //creates associative array of data
					echo "<tr>";
					echo "<td>{$Row[$col_0]}</td>"; // similar to above, match # of cols in table
					echo "<td>{$Row[$col_1]}</td>";
					echo "<td>{$Row[$col_2]}</td>";
					echo "<td>{$Row[$col_3]}</td>";                    
					echo "</tr>";
				}
			} 		mysqli_free_result($QueryResult); //Fetch rows from a result-set, then free the memory associated with the result:
		}
	}
}
?>	