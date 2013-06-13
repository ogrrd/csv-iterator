csv-iterator
============

CSV Reader to array/object iterator with low memory usage and ease of use.
Mostly useful for importing large CSV files from external APIs.

Installation
------------

Add this to your composer.json:
```json
{
    "repositories": [
        {
            "type": "vcs",
            "url" : "https://github.com/ogrrd/csv-iterator.git"
        }
    ],

    "require": {
        "ogrrd/csv-iterator": "dev-master"
    }
}
```

Usage
-----

Reading data out of a CSV file:
```php
use ogrrd\CsvIterator\CsvIterator;

$pathToFile = '/path/to/file.csv';
$delimiter = ','; // optional
$rows = new CsvIterator($pathToFile, $delimiter);
$rows->useFirstRowAsHeader();
foreach ($rows as $row) {
    // print_r($row);
}
```

Features
--------

* Set names of columns for more readable code
* Use first line of csv file as column names

Todo
----

* Unit tests
