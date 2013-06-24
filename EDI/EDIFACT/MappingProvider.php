<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

require_once 'EDI/Logger.php';

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
 * A class to retrieve xml specifications for segments, 
 * composite_data_elements and data_elements.
 *
 * Usage example:
 * <code>
 * require_once 'EDI_EDIFACT_MappingProvider.php';
 * 
 * $spec = EDI_EDIFACT_MappingProvider::find('UNB');
 * // do something with the SimpleXmlElement spec...
 * </code>
 *
 * @category  File_Formats
 * @package   EDI
 * @author    Mark Foster <mark@myndtech.com>
 * @copyright 2013 Mark Foster
 * @license   http://opensource.org/licenses/mit-license.php MIT License 
 * @link      http://en.wikipedia.org/wiki/EDIFACT
 * @link      http://www.unece.org/trade/untdid/welcome.htm
 */
class EDI_EDIFACT_MappingProvider
{
    // Constants {{{

    /**
     * Define the latest directory of specs files.
     */
    const LATEST_DIRECTORY = 'D07A';

    /**
     * Level constants.
     */
    const LEVEL_CODE                   = 'codes';
    const LEVEL_DATA_ELEMENT           = 'data_elements';
    const LEVEL_COMPOSITE_DATA_ELEMENT = 'composite_data_elements';
    const LEVEL_SEGMENT                = 'segments';
    const LEVEL_MESSAGE                = 'messages';

    /**
     * Type constants.
     */
    const TYPE_SERVICE = 'EDI_EDIFACT_Service_';
    const TYPE_USER    = 'EDI_EDIFACT_';

    /**
     * Syntax constants.
     */
    const SYNTAX_V1 = 1;
    const SYNTAX_V2 = 2;
    const SYNTAX_V3 = 3;
    const SYNTAX_V4 = 4;

    // }}}
    // Properties {{{

    /**
     * Array that will act as a cache for opened xml specification files.
     *
     * @var array $xmlspecs
     * @access protected
     * @static
     */
    protected static $xmlspecs = array();

    // }}}
    // find() {{{

    /**
     * Finds the spec node corresponding to the id provided.
     * If a directory is passed, the node will be searched in that directory
     * if it exists, otherwise it will be searched in the directory specified
     * by the constant EDI_EDIFACT_MappingProvider::LATEST_DIRECTORY.
     *
     * @param string $id     Id of the element
     * @param string $dir    An alternate  directory (optional)
     * @param string $syntax An alternate  directory (optional)
     *
     * @access public
     * @static
     * @return SimpleXMLElement
     * @throws EDI_Exception
     */
    public static function find($id, $dir=null, $syntax=null)
    {
        $node = false;
        if (preg_match('/[A-Z]{6}/', $id)) {
            $id = strtolower($id);
            // we have a messages
            if ($id == 'autack' || $id == 'contrl' || $id == 'keyman') {
                // service message
                $node = self::getSpecNode($id, self::LEVEL_MESSAGE,
                    self::TYPE_SERVICE, $dir, $syntax);
            } else {
                // user message
                $node = self::getSpecNode($id, self::LEVEL_MESSAGE,
                    self::TYPE_USER, $dir, $syntax);
            }
        } else if (preg_match('/^([A-Z])[A-Z]{2}$/', $id, $tokens)) { 
            // we have a segment
            $id = strtoupper($id);
            if ($tokens[1] == 'U') {
                // it's a service segment
                $node = self::getSpecNode($id, self::LEVEL_SEGMENT,
                    self::TYPE_SERVICE, $dir, $syntax);
            } else {
                // it's a user segment
                $node = self::getSpecNode($id, self::LEVEL_SEGMENT, 
                    self::TYPE_USER, $dir, $syntax);
            }  
        } else if (preg_match('/^(S|C|E|\d)\d{3}$/', $id, $tokens)) { 
            $id = strtoupper($id);
            if (is_numeric($tokens[1])) { 
                // we have a data element
                if ((int)$id < 1000) {
                    // service data element have id < 1000
                    $node = self::getSpecNode($id, self::LEVEL_DATA_ELEMENT,
                        self::TYPE_SERVICE, $dir, $syntax);
                } else {
                    // it's a user data element
                    $node = self::getSpecNode($id, self::LEVEL_DATA_ELEMENT,
                        self::TYPE_USER, $dir, $syntax);
                }
            } else if ($tokens[1] == 'S') {
                // service composite data element
                $node = self::getSpecNode($id, 
                    self::LEVEL_COMPOSITE_DATA_ELEMENT,
                    self::TYPE_SERVICE, $dir, $syntax);
            } else {
                // user composite data element
                $node = self::getSpecNode($id,
                    self::LEVEL_COMPOSITE_DATA_ELEMENT,
                    self::TYPE_USER, $dir, $syntax);
            }
        }
        if ($node) {
            return $node;
        } else {
            throw new EDI_Exception(
                'no mapping found for identifier "' . $id . '"',
                EDI_Exception::E_EDI_MAPPING_ERROR
            );
        }
    }

    // }}}
    // findCodesForDataElement() {{{

    /**
     * Retrieves the possible codes for the data element idenified by $id.
     *
     * @param string $id     Id of the data element
     * @param string $dir    An alternate  directory (optional)
     * @param string $syntax An alternate syntax (optional)
     *
     * @access public
     * @static
     * @return array An array of SimpleXMLElement objects
     */
    public static function findCodesForDataElement($id, $dir=null, $syntax=null)
    {
        try {
            $id   = strtoupper((string)$id);
            $type = ((int)$id < 1000) ? self::TYPE_SERVICE : self::TYPE_USER;
            return self::getSpecNode($id, self::LEVEL_CODE, $type, $dir, $syntax);
        } catch (EDI_Exception $exc) {
            return false;
        }
    }

    // }}}
    // findCodedValueForDataElement() {{{

    /**
     * Retrieves the coded value for the data element idenified by $id.
     *
     * @param string $id     Id of the data element
     * @param string $dir    An alternate  directory (optional)
     * @param string $syntax An alternate syntax (optional)
     *
     * @access public
     * @static
     * @return array An array of SimpleXMLElement objects
     */
    public static function findCodedValueForDataElement($id, $value, $dir=null, $syntax=null)
    {
        try {
        	$vals = self::findCodesForDataElement($id, $value, $dir, $syntax);
        	if ($vals != null) {
        		foreach($vals->children() as $child) {
        			if ($child['id'] == $value) {
        				return (string)$child['desc'];
        				break;
        			}
        		}
        	}
        	return null;
        } catch (EDI_Exception $exc) {
            return null;
        }
    }

    // }}}
    // getSpecNode() {{{

    /**
     * Static method returning the xml specification node corresponding to the
     * $level, $id, $type and $dir provided.
     *
     * @param string $id     Id of the element
     * @param string $level  Level of the element, can be:
     *                         - self::LEVEL_DATA_ELEMENT
     *                         - self::LEVEL_COMPOSITE_DATA_ELEMENT
     *                         - self::LEVEL_SEGMENT
     * @param string $type   Type, can be one of these two constants:
     *                         - self::TYPE_SERVICE (default)
     *                         - self::TYPE_USER
     * @param string $dir    Defaults to constant self::LATEST_DIRECTORY
     * @param string $syntax Defaults to constant self::SYNTAX_V4
     *
     * @access public
     * @static
     * @return SimpleXMLElement
     * @throws EDI_Exception
     */
    public static function getSpecNode($id, $level, $type=null,
        $dir=null, $syntax=null)
    {
    	//L::debug("getSpecNode " . $id . ' ' . $level . ' ' . $type);
    	
        if (is_dir('@data_dir@')) {
            $basedir = '@data_dir@/';
        } else {
            $basedir = dirname(__FILE__) . '/../../data/';
        }
        // setup defaults
        if ($type === null) {
            $type = self::TYPE_SERVICE;
        }
        $dir = $dir === null ? self::LATEST_DIRECTORY : strtoupper($dir);
        if (defined('self::SYNTAX_V' . $syntax)) {
            $syntax = constant('self::SYNTAX_V' . $syntax);
        } else {
            $syntax = self::SYNTAX_V4;
        }
        // try to retrieve a cached version
        $cacheId = md5($id.$level.$type.$dir.$syntax);
        if (isset(self::$xmlspecs[$cacheId])) {
            return self::$xmlspecs[$cacheId];
        }
        // path to specs
        if ($type == self::TYPE_SERVICE) {
            $f = $basedir . "{$type}V{$syntax}/{$level}";
        } else {
            $f = $basedir . "{$type}{$dir}/{$level}";
        }
        // tweak the path for special cases
        if ($level == self::LEVEL_MESSAGE) {
            $f .=  "/{$id}.xml";
        } else if ($level == self::LEVEL_CODE) {
            if ($type == self::TYPE_SERVICE) {
                $syn = $syntax < 3 ? 3 : $syntax;
                $f   = $basedir . self::TYPE_USER . $dir 
                     . "/service_codes_v{$syn}.xml";
            } else {
                $f = $basedir.self::TYPE_USER.$dir."/codes.xml";
            }
        } else {
            $f .= ".xml";
        }
        // if file is not in cache load it
        if (!file_exists($f)) {
            throw new EDI_Exception(
                'mapping file "' . $f . '" not found',
                EDI_Exception::E_EDI_MAPPING_ERROR
            );
        }
        if (!($xml = simplexml_load_file($f))) {
            throw new EDI_Exception(
                'invalid xml mapping file "' . $f . '"',
                EDI_Exception::E_EDI_MAPPING_ERROR
            );
        }
        // return the right node
        if ($level == self::LEVEL_MESSAGE) {
            return $xml;
        } else if ($level == self::LEVEL_CODE) {
            $root = $xml;
        } else if ($level == self::LEVEL_DATA_ELEMENT) {
            $root = $xml->data_element;
        } else if ($level == self::LEVEL_COMPOSITE_DATA_ELEMENT) {
            $root = $xml->composite_data_element;
        } else {
            $root = $xml->segment;
        }
        foreach ($root as $elt) {
            if ((string)$elt['id'] == $id) {
                self::$xmlspecs[$cacheId] = $elt;
                return $elt;
            }
        }
        throw new EDI_Exception(
            'mapping identifier "' . $id . '" not found',
            EDI_Exception::E_EDI_MAPPING_ERROR
        );
    }

    // }}}
}
