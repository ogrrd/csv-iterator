csv-iterator
============

CSV Reader to array/object iterator with low memory usage and ease of use.
Mostly useful for importing large CSV files from external APIs.

Installation
------------

Add this to your composer.json:
```json
{
    "require": {
        "ogrrd/csv-iterator": "dev-master"
    }
}
```

Or just type:
```composer require ogrrd/csv-iterator```

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

* Set array of values to be used as keys for the rows (must cover all columns)
* Use the values from the first row as the keys for the remaining rows

Todo
----

* Unit tests
