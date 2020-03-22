<?php declare(strict_types=1);

$source = 'https://raw.githubusercontent.com/CSSEGISandData/COVID-19/master/csse_covid_19_data/csse_covid_19_time_series/time_series_19-covid-Confirmed.csv';
$handle = fopen($source, 'r');
$header = [];
$output = fopen('casos-confirmados-brasil.csv', 'w');

while ($row = fgetcsv($handle)) {
    if (empty($header)) {
        $header = $row;
        fputcsv($output, $header);
        continue;
    }

    $country = $row[1];

    if ($country === 'Brazil') {
        fputcsv($output, $row);
    }
}