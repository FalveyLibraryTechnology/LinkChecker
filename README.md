# LinkChecker
Simple PHP link checker. Takes URLs from an input CSV, checks their HTTP status, and writes an output CSV with URLs and statuses (or exception messages, in case of trouble).

## Setup
Run "composer install" to load dependencies.

## Usage
php check.php [input CSV] [output CSV] [index of URL in input CSV] [CSV delimiter character]
