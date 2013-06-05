<?php

namespace ogrrd\CsvIterator;

class CsvFile implements \IteratorAggregate
{

    /**
     * A full path to CSV file that is being written
     *
     * @var string
     */
    private $pathToFile;

    /**
     * File pointer created by fopen function
     *
     * @var resource
     */
    protected $filePointer;

    /**
     * @var string
     */
    private $fieldDelimiter = ",";

    /**
     * @var string
     */
    private $fieldEnclosure = '"';

    /**
     * @var string
     */
    private $lineTerminator = '\n';

    /**
     * @var string
     */
    private $iteratorClass = 'CsvIterator';

    /**
     * @var boolean
     */
    private $useFirstRowAsHeader = false;
    private $columnNames;

    /**
     * Constructor only takes a full CSV file path and inits class properties
     *
     * @param string $pathToFile
     */
    public function __construct($pathToFile)
    {
        \ini_set('auto_detect_line_endings', true);
        $this->pathToFile = $pathToFile;
    }

    /**
     * @param string $delimiter
     * @return CsvFile
     */
    public function setFieldDelimiter($delimiter)
    {
        $this->fieldDelimiter = $delimiter;
        return $this;
    }

    /**
     * @param string $enclosure
     * @return CsvFile
     */
    public function setFieldEnclosure($enclosure)
    {
        $this->fieldEnclosure = $enclosure;
        return $this;
    }

    /**
     * @param string $terminator
     * @return CsvFile
     */
    public function setLineTerminator($terminator)
    {
        $this->lineTerminator = $terminator;
        return $this;
    }

    /**
     * @param array $names
     * @return CsvFile
     */
    public function setColumnNames(array $names)
    {
        $this->columnNames = $names;
        return $this;
    }

    /**
     * Specify to use first row as header
     *
     * @return \CsvFile
     */
    public function useFirstRowAsHeader()
    {
        $this->useFirstRowAsHeader = true;
        return $this;
    }

    /**
     * Set a custom itertor class to use when looping over the data.
     * This should be a subclass of CSV_FileIterator
     *
     * @param string $className
     * @return CsvFile
     */
    public function setIteratorClass($className)
    {
        $this->iteratorClass = $className;
        return $this;
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getPath();
    }

    /**
     * @return boolean
     */
    public function exists()
    {
        return \file_exists($this->pathToFile);
    }

    /**
     * @return int
     */
    public function getFileSize()
    {
        return $this->exists() ? \filesize($this->pathToFile) : null;
    }

    /**
     * Close file resource (handle) using fclose
     *
     * @return nothing
     */
    public function close()
    {
        if (\is_resource($this->filePointer)) {
            \fclose($this->filePointer);
        }

        return $this;
    }

    /**
     * Get CSV file path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->pathToFile;
    }

    /**
     * @return string
     */
    public function getFieldDelimiter()
    {
        return $this->fieldDelimiter;
    }

    /**
     * @return string
     */
    public function getFieldEnclosure()
    {
        return $this->fieldEnclosure;
    }

    /**
     * @return string
     */
    public function getLineTerminator()
    {
        return $this->lineTerminator;
    }

    /**
     * @return CsvIterator
     */
    public function getIterator()
    {
        if (!$this->exists()) {
            throw new \RuntimeException("The file $this->pathToFile does not exist");
        }
        $class = new $this->iteratorClass($this->getPath(), $this->getFieldDelimiter(), $this->getFieldEnclosure());
        if ($this->columnNames) {
            $class->setColumnNames($this->columnNames);
        } elseif ($this->useFirstRowAsHeader) {
            $class->setColumnNames($class->current(), true);
        }

        return $class;
    }

}
