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

// EDI_Common_Utils_splitEDIString() {{{

/**
 * Splits an edi string in an array of tokens.
 * Usage example:
 *
 * <code>
 * require_once('EDI/Common/Utils.php');
 * $tokens = EDI_Common_Utils_splitEDIString(
 *     "some string that will be cut here' but not here?'...",
 *     "'",
 *     "?"
 * );
 * print_r($tokens);
 * // displays:
 * // Array
 * // (
 * //     [0] => some string that will be cut here
 * //     [1] =>  but not here?'...
 * // )
 * </code>
 *
 * @param string $str     The string to split
 * @param string $dm      The delimiter
 * @param string $rc      The release caracter
 * @param bool   $noEmpty If set to true only non-empty pieces are returned
 *
 * @return array
 */
function EDI_Common_Utils_splitEDIString($str, $dm, $rc, $noEmpty=false)
{
    $result = array();
    $buffer = '';
    $count  = strlen($str);
    for ($i=0; $i<$count; $i++) {
        if ($str[$i] == $dm && ($i == 0 || $str[$i-1] != $rc)) {
            if (!$noEmpty || $buffer != '') {
                $result[] = $buffer;
            }
            $buffer = '';
        } else {
            $buffer .= $str[$i];
        }
    }
    if (!$noEmpty || $buffer != '') {
        $result[] = $buffer;
    }
    return $result;
}

// }}}
// EDI_Common_Utils_escapeEDIString() {{{

/**
 * Escapes the string $str by adding the necessary release chars.
 * Usage example:
 *
 * <code>
 * echo EDI_Common_Utils_escapeEDIString("String: ?'+", "?", "'", "+", ":");
 * // displays:
 * // String?: ???'?+
 * </code>
 *
 * @param string $str The string to escape
 * @param string $rc  The release caracter
 * @param string $st  The segment terminator
 * @param string $es  The element separator
 * @param string $ds  The data separator
 * @param string $rs  The repetition separator (optional)
 *
 * @return string
 */
function EDI_Common_Utils_escapeEDIString($str, $rc, $st, $es, $ds, $rs=false)
{
    $s = array($rc, $st, $es, $ds);
    if ($rs) {
        $s[] = $rs;
    }
    $r = array_map(create_function('&$v', 'return "'.$rc.'".$v;'), $s);
    return str_replace($s, $r, $str);
}

// }}}
// EDI_Common_Utils_unescapeEDIString() {{{

/**
 * Unescapes the string $str by removing all release chars.
 * Usage example:
 *
 * <code>
 * echo EDI_Common_Utils_unescapeEDIString("String: ???'?+", "?", "'", "+", ":");
 * // displays:
 * // String: ?'+
 * </code>
 *
 * @param string $str The string to unescape
 * @param string $rc  The release caracter
 * @param string $st  The segment terminator
 * @param string $es  The element separator
 * @param string $ds  The data separator
 * @param string $rs  The repetition separator (optional)
 * 
 * @return string
 */
function EDI_Common_Utils_unescapeEDIString($str, $rc, $st, $es, $ds, $rs=false)
{
    $r = array($rc, $st, $es, $ds);
    if ($rs) {
        $r[] = $rs;
    }
    $s = array_map(create_function('&$v', 'return "'.$rc.'".$v;'), $r);
    return str_replace($s, $r, $str);
}

// }}}
// EDI_Common_Utils_escapeXmlString() {{{

/**
 * Escapes a string to make it a valid xml string.
 * Usage example:
 *
 * <code>
 * require_once 'EDI/Common/Utils.php';
 * echo EDI_Common_Utils_escapeXmlString('a string & another <string>');
 * // diplays:
 * // a string &amp; another &lt;string&gt;
 * </code>
 *
 * @param string $str The raw string
 *
 * @return string The escaped string
 */
function EDI_Common_Utils_escapeXmlString($str)
{
    // must do ampersand first
    $search = array('&', '>', '<', "'", '"');
    $repl   = array('&amp;', '&gt;', '&lt;', '&apos;', '&quot;');
    return str_replace($search, $repl, $str);
}

// }}}
// EDI_Common_Utils_unescapeXmlString() {{{

/**
 * Unescapes an xml string.
 * Usage example:
 *
 * <code>
 * require_once 'EDI/Common/Utils.php';
 * echo EDI_Common_Utils_unescapeXmlString('a string &amp; another &lt;string&gt;');
 * // diplays:
 * // a string & another <string>
 * </code>
 *
 * @param string $str The xml string
 *
 * @return string The escaped string
 */
function EDI_Common_Utils_unescapeXmlString($str)
{
    // must do ampersand last
    $search = array('&gt;', '&lt;', '&apos;', '&quot;', '&amp;');
    $repl   = array('>', '<', "'", '"', '&');
    return str_replace($search, $repl, $str);
}

// }}}
