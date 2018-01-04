<?php
namespace Gary\Protobuf\Generator;

/**
 * Helper class for generating source code comments
 */
class CommentStringBuffer extends CodeStringBuffer
{
    const COMMENT_LINE_PREFIX = ' *';

    public function __construct($tabString = ' ', $newLineString = PHP_EOL)
    {
        parent::__construct($tabString, $newLineString);
    }

    /**
     * Appends new param to docblock
     *
     * @param string $param Param name
     * @param string $value Param value
     *
     * @return CommentStringBuffer
     */
    public function appendParam($param, $value)
    {
        $this->append('@' . $param . ' ' . $value);

        return $this;
    }

    /**
     * Appends new line to docblock
     *
     * @return CommentStringBuffer
     */
    public function newline()
    {
        $this->append('');

        return $this;
    }

    /**
     * Appends new comment line to block
     *
     * @param string $line         Lines to append
     * @param bool   $newline      True to append extra line at the end
     * @param int    $indentOffset Ident offset
     *
     * @return CommentStringBuffer
     */
    public function append($line, $newline = true, $indentOffset = 0)
    {
        $line = trim($line);
        if (strlen($line) > 0) {
            parent::append(
                self::COMMENT_LINE_PREFIX . ' ' . $line, $newline, $indentOffset
            );
        } else {
            parent::append(
                self::COMMENT_LINE_PREFIX, $newline, $indentOffset
            );
        }
        return $this;
    }

    /**
     * Returns buffer as as string
     *
     * @return string
     */
    public function __toString()
    {
        $str =  '/**' . $this->newLineStr .
            parent::__toString() . $this->_getIndentationString() . ' */';
        return $str;
    }
}
