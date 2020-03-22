<?php declare(strict_types=1);

$countries = ['Brazil', 'Angola', 'Mozambique', 'Portugal', 'Guinea-Bissau', 'East Timor', 'Equatorial Guinea', 'Macau', 'Cape Verde', 'São Tomé and Príncipe'];
$source = 'https://raw.githubusercontent.com/CSSEGISandData/COVID-19/master/csse_covid_19_data/csse_covid_19_time_series/time_series_19-covid-Confirmed.csv';
$handle = fopen($source, 'r');
$header = [];

$outputs = array_map(function (string $country): array {
    $filename = "casos-confirmados-$country.csv";
    unlink($filename);
    return [$country, fopen($filename, 'w')];
}, $countries);

unlink('casos-confirmados.csv');
$output_all = fopen('casos-confirmados.csv', 'w');

while ($row = fgetcsv($handle)) {
    if (empty($header)) {
        $header = $row;

        foreach ($outputs as [$country, $output]) {
            fputcsv($output, $header);
        }

        fputcsv($output_all, $header);

        continue;
    }

    foreach ($outputs as [$country, $output]) {
        if ($row[1] === $country) {
            fputcsv($output, $row);
        }
    }

    if (in_array($row[1], $countries)) {
        fputcsv($output_all, $row);
    }
}