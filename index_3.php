<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Sample API</title>
</head>
<body>
  <?php
$apikey = 'AASZRPPt6';

$contents_europeana = fopen("http://www.europeana.eu/api/v2/search.json?wskey=$apikey&query=London&reusability=open&media=true", "r");
$json_europeana = stream_get_contents($contents_europeana);
fclose($contents_europeana);

$data_europeana = json_decode($json_europeana);

// Table view of Europeana data
print '<table border=1><tr><th>Title</th><th>Data Provider</th><th>External Link</th><th>Thumbnail</th></tr>';

foreach($data_europeana->items as $item) {
    print '<td><a href="'.$item->guid.'">' .$item->title[0].'</a></td>';
    print '<td>'.$item->dataProvider[0].'</td>';
    print '<td><a href="'.$item->edmIsShownAt[0].'">View at the provider website</a></td>';
    print '<td><a href="'.$item->guid.'"><img src="'.$item->edmPreview[0].'"></a></td></tr>';
}

print '</table>';
?>
</body>
</html>