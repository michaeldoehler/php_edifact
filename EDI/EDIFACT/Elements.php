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
 * @link      http://en.wikipedia.org/wiki/EDIFACT
 * @link      http://www.unece.org/trade/untdid/welcome.htm
 * @filesource
 */

/**
 * Include the base element classes.
 */
require_once 'EDI/Common/Elements.php';

/**
 * Represents an EDIFACT interchange.
 *
 * @category  File_Formats
 * @package   EDI
 * @author    Mark Foster <mark@myndtech.com>
 * @copyright 2013 Mark Foster
 * @license   http://opensource.org/licenses/mit-license.php MIT License 
 * @link      http://en.wikipedia.org/wiki/EDIFACT
 * @link      http://www.unece.org/trade/untdid/welcome.htm
 */
class EDI_EDIFACT_Interchange extends EDI_Common_CompositeElement
{
    // Properties {{{

    /**
     * Boolean used (internally, but could be used from outside this class if 
     * necessary) to determine if the parsed edifact document had an explicit
     * service string advice.
     *
     * @var bool $hasServiceStringAdvice
     * @access public
     */
    public $hasServiceStringAdvice = true;

    /**
     * The segment terminator, default "'".
     *
     * @var string $segmentTerminator
     * @static
     * @access public
     */
    public static $segmentTerminator = "'";

    /**
     * The element separator, default ":".
     *
     * @var string $elementSeparator
     * @access public
     * @static
     */
    public static $elementSeparator = '+';

    /**
     * The data separator, default "+".
     *
     * @var string $dataSeparator
     * @static
     * @access public
     */
    public static $dataSeparator = ':';

    /**
     * The repetition separator, default "*".
     * Note: this is only used in UN/EDIFACT syntax 4, in earlier versions
     * this part of the UNA segment was reserved for future usage and was set 
     * to a space char.
     *
     * @var string $repetitionSeparator
     * @static
     * @access public
     */
    public static $repetitionSeparator = '*';

    /**
     * The release char, default "?".
     *
     * @var string $releaseChar
     * @static
     * @access public
     */
    public static $releaseChar = '?';

    /**
     * The decimal char, default ".".
     *
     * @var string $decimalChar
     * @static
     * @access public
     */
    public static $decimalChar = '.';

    /**
     * The error message if the interchange is not valid.
     *
     * @var string $decimalChar
     * @see EDI_EDIFACT_Interchange::isValid()
     * @access public
     */
    public $errorMessage = '';

    // }}}
    // __construct {{{

    /**
     * Constructor
     *
     * @param array $params An array of parameters
     *
     * @access public
     * @return void
     */
    public function __construct($params = array())
    {
        parent::__construct($params);
    }

    // }}}
    // loadConfig() {{{

    /**
     * Parses the config file $cfgfile and for each valid entry of the config 
     * file the method tries to assign the value of the entry to the property
     * pointed by the key with the EDI_EDIFACT_Interchange::set() method.
     *
     * @param string $cfgfile path to the config file
     *
     * @access public
     * @return void
     * @throws EDI_Exception
     */
    public function loadConfig($cfgfile)
    {
        if (!file_exists($cfgfile)) {
            throw new EDI_Exception('config file $cfgfile not found.');
        }
        if (!is_readable($cfgfile) || !($fh = fopen($cfgfile, 'r'))) {
            throw new EDI_Exception('config file $cfgfile is not readable.');
        }
        $conf = array();
        while (!feof($fh) && ($l = trim(fgets($fh))) !== false) {
            if (preg_match('/^([^#]+)\s*=\s*([^#]+).*$/', $l, $t)) {
                $conf[trim($t[1])] = trim($t[2]);
            }
        }
        fclose($fh);
    }

    // }}}
    // toEDI() {{{

    /**
     * Returns the edi representation of the interchange.
     *
     * @access public
     * @return string
     */
    public function toEDI()
    {
        if ($this->hasServiceStringAdvice) {
            $str = 'UNA' 
                 . self::$dataSeparator
                 . self::$elementSeparator
                 . self::$decimalChar
                 . self::$releaseChar
                 . self::$repetitionSeparator
                 . self::$segmentTerminator . "\n";
        } else {
            $str = '';
        }
        return $str . parent::toEDI();

    }

    // }}}
    // isValid() {{{

    /**
     * Returns true if the interchange is valid and false otherwise.
     * If not valid an error message is set in the errorMessage property.
     *
     * An optional argument strict can be passed, if set to true the method 
     * will skip type (alpha, numeric, alphanumeric) and length checks.
     * An exemple:
     *
     * <code>
     * $interchange = EDI::interchangeFactory('EDIFACT');
     * // build interchange
     * // [..]
     * if (!$interchange->isValid()) {
     *     fwrite(STDERR, $interchange->errorMessage);
     *     exit(1);
     * }
     * </code>
     *
     * @param bool $strict If set to true type and length checks are skipped
     *
     * @access public
     * @return boolean
     */
    public function isValid($strict=true)
    {
        try {
            $parser = EDI::parserFactory('EDIFACT');
            $parser->parseString($this->toEDI());
        } catch (EDI_Exception $exc) {
            $this->errorMessage = $exc->getMessage();
            return false;
        }
        return true;
    }

    // }}}
}

/**
 * Represents an edifact functional group.
 *
 * @category  File_Formats
 * @package   EDI
 * @author    Mark Foster <mark@myndtech.com>
 * @copyright 2013 Mark Foster
 * @license   http://opensource.org/licenses/mit-license.php MIT License 
 * @link      http://en.wikipedia.org/wiki/EDIFACT
 * @link      http://www.unece.org/trade/untdid/welcome.htm
 */
class EDI_EDIFACT_FunctionalGroup extends EDI_Common_CompositeElement
{
}

/**
 * Represents an edifact message.
 *
 * @category  File_Formats
 * @package   EDI
 * @author    Mark Foster <mark@myndtech.com>
 * @copyright 2013 Mark Foster
 * @license   http://opensource.org/licenses/mit-license.php MIT License 
 * @link      http://en.wikipedia.org/wiki/EDIFACT
 * @link      http://www.unece.org/trade/untdid/welcome.htm
 */
class EDI_EDIFACT_Message extends EDI_Common_CompositeElement
{
}

/**
 * Represents an edifact segment group.
 *
 * @category  File_Formats
 * @package   EDI
 * @author    Mark Foster <mark@myndtech.com>
 * @copyright 2013 Mark Foster
 * @license   http://opensource.org/licenses/mit-license.php MIT License 
 * @link      http://en.wikipedia.org/wiki/EDIFACT
 * @link      http://www.unece.org/trade/untdid/welcome.htm
 */
class EDI_EDIFACT_SegmentGroup extends EDI_Common_CompositeElement
{
}

/**
 * Represents an edifact container.
 *
 * @category  File_Formats
 * @package   EDI
 * @author    Mark Foster <mark@myndtech.com>
 * @copyright 2013 Mark Foster
 * @license   http://opensource.org/licenses/mit-license.php MIT License 
 * @link      http://en.wikipedia.org/wiki/EDIFACT
 * @link      http://www.unece.org/trade/untdid/welcome.htm
 */
abstract class EDI_EDIFACT_Container extends EDI_Common_CompositeElement
{
    // toXml() {{{

    /**
     * Returns the xml representation of the element.
     *
     * @param bool $verbose If set to true xml comments will be included
     * @param int  $indent  The number of spaces for indentation
     *
     * @access public
     * @return string
     */
    public function toXml($verbose = false, $indent = 0)
    {
        $ret = array();
        foreach ($this->children as $child) {
            if ($child instanceof EDI_Common_Element) {
                $ret[] = $child->toXml($verbose, $indent);
            }
        }
        return implode("\n", $ret);
    }

    // }}}
}

/**
 * Represents an edifact segment group container.
 *
 * @category  File_Formats
 * @package   EDI
 * @author    Mark Foster <mark@myndtech.com>
 * @copyright 2013 Mark Foster
 * @license   http://opensource.org/licenses/mit-license.php MIT License 
 * @link      http://en.wikipedia.org/wiki/EDIFACT
 * @link      http://www.unece.org/trade/untdid/welcome.htm
 */
class EDI_EDIFACT_SegmentGroupContainer extends EDI_EDIFACT_Container
{
}

/**
 * Represents an edifact segment container.
 *
 * @category  File_Formats
 * @package   EDI
 * @author    Mark Foster <mark@myndtech.com>
 * @copyright 2013 Mark Foster
 * @license   http://opensource.org/licenses/mit-license.php MIT License 
 * @link      http://en.wikipedia.org/wiki/EDIFACT
 * @link      http://www.unece.org/trade/untdid/welcome.htm
 */
class EDI_EDIFACT_SegmentContainer extends EDI_EDIFACT_Container
{
}

/**
 * Represents an edifact segment.
 *
 * @category  File_Formats
 * @package   EDI
 * @author    Mark Foster <mark@myndtech.com>
 * @copyright 2013 Mark Foster
 * @license   http://opensource.org/licenses/mit-license.php MIT License 
 * @link      http://en.wikipedia.org/wiki/EDIFACT
 * @link      http://www.unece.org/trade/untdid/welcome.htm
 */
class EDI_EDIFACT_Segment extends EDI_Common_CompositeElement
{
    // toEDI() {{{

    /**
     * Returns the edi representation of the element.
     *
     * @access public
     * @return string
     */
    public function toEDI()
    {
        $tokens = array($this->id);
        foreach ($this as $child) {
            $tokens[] = $child === null ? '' : $child->toEDI();
        }
        // remove empty entries at the end
        $i = count($tokens);
        while (--$i) {
            if ($tokens[$i] !== '') {
                break;
            }
        }
        $tokens = array_slice($tokens, 0, $i+1);
        $str    = implode(EDI_EDIFACT_Interchange::$elementSeparator, $tokens);
        return $str . EDI_EDIFACT_Interchange::$segmentTerminator . "\n";
    }

    // }}}
}

/**
 * Represents an edifact composite data element.
 *
 * @category  File_Formats
 * @package   EDI
 * @author    Mark Foster <mark@myndtech.com>
 * @copyright 2013 Mark Foster
 * @license   http://opensource.org/licenses/mit-license.php MIT License 
 * @link      http://en.wikipedia.org/wiki/EDIFACT
 * @link      http://www.unece.org/trade/untdid/welcome.htm
 */
class EDI_EDIFACT_CompositeDataElement extends EDI_Common_CompositeElement
{
    // toEDI() {{{

    /**
     * Returns the edi representation of the element.
     *
     * @access public
     * @return string
     */
    public function toEDI()
    {
        $tokens = array();
        foreach ($this as $child) {
            $tokens[] = $child === null ? '' : $child->toEDI();
        }
        // remove empty entries at the end
        $i = count($tokens);
        while (--$i > 0) {
            if ($tokens[$i] !== '') {
                break;
            }
        }
        $tokens = array_slice($tokens, 0, $i+1);
        return implode(EDI_EDIFACT_Interchange::$dataSeparator, $tokens);
    }

    // }}}
}

/**
 * Represents an edifact data element.
 *
 * @category  File_Formats
 * @package   EDI
 * @author    Mark Foster <mark@myndtech.com>
 * @copyright 2013 Mark Foster
 * @license   http://opensource.org/licenses/mit-license.php MIT License 
 * @link      http://en.wikipedia.org/wiki/EDIFACT
 * @link      http://www.unece.org/trade/untdid/welcome.htm
 */
class EDI_EDIFACT_DataElement extends EDI_Common_Element
{
    // Constants {{{

    /**
     * Type constants
     */
    const TYPE_DATE      = 1; // YYYY/MM/DD
    const TYPE_TIME      = 2; // HH:MM:SS or HH:MM
    const TYPE_TIMESTAMP = 3;

    // }}}
    // getValue() {{{

    /**
     * Returns the value of the element.
     *
     * @access public
     * @return mixed
     */
    public function getValue()
    {
        return EDI_Common_Utils_unescapeEDIString($this->value,
            EDI_EDIFACT_Interchange::$releaseChar,
            EDI_EDIFACT_Interchange::$segmentTerminator,
            EDI_EDIFACT_Interchange::$elementSeparator,
            EDI_EDIFACT_Interchange::$dataSeparator);
    }

    // }}}
    // setValue() {{{

    /**
     * Set the value of the element.
     *
     * @param mixed $value Value to set
     *
     * @access public
     * @return void
     */
    public function setValue($value)
    {
        $this->value = EDI_Common_Utils_escapeEDIString($value,
            EDI_EDIFACT_Interchange::$releaseChar,
            EDI_EDIFACT_Interchange::$segmentTerminator,
            EDI_EDIFACT_Interchange::$elementSeparator,
            EDI_EDIFACT_Interchange::$dataSeparator);
    }

    // }}}
    // toEDI() {{{

    /**
     * Returns the edi representation of the element.
     *
     * @access public
     * @return string
     */
    public function toEDI()
    {
        return $this->value;
    }

    // }}}
    // toXml() {{{

    /**
     * Returns the xml representation of the element.
     *
     * @param bool $verbose If set to true xml comments will be included
     * @param int  $indent  The number of spaces for indentation
     *
     * @access public
     * @return string
     */
    public function toXml($verbose = false, $indent = 0)
    {
        $blank = str_repeat(' ', $indent);
        $cls   = get_class($this);
        $node  = strtolower(substr($cls, strrpos($cls, '_')+1));
        $str   = '';
        if ($verbose && !empty($this->description)) {
            $desc = EDI_Common_Utils_escapeXmlString($this->description);
            $str .= $blank . '<!-- ' . utf8_encode($desc) . ' -->' . "\n";
        }
        $id   = 'e' . strtolower($this->id);
        $str .= sprintf('%s<%s name="%s">%s</%s>',
            $blank,
            $id,
            utf8_encode(EDI_Common_Utils_escapeXmlString($this->name)),
            utf8_encode(EDI_Common_Utils_escapeXmlString($this->getValue())),
            $id);
        return $str;
    }

    // }}}
}
