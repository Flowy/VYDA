<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>KU2 - Predmety</title>
</head>
<body>
<?php
require_once 'PredmetForm.php';
require_once 'PredmetTable.php';

$db = new mysqli('db4free.net', 'flowyk', 'flowyk', 'vyda');
if (mysqli_connect_errno()) {
    exit('Connect failed: ' . mysqli_connect_error());
}

$form = new PredmetForm($db, $_GET);
print($form->getHtml());

$table = new PredmetTable($db);
print($table->getHtml());

$db->close();
?>


</body>
</html>