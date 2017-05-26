<?php

namespace ogrrd\CsvIterator;

class CsvIterator extends \SplFileObject
{

    /**
     * @var array
     */
    private $names;

    /**
     * @var bool
     */
    private $firstRowUsedAsNames;

    /**
     * @param string $pathToFile
     * @param string $delimiter
     * @param string $fieldEnclosure
     * @param string $escapeChar
     */
    public function __construct($pathToFile, $delimiter = ',', $fieldEnclosure = '"', $escapeChar = '\\')
    {
        parent::__construct($pathToFile, 'r');
        $this->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);
        $this->setCsvControl($delimiter, $fieldEnclosure, $escapeChar);
    }

    /**
     * @return array
     */
    public function getColumnNames()
    {
        return $this->names;
    }

    /**
     * @param array $names
     * @param bool $firstRowUsedAsNames
     *
     * @return CsvIterator
     */
    public function setColumnNames(array $names, $firstRowUsedAsNames = false)
    {
        if ($firstRowUsedAsNames) {
            $this->firstRowUsedAsNames = true;
        }

        array_walk($names, function (&$value, $key) {
            if ($value === '') {
                $value = "COL_$key";
            }
        });
        $this->names = $names;

        return $this;
    }

    /**
     * Use the values from the first row as the keys for the remaining rows
     */
    public function useFirstRowAsHeader()
    {
        $this->next();
        $this->setColumnNames($this->current(), true);
    }

    /**
     * @return boolean|array
     */
    public function current()
    {
        $row = parent::current();

        if (!$this->names || !$row) {
            return $row;
        }

        // skip first row if names are set by first row of file
        if ($this->firstRowUsedAsNames && $this->key() < 1) {
            $this->next();
            $row = parent::current();
        }

        $rowLength = count($row);
        $namesLength = count($this->names);
        if ($rowLength != $namesLength) {
            if (count(array_filter($row)) && $namesLength > $rowLength) {
                // if there's more column names than data, pad out the data with nulls to match column width
                // ensures there's some data in the row, too
                $row = array_pad($row, count($this->names), null);
            } else {
                // if there's more data than columns, we have unmatchable data so let's skip it
                return false;
            }
        }

        // combine the data with the keys
        $row = array_combine($this->names, $row);

        return $row;
    }

}
