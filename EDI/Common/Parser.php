<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of the PEAR EDI package.
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to the MIT license that is available
 * through the world-wide-web at the following URI:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category  File_Formats 
 * @package   EDI
 * @author    Mark Foster <mark@myndtech.com>
 * @copyright 2013 Mark Foster
 * @license   http://opensource.org/licenses/mit-license.php MIT License 
 * @link      http://en.wikipedia.org/wiki/Electronic_Data_Interchange
 * @filesource
 */

/**
 * Base abstract class for EDI formats parsers.
 *
 * @category  File_Formats
 * @package   EDI
 * @author    Mark Foster <mark@myndtech.com>
 * @copyright 2013 Mark Foster
 * @license   http://opensource.org/licenses/mit-license.php MIT License 
 * @link      http://en.wikipedia.org/wiki/Electronic_Data_Interchange
 */
abstract class EDI_Common_Parser
{
    // Properties {{{

    /**
     * An instance of EDI_Interchange.
     *
     * @var EDI_Common_CompositeElement $interchange
     *
     * @access protected
     */
    protected $interchange = false;

    /**
     * Buffer containing the string being parsed.
     *
     * @var string $buffer
     *
     * @access protected
     */
    protected $buffer = '';

    // }}}
    // __construct() {{{

    /**
     * Constructor.
     *
     * @param array $params An array of parameters
     *
     * @access public
     * @return void
     */
    public function __construct(Array $params=array())
    {
    }

    // }}}
    // parse() {{{

    /**
     * Parses given edi file and return an EDI_Common_CompositeElement instance
     * or throw an EDI_Exception if the file cannot be found or if an error
     * occurs.
     *
     * @param string $file Path to the edi file
     *
     * @access public
     * @return EDI_Common_CompositeElement the interchange instance
     * @throws EDI_Exception
     */
    public function parse($file)
    {
        if (!file_exists($file) || !is_readable($file)) {
            throw new EDI_Exception(
                'Cannot access edi file "' . $file . '"',
                E_EDI_FILE_NOT_FOUND
            );
        }
        return $this->parseString(file_get_contents($file));
    }

    // }}}
    // parseString() {{{

    /**
     * Parses given edi string and return an EDI_Common_CompositeElement
     * instance or throws an EDI_Exception if an error occurs.
     *
     * @param string $string The EDI string to parse
     *
     * @abstract
     * @access public
     * @return EDI_Common_CompositeElement the interchange instance
     * @throws EDI_Exception
     */
    abstract public function parseString($string);

    // }}}
}
