<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<title>output</title>
</head>

<body>

<p>
<?php
include 'dbFunctions.php';

// retrieve form data

$id = $_POST['id'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$phone = $_POST['phone'];
$location = $_POST['location'];

// connect to MySQL server and select database 'daedalus'
$our_db = connect();

/* check connection for errors */
if ($our_db->connect_errno) {
    printf("Connection failed: %s\n", $our_db->connect_errno);
    exit();
}

// check that DB and table exist, creates if they don't
ensureInit($our_db);

// prepares check to see if item of given ID even exists
$IDRecord = $our_db->prepare("SELECT * FROM employees WHERE id = $id;");
$IDRecord->execute();
$IDRecord->bind_result($idTest, $fnameTest, $lnameTest, $phoneTest, $locationTest);
$IDRecord->fetch();

// if record exists, deletes row with specified ID, then closes DB connection
if ($idTest==0) {
    echo 'Sorry, that record does not exist.';
    $IDRecord->close();
}
else if ($IDRecord->close() && !$our_db->query("DELETE FROM employees WHERE id=$id")) {
    printf("<br>Error: %s. Request could not be completed.", $our_db->error);
}
else {
    echo 'Your request was completed successfully.';
}
$our_db->close();
?>
</p>

<p>
    <a href="http://validator.w3.org/check?uri=referer"><img
      src="http://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01 Strict" height="31" width="88"></a>
</p>

</body>

</html>
