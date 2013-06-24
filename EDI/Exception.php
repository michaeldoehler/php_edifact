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
 * Include the PEAR_Exception class.
 */
require_once 'PEAR/Exception.php';

/**
 * Class for exceptions raised by this package.
 *
 * @category  File_Formats
 * @package   EDI
 * @author    Mark Foster <mark@myndtech.com>
 * @copyright 2013 Mark Foster
 * @license   http://opensource.org/licenses/mit-license.php MIT License 
 * @link      http://en.wikipedia.org/wiki/Electronic_Data_Interchange
 */
class EDI_Exception extends PEAR_Exception
{
    // constants {{{

    /**#@+
     * Exception codes constants defined by this package.
     */
    const E_EDI_FILE_NOT_FOUND   = 1;
    const E_UNSUPPORTED_STANDARD = 2;
    const E_UNSUPPORTED_MESSAGE  = 3;
    const E_EDI_MAPPING_ERROR    = 4;
    const E_EDI_SYNTAX_ERROR     = 5;
    /**#@-*/
     
    // }}}
}
