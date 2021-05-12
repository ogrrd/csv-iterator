<?php

namespace ogrrd\CsvIterator;

class CsvIterator extends \SplFileObject
{
    /**
     * @var array
     */
    private $columnNames = [];

    /**
     * @var bool
     */
    private $firstRowUsedAsColumnNames;

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
        return $this->columnNames;
    }

    /**
     * @param array $names
     *
     * @return CsvIterator
     */
    public function setColumnNames(array $names)
    {
        array_walk($names, function (&$value, $key) {
            if ($value === '') {
                $value = "COL_$key";
            }
        });
        $this->columnNames = $names;

        return $this;
    }

    /**
     * Use the values from the first row as the keys for the remaining rows
     *
     * @return CsvIterator
     */
    public function useFirstRowAsHeader()
    {
        $this->firstRowUsedAsColumnNames = true;

        $this->next();
        $this->setColumnNames(parent::current());

        return $this;
    }

    /**
     * @return array
     */
    public function current()
    {
        $row = parent::current();

        if (!$row) {
            return [];
        }

        // skip first row if names are set by first row of file
        if ($this->firstRowUsedAsColumnNames && $this->key() < 1) {
            $this->next();
            $row = parent::current();
        }

        $rowLength = count($row);
        $namesLength = count($this->columnNames);
        if ($rowLength != $namesLength) {
            if (count(array_filter($row)) && $namesLength > $rowLength) {
                // if there's more column names than data, pad out the data with nulls to match column width
                // ensures there's some data in the row, too
                $row = array_pad($row, count($this->columnNames), null);
            } elseif ($namesLength > 0) {
                // if there's more data than columns, we have unmatchable data so let's skip it
                return [];
            } else {
                // we don't care about matching column widths as there's no column names, so let's return the row indexed numerically
                return $row;
            }
        }

        // combine the data with the keys
        $row = array_combine($this->columnNames, $row);

        return $row;
    }
}
