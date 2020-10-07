<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Sample API</title>
</head>
<body>
  <?php
$contents_covid = fopen("https://data.cdc.gov/resource/w9j2-ggv5.json", "r");
$json_covid = stream_get_contents($contents_covid);
fclose($contents_covid);

$data_covid = json_decode($json_covid);

print '<table border=1><tr><th>Year</th><th>Race</th><th>Sex</th><th>Average Life Expectancy</th><th>Mortality</th></tr>';

foreach($data_covid as $data){
	print '<td>'.$data->year.'</td>';
	print '<td>'.$data->race.'</td>';
	print '<td>'.$data->sex.'</td>';
	print '<td>'.$data->average_life_expectancy.'</td>';
	print '<td>'.$data->mortality.'</td></tr>';
}

print '</table>';
?>
</body>
</html>