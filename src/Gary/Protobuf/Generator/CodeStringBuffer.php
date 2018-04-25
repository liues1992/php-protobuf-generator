<?php
namespace Gary\Protobuf\Generator;

/**
 * Helper class for generating source code
 */
class CodeStringBuffer
{
    private $_buffer = array();
    private $_indentLevel = 0;

    protected $indentStr = '';
    protected $newLineStr = '';

    /**
     * Constructs new code buffer
     *
     * @param string $tabString     String used for indentation
     * @param string $newLineString String used as new line
     */
    public function __construct($tabString = ' ', $newLineString = PHP_EOL)
    {
        $this->indentStr = $tabString;
        $this->newLineStr = $newLineString;
    }

    /**
     * Adds new line to buffer
     *
     * @return CodeStringBuffer
     */
    public function newline()
    {
        $this->append('');
        return $this;
    }

    /**
     * Appends lines to buffer
     *
     * @param string $lines        Lines to append
     * @param bool   $newline      Add extra newline after lines
     * @param int    $indentOffset Extra indent code
     *
     * @return CodeStringBuffer
     */
    public function append($lines, $newline = true, $indentOffset = 0)
    {
        $this->_buffer[] = trim($lines) ? ($this->_getIndentationString($indentOffset) . $lines) : $lines;
        if ($newline) {
            $this->_buffer[] = $this->newLineStr;
        }

        return $this;
    }

    /**
     * Increases indentation
     *
     * @param int $i
     *
     * @return CodeStringBuffer
     */
    public function incrIndentation($i = 1)
    {
        $this->_indentLevel += $i;

        return $this;
    }

    /**
     * Decreases indentation
     *
     * @param int $i
     *
     * @return CodeStringBuffer
     */
    public function decrIndentation($i = 1)
    {
        $this->_indentLevel = max(0, $this->_indentLevel - $i);

        return $this;
    }

    /**
     * Returns indentation string
     *
     * @param int $indentOffset Offset
     *
     * @return string
     */
    protected function _getIndentationString($indentOffset = 0)
    {
        return str_repeat($this->indentStr, $this->_indentLevel + $indentOffset);
    }

    /**
     * Returns buffer as string
     *
     * @return string
     */
    public function __toString()
    {
        return implode("", $this->_buffer);
    }

    /**
     * @return int
     */
    public function getIndentLevel()
    {
        return $this->_indentLevel;
    }

    /**
     * @param int $indentLevel
     */
    public function setIndentLevel($indentLevel)
    {
        $this->_indentLevel = $indentLevel;
    }

    public function alignWithBuffer(CodeStringBuffer $b)
    {
        $this->_indentLevel = $b->getIndentLevel();
    }
}

