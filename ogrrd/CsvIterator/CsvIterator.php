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
        $this->setFlags(\SplFileObject::READ_CSV);
        $this->setCsvControl($delimiter, $fieldEnclosure, $escapeChar);
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
        $this->names = $names;

        return $this;
    }

    /**
     * Use the values from the first row as the keys for the remaining rows
     */
    public function useFirstRowAsHeader()
    {
        $this->setColumnNames($this->current(), true);
    }

    /**
     * @return null|array
     */
    public function current()
    {
        $row = parent::current();
        if ($this->names) {
            // skip first row if names are set by first row of file
            if ($this->firstRowUsedAsNames && $this->key() < 1) {
                $this->next();
            }
            if (count($row) != count($this->names)) {
                return [];
            }
            $row = array_combine($this->names, $row);
        }

        return $row;
    }

}
