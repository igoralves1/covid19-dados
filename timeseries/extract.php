<?php declare(strict_types=1);

$countries = json_decode(file_get_contents(__DIR__ . '/../countries.json'), true);
$country_names = array_map(fn(array $country): string => $country['name'], $countries);

$source = 'https://raw.githubusercontent.com/CSSEGISandData/COVID-19/master/csse_covid_19_data/csse_covid_19_time_series/time_series_19-covid-Confirmed.csv';
$handle = fopen($source, 'r');
$header = [];

$outputs = array_map(function (array $country): array {
    $filename = __DIR__ . "/confirmados/{$country['code']}.csv";

    if (file_exists($filename)) {
        unlink($filename);
    }

    return [$country['name'], fopen($filename, 'w')];
}, $countries);

$filename_all = __DIR__ . '/confirmados/todos.csv';

if (file_exists($filename_all)) {
    unlink($filename_all);
}

$output_all = fopen($filename_all, 'w');

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

    if (in_array($row[1], $country_names)) {
        fputcsv($output_all, $row);
    }
}