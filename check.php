<?php

require_once 'vendor/autoload.php';

$infile = $argv[1] ?? null;
$outfile = $argv[2] ?? null;
$index = $argv[3] ?? 0;
$delimiter = $argv[4] ?? ',';
if (empty($outfile)) {
    die("Must specify output file. Syntax: {$argv[0]} [input csv] [output csv] [index of URL] [CSV delimiter]\n");
}
if (!file_exists($infile)) {
    die("Cannot open $infile");
}
$checked = [];
if (file_exists($outfile)) {
    $tmphandle = fopen($outfile, 'r');
    while ($line = fgetcsv($tmphandle)) {
        $checked[] = $line[0];
    }
    fclose($tmphandle);
}
$handle = fopen($infile, 'r');
$target = fopen($outfile, 'a');
while ($line = fgetcsv($handle, 0, $delimiter)) {
    $url = trim($line[$index]);
    if (empty($url)) {
        // skip empty URLs.
    } elseif (in_array($url, $checked)) {
        echo "$url already checked; skipping...\n";
    } else {
        check($url, $target);
        $checked[] = $url;
    }
}
fclose($handle);
fclose($target);

function check($url, $target)
{
    echo "Checking $url... ";
    try {
        $client = new \Zend\Http\Client();
        $client->setUri($url);
        $response = $client->send();
        $code = $response->getStatusCode();
    } catch (\Exception $e) {
        $code = "Exception: " . $e->getMessage();
    }
    echo "$code\n";
    fputcsv($target, [$url, $code]);
}
