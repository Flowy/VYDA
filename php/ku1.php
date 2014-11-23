<?php
/**
 * Created by IntelliJ IDEA.
 * User: Lukas
 * Date: 16. 11. 2014
 * Time: 18:37
 */
require_once "PrintableMap.php";
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>KU1 - CPU filter</title>
</head>
<body>

<?php
$printableMap = new PrintableMap($_GET);
echo $printableMap->getHtml();
?>

<?php
include "cpuFilterForm.html";
?>

</body>
</html>