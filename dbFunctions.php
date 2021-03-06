<?php

function connect() {
	$parameters = file('dbInfo.inc.php');
	$db = new mysqli(trim($parameters[1]), trim($parameters[2]), trim($parameters[3]), trim($parameters[4]));
    return $db;
}

# ensures that proper database and table has been initialized
function ensureTableInit($db) {
	// check that DB and table exist, creates if they don't
	$initFile = 'init.sql';
	$initQuery = file_get_contents($initFile);
	$db->query($initQuery);
}

function checkConnection($db) {
	// check connection for errors 
	if ($db->connect_errno) {
	printf("Connection failed: %s\n", $db->connect_errno);
	exit();
	}
}

// executes package of functions so that each source file need only call one function to get access to MySQL
function getDBAccess() {
	$db = connect();
	checkConnection($db);
	ensureTableInit($db);
	return $db;
}

function echoTableHeader() {
	echo '<thead>';
	echo '<tr>';
	echo '	<th>ID</th> <th>First name</th> <th>Last name</th> <th>Phone</th> <th>Location</th>';
	echo '</tr>';
	echo '</thead>';
}

// displays an employee record
function displayRow($id, $fname, $lname, $phone, $location) {
	$cellTemplate = '<td>%s</td> ';
	echo '<tr>';
	// print a cell containing the ID number along with a radio button that submits the ID number
	printf($cellTemplate, '<input type="radio" name="id" value="'.$id.'">'.$id);
	//printf($cellTemplate, $id);
	// print cells of the other data from the row, as text fields
	printf($cellTemplate, $fname);
	printf($cellTemplate, $lname);
	printf($cellTemplate, $phone);
	printf($cellTemplate, $location); 
	echo '</tr>';
} 

// display employees table
function displayTable() {
	$db = getDBAccess();
	$selectQueryText = "SELECT * FROM employees ORDER BY id;";
	$selectQuery = $db->prepare($selectQueryText);
	$selectQuery->execute();
	$selectQuery->bind_result($id, $fname, $lname, $phone, $location);
	echo '<script type="text/javascript" src="radioFunction.js"></script>';
	echo '<form name="editTable" action="addAndConfirm.php" method="post">';
	echo '<table>';
	echo '<caption>Results</caption>';
	echoTableHeader();
	echo '<tbody>';
	while($selectQuery->fetch()) {
		displayRow($id, $fname, $lname, $phone, $location);
	}
	echo '</tbody>';
	echo '</table>';
	echo '<p>';
	echo '<label><input type="radio" name="function"> Update </label>';
	echo '<label><input type="radio" name="function"> Delete </label>';
	echo '<table>';
	echo '<caption>Data for update</caption>';
	echoTableHeader();
	updatesRow();
	echo '</table>';
	echo '<p><input type="submit" value="Send" onClick="return whichFunction()"></p>';
	echo '</form>';
	echo '<form>';
	echo '<table>';
	echo '<caption>Data to add</caption>';
	echoTableHeader();
	additionRow();
	echo '</table>';
	echo '<input type="submit" value="Send" onClick="add.php">';
	echo '</form>';
}

function updatesRow() {
	$cellTemplate = '<td>%s</td> ';
	echo '<tr>';
	// print a cell containing the ID number along with a radio button that submits the ID number
	printf($cellTemplate, 'ID number');
	//printf($cellTemplate, $id);
	// print cells of the other data from the row, as text fields
	printf($cellTemplate, '<input type="text" onfocus="this.value=\'\'" name="fname" size="30" tabindex="10" value="Insert first name here">');
	printf($cellTemplate, '<input type="text" onfocus="this.value=\'\'" name="lname" size="30" tabindex="20" value="Insert last name here">');
	printf($cellTemplate, '<input type="text" onfocus="this.value=\'\'" name="phone" size="10" tabindex="30" value="Insert phone number here">');
	printf($cellTemplate, '<select name="location" tabindex="40">
	<option value="null">Please select a state</option>
	<option value="New York">New York</option>
	<option value="New Jersey">New Jersey</option>
	<option value="California">California</option>
	</select>'); 
	echo '</tr>';
}

function additionRow() {
	$cellTemplate = '<td>%s</td> ';
	echo '<tr>';
	// print a cell containing the ID number along with a radio button that submits the ID number
	printf($cellTemplate, '<input type="hidden" name="id" value="null">ID number');
	//printf($cellTemplate, $id);
	// print cells of the other data from the row, as text fields
	printf($cellTemplate, '<input type="text" onfocus="this.value=\'\'" name="fname" size="30" tabindex="10" value="Insert first name here">');
	printf($cellTemplate, '<input type="text" onfocus="this.value=\'\'" name="lname" size="30" tabindex="20" value="Insert last name here">');
	printf($cellTemplate, '<input type="text" onfocus="this.value=\'\'" name="phone" size="10" tabindex="30" value="Insert phone number here">');
	printf($cellTemplate, '<select name="location" tabindex="40">
	<option value="null">Please select a state</option>
	<option value="New York">New York</option>
	<option value="New Jersey">New Jersey</option>
	<option value="California">California</option>
	</select>'); 
	echo '</tr>';
}
?>
