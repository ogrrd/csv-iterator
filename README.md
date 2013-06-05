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
    $pathToFile = '/path/to/file.csv';
    $file = new CsvFile($pathToFile);
    $file->setFieldDelimiter('|'); // optional 
    foreach ($file as $row) {
        // do_something_with($row['name'], $row['age']);
    }
```

Features
--------

* Set names of columns for more readable code
* Use first line of csv file as column names
* Uses a ``SplFileObject`` as an iterator, which can be subclassed or overridden for custom behaviour.

Todo
----

* Working unit tests (I broke them by removing all the functionality most of them test!)
