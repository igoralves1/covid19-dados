<?php declare(strict_types=1);

$countries = [
    'Brazil',
    'Angola',
    'Mozambique',
    'Portugal',
    'Guinea-Bissau',
    'East Timor',
    'Equatorial Guinea3',
    'Macau2',
    'Cape Verde',
    'São Tomé and Príncipe',
];

$source = 'https://raw.githubusercontent.com/CSSEGISandData/COVID-19/master/csse_covid_19_data/csse_covid_19_time_series/time_series_19-covid-Confirmed.csv';
$handle = fopen($source, 'r');
$header = [];

$outputs = array_map(function (string $country): array {
    return [$country, fopen("casos-confirmados-$country.csv", 'w')];
}, $countries);


while ($row = fgetcsv($handle)) {
    if (empty($header)) {
        $header = $row;

        foreach ($outputs as [$country, $output]) {
            fputcsv($output, $header);
        }

        continue;
    }

    foreach ($outputs as [$country, $output]) {
        if ($row[1] === $country) {
            fputcsv($output, $row);
        }
    }
}