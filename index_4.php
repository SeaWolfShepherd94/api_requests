<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Sample API</title>
</head>
<body>
  <?php
echo "<table border=1>\n\n";
$f = fopen("https://data.cdc.gov/api/views/xkkf-xrst/rows.csv?accessType=DOWNLOAD&bom=true&format=true%20target=", "r");
while (($line = fgetcsv($f)) !== false) {
        echo "<tr>";
        foreach ($line as $cell) {
                echo "<td>" . htmlspecialchars($cell) . "</td>";
        }
        echo "</tr>\n";
}
fclose($f);
echo "\n</table>";
?>
</body>
</html>