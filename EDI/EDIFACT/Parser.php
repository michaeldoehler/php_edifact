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
 * Include required classes.
 */
require_once 'EDI/Common/Parser.php';
require_once 'EDI/Common/Utils.php';
require_once 'EDI/EDIFACT/Elements.php';
require_once 'EDI/EDIFACT/MappingProvider.php';
require_once 'EDI/Logger.php';

/**
 * A class to parse the UN/EDIFACT format, it can parse every version of the 
 * UN/EDIFACT directories from 1988 to nowadays and support the UN/EDIFACT 
 * syntax version 1, 2, 3 and 4.
 *
 * Note that you should not instanciate this class directly but use the
 * EDI::parserFactory() method instead.
 *
 * @category  File_Formats
 * @package   EDI
 * @author    Mark Foster <mark@myndtech.com>
 * @copyright 2013 Mark Foster
 * @license   http://opensource.org/licenses/mit-license.php MIT License 
 * @link      http://en.wikipedia.org/wiki/EDIFACT
 * @link      http://www.unece.org/trade/untdid/welcome.htm
 * @see       EDI::parserFactory()
 */
class EDI_EDIFACT_Parser extends EDI_Common_Parser
{
    // Properties {{{

    /**
     * If set to false, the parser will only issue warnings for data type and 
     * length errors.
     *
     * @param bool $strictValidation
     * @access protected
     */
    public $strictValidation = false;

    public $translateCodes = false;

    /**
     * Array that contains various informations about the parsed message:
     *  - syntax_id: the syntax identifier (ex: UNOA)
     *  - syntax_version: the syntax version (ex: V4)
     *  - directory: the edifact directory (ex: D96A)
     *  - message_id: the id of the message (ex: APERAK)
     *
     * @param array $interchangeInfo
     * @access protected
     */
    protected $interchangeInfo = array();

    // }}}
    // __construct {{{

    /**
     * Constructor
     *
     * @param array $params An array of parameters.
     *
     * @access public
     * @return void
     */
    public function __construct($params = array())
    {
        parent::__construct($params);
    }

    // }}}
    // parseString() {{{

    public function setStrictValidation($set) {
    	$this->strictValidation = $set;
    }
    
    /**
     * Parses given edi string and return an EDI_EDIFACT_Document instance or
     * throws an EDI_Exception.
     *
     * @param string $edistring The EDI string
     *
     * @access public
     * @return EDI_EDIFACT_CompositeDataElement
     * @throws EDI_Exception
     */
    public function parseString($edistring)
    {
        $this->buffer          = trim($edistring);
        $this->interchange     = new EDI_EDIFACT_Interchange();
        $this->interchange->id = 'interchange';
        $tokens                = $this->tokenize();
        // parse the service string advice: must be done first !
        // parse the interchange header aka UNB segment, the first token
        $this->interchange[] = $this->parseSegment('UNB', $tokens['interchangeHeader'], true);
        //L::debug($this->interchange->toXml());
        //L::debug(json_encode($tokens));
        // parse functional groups if any
        if (!empty($tokens['groups'])) {
            // if there's one or more functional group, there can't be any 
            // isolated message
            if (!empty($tokens['messages'])) {
                throw new EDI_Exception(
                    'functional groups and messages cannot be mixed in the '
                    . 'same interchange',
                    EDI_Exception::E_EDI_SYNTAX_ERROR
                );
            }
            foreach ($tokens['groups'] as $group) {
                $groupInstance = $this->parseFunctionalGroup($group);
                print_r('GROUP: ' . $groupInstance->toEDI());
                $this->interchange[] = $groupInstance;
            }
        } else {
            if (empty($tokens['messages'])) {
                throw new EDI_Exception(
                    'an interchange must contain at least one message',
                    EDI_Exception::E_EDI_SYNTAX_ERROR
                );
            }
            foreach ($tokens['messages'] as $message) {
            	//var_dump($message);
                $messageInstance     = $this->parseMessage($message);
                $this->interchange[] = $messageInstance;
            }
        }
        // parse the interchange trailer aka UNZ segment, the last token
        $this->interchange[] = $this->parseSegment('UNZ', $tokens['interchangeTrailer'], true);
        
        // return the final document
        return $this->interchange;
    }

    // }}}
    // tokenize() {{{

    /**
     * Parse the edi service string advice aka UNA segment, extract syntax
     * information required for parsing and tokenize the buffer.
     *
     * @access protected
     * @return void
     * @throws EDI_Exception
     */
    protected function tokenize()
    {
        // parse the edi service string advice aka UNA segment
        if (strlen($this->buffer) > 8 && substr($this->buffer, 0, 3) == 'UNA') {
            EDI_EDIFACT_Interchange::$dataSeparator       = $this->buffer[3];
            EDI_EDIFACT_Interchange::$elementSeparator    = $this->buffer[4];
            EDI_EDIFACT_Interchange::$decimalChar         = $this->buffer[5];
            EDI_EDIFACT_Interchange::$releaseChar         = $this->buffer[6];
            EDI_EDIFACT_Interchange::$repetitionSeparator = $this->buffer[7];
            EDI_EDIFACT_Interchange::$segmentTerminator   = $this->buffer[8];
            // remove the string advice
            $this->buffer = trim(substr($this->buffer, 9));
        } else {
            $this->interchange->hasServiceStringAdvice = false;
        }
        // parse the UNB segment to extract syntax id and version
        if (substr($this->buffer, 0, 3) != 'UNB') {
            throw new EDI_Exception(
                'missing or misplaced UNB segment',
                EDI_Exception::E_EDI_SYNTAX_ERROR
            );
        }
        $this->interchangeInfo['directory']        = null;
        $this->interchangeInfo['syntaxIdentifier'] = substr($this->buffer, 4, 4);
        $this->interchangeInfo['syntaxVersion']    = substr($this->buffer, 9, 1);
        // tokenize
        $segments = EDI_Common_Utils_splitEDIString(
            $this->buffer,
            EDI_EDIFACT_Interchange::$segmentTerminator,
            EDI_EDIFACT_Interchange::$releaseChar,
            true
        );
        // initialize tokens variable
        $tokens                = array();
        $tokens['interchange'] = array();
        $interchange           = array();
        $tokens['groups']      = array();
        $currentGroup          = array();
        $tokens['messages']    = array();
        $currentMessage        = array();
        foreach ($segments as $segment) {
            $elements = EDI_Common_Utils_splitEDIString(
                trim($segment),
                EDI_EDIFACT_Interchange::$elementSeparator,
                EDI_EDIFACT_Interchange::$releaseChar
            );
            if (!empty($elements[0])) {
                $id = rtrim(array_shift($elements));
                array_walk(
                    $elements,
                    create_function(
                        '&$v,$k',
                        '$v = EDI_Common_Utils_splitEDIString($v, "'
                         .EDI_EDIFACT_Interchange::$dataSeparator . '","'
                         .EDI_EDIFACT_Interchange::$releaseChar   . '");'
                     )
                );
                if ($id == 'UNB') {
                    // interchange header
                    $tokens['interchangeHeader'] = array(
                        array($id, $elements)
                    ); 
                } else if ($id == 'UNG') {
                    // functional group header
                    $currentGroup[] = array($id, $elements);
                } else if ($id == 'UNH') {
                    // message header
                    $currentMessage[] = array($id, $elements);
                } else if ($id == 'UNT') {
                    // message trailer
                    if (empty($currentMessage)) {
                        throw new EDI_Exception(
                            'unexpected segment UNT',
                            EDI_Exception::E_EDI_SYNTAX_ERROR
                        );
                    }
                    $currentMessage[] = array($id, $elements);
                    if (!empty($currentGroup)) {
                        if (!isset($currentGroup['messages'])) {
                            $currentGroup['messages'] = array();
                        }
                        $currentGroup['messages'][] = $currentMessage;
                    } else {
                        $tokens['messages'][] = $currentMessage;
                    }
                    $currentMessage = array();
                } else if ($id == 'UNE') {
                    // functional group trailer
                    if (empty($currentGroup)) {
                        throw new EDI_Exception(
                            'unexpected segment UNE',
                            EDI_Exception::E_EDI_SYNTAX_ERROR
                        );
                    } else {
                        $currentGroup[]     = array($id, $elements);
                        $tokens['groups'][] = $currentGroup;
                    }
                    $currentGroup = array();
                } else if ($id == 'UNZ') {
                    // interchange trailer
                    if (!isset($tokens['interchangeHeader'])) {
                        throw new EDI_Exception(
                            'unexpected segment UNZ',
                            EDI_Exception::E_EDI_SYNTAX_ERROR
                        );
                    } else {
                        $interchange[]                = array($id, $elements);
                        $tokens['interchangeTrailer'] = array(
                            array($id, $elements)
                        ); 
                    }
                } else {
                    // we are in a message
                    if (empty($currentMessage)) {
                        throw new EDI_Exception(
                            "unexpected segment $id",
                            EDI_Exception::E_EDI_SYNTAX_ERROR
                        );
                    } else {
                        $currentMessage[] = array($id, $elements);
                    } 
                }
            }
        }
        return $tokens;
    }

    // }}}
    // parseFunctionalGroup() {{{

    /**
     * Parse an edifact functional group and return the correponding
     * EDI_EDIFACT_FunctionalGroup object.
     *
     * @param array $data Data of the functional group
     *
     * @access protected
     * @return EDI_EDIFACT_FunctionalGroup
     * @throws EDI_Exception
     */
    protected function parseFunctionalGroup($data)
    {
        $elt = new EDI_EDIFACT_FunctionalGroup();
        // XXX TODO
        return $elt;
    }

    // }}}
    // parseMessage() {{{

    /**
     * Parse an edifact segment (UNB,UNH etc...) and return the correponding
     * EDI_EDIFACT_Segment object.
     *
     * @param array $data Data of the message
     *
     * @access protected
     * @return EDI_EDIFACT_Segment
     */
    protected function parseMessage($data)
    {
        //var_dump($data);
        // parse the header to retrive the message id, the directory and the 
        // syntax version
        $elt = new EDI_EDIFACT_Message();
        if (empty($data)) {
            throw new EDI_Exception(
                'edi file malformed !',
                EDI_Exception::E_EDI_SYNTAX_ERROR
            );
        }
        if (!is_array($data[0]) || !isset($data[0][1][1]) || 
            !is_array($data[0][1][1]) || count($data[0][1][1]) < 3) {
            throw new EDI_Exception(
                'missing or maformed "UNH" segment in message.',
                EDI_Exception::E_EDI_SYNTAX_ERROR
            );
        }
        // message id
        $elt->id = $data[0][1][1][0];
        // interchange directory
        $this->interchangeInfo['directory'] = $data[0][1][1][1].$data[0][1][1][2];
        // load the mapping
        $mapping = EDI_EDIFACT_MappingProvider::find(
            $elt->id,
            $this->interchangeInfo['directory'],
            $this->interchangeInfo['syntaxVersion']
        );
        // children
        $children = $mapping->children();
        $i        = 1;
        $looped	  = 0;
        while (!empty($data)) {
            list($sId,) = $data[0];
            //var_dump($data[0]);
            //if (!isset($children[$i])) continue;
            /**
            echo "i = $i " . PHP_EOL;
            echo "sId = $sId " . PHP_EOL;
            echo "nodeid = " . (string)$children[$i]['id'] . PHP_EOL;
            echo "name = " . (string)$children[$i]->getName() . PHP_EOL;
            **/
            if (!isset($children[$i])) {
                throw new EDI_Exception(
                    sprintf('invalid token "%s" in message "%s"',
                        $sId, $elt->id),
                    EDI_Exception::E_EDI_SYNTAX_ERROR
                );
            }
            $nodeName  = (string)$children[$i]->getName();
            $nodeId    = (string)$children[$i]['id'];
            $req       = (string)$children[$i]['required'] == 'true';
            $maxrepeat = (int)$children[$i]['maxrepeat'];
            if ($nodeName == 'segment') {
                if ($sId != $nodeId) {
                    if ($req) {
                    	// we have not found a required segment node
                    	if ($looped == 2 || $this->strictValidation) {
                    		$looped = 0;
                    	} else {
                    		$len = count($children);
                    		$found = false;
                    		for ($j=0; $j<$len;$j++) {
                    			$jNodeId = $children[$j]['id'];
                    			if ($jNodeId == $sId) {
                    				$i = $j;
                    				$found = true;
                    			}
                    		}
                    		if ($found) {
                    			continue;
                    		}
                    		$looped++;
                    	}
                        throw new EDI_Exception(
                            sprintf('%s "%s" required in message "%s"', $nodeName, $nodeId, $elt->id),
                            EDI_Exception::E_EDI_SYNTAX_ERROR
                        );
                    }
                } else {
                    if ($maxrepeat > 1) {
                        $elt[] = $this->parseSegmentRepetition($sId, $data, $maxrepeat, $req);
                    } else {
                        $elt[] = $this->parseSegment($sId, $data, $req);
                    }
                }
            } else if ($nodeName == 'group') {
                if ($maxrepeat > 1) {
                    $e = $this->parseSegmentGroupRepetition($sId, $data, $children[$i], $maxrepeat, $req);
                } else {
                    $e = $this->parseSegmentGroup($sId, $data, $children[$i], $req);
                }
                if ($e !== null) {
                    $elt[] = $e;
                }
            }
            $i++;
        }
        if (isset($children[$i]) && 
          (string)$children[$i]['required'] == 'true') {
            throw new EDI_Exception(
                sprintf('%s "%s" is required in message "%s"',
                    str_replace('_', ' ', $children[$i]->getName()),
                    (string)$children[$i]['id'], $elt->id),
                EDI_Exception::E_EDI_SYNTAX_ERROR
            );
        }
        $elt->name        = (string)$mapping['name'];
        $elt->description = (string)$mapping['desc'];
        return $elt;
    }

    // }}}
    // parseSegmentGroupRepetition() {{{

    /**
     * Handle an edifact segment group repetition (maxrepeat attribute) and
     * return the corresponding EDI_EDIFACT_SegmentGroupContainer object.
     *
     * @param string           $id        Id of the segment group (SG1, SG2...)
     * @param array            &$data     Array of segments passed by reference
     * @param SimpleXmlElement $mapping   Corresponding mapping node
     * @param int              $maxrepeat Number of times the seg. group can
     *                                    be repeated
     * @param bool             $req       Set this to false if the segment is
     *                                    optional
     *
     * @access protected
     * @return EDI_EDIFACT_SegmentGroupContainer
     * @throws EDI_Exception
     */
    protected function parseSegmentGroupRepetition($id, &$data, $mapping,
        $maxrepeat, $req=true)
    {
        $segmentGroups = array();
        $hasChildren   = false;
        foreach ($data as $sDataItem) {
            if (count($segmentGroups) == $maxrepeat) {
                throw new EDI_Exception(
                    sprintf('segment group "%s" cannot be repeated more than '
                        . '%d times', $id, $maxrepeat),
                    EDI_Exception::E_EDI_SYNTAX_ERROR
                );
            }
            $e = $this->parseSegmentGroup($id, $data, $mapping, $req);
            if ($e === null) {
                break;
            }
            $segmentGroups[] = $e;
            $hasChildren     = true;
            // we have added a segment group, other segment groups are now
            // conditional
            $req = false;
        }
        $segCount = count($segmentGroups);
        if ($segCount == 0 && $req) {
            throw new EDI_Exception(
                "segment group \"$id\" is required.",
                EDI_Exception::E_EDI_SYNTAX_ERROR
            );
        }
        $elt     = new EDI_EDIFACT_SegmentGroupContainer();
        $elt->id = $id . '_container';
        foreach ($segmentGroups as $segmentGroup) {
            $elt[] = $segmentGroup;
        }
        return $hasChildren ? $elt : null;
    }

    // }}}
    // parseSegmentGroup() {{{

    /**
     * Parse an edifact segment group and return the correponding
     * EDI_EDIFACT_SegmentGroup object.
     *
     * @param string           $id      Id of the segment group (SG1, SG2...)
     * @param array            &$data   Array of CDE or data elements
     * @param SimpleXmlElement $mapping Corresponding mapping node
     * @param bool             $req     set this to false if the CDE is optional
     *
     * @access protected
     * @return EDI_EDIFACT_SegmentGroup
     * @throws EDI_Exception
     */
    protected function parseSegmentGroup($id, &$data, $mapping, $req=true)
    {
        if ($req && empty($data)) {
            throw new EDI_Exception(
                "segment group \"$id\" is required.",
                EDI_Exception::E_EDI_SYNTAX_ERROR
            );
        }
        $elt         = new EDI_EDIFACT_SegmentGroup();
        $children    = $mapping->children();
        $hasChildren = false;
        foreach ($children as $node) {
            $nodeName      = (string)$node->getName();
            $nodeId        = (string)$node['id'];
            $nodeReq       = (string)$node['required'] == 'true';
            $nodeMaxRepeat = (int)$node['maxrepeat'];
            if ($nodeName == 'segment') {
                if (empty($data)) {
                    if ($req && $nodeReq) {
                        throw new EDI_Exception(
                            sprintf('segment "%s" is required in group "%s"',
                                $nodeId, $id),
                            EDI_Exception::E_EDI_SYNTAX_ERROR
                        );
                    }
                    break;
                }
                list($sId, $sData) = $data[0];
                if ($sId != $nodeId) {
                    if ($req && $nodeReq) {
                        throw new EDI_Exception(
                            sprintf('segment "%s" is required in group "%s"',
                                $nodeId, $id),
                            EDI_Exception::E_EDI_SYNTAX_ERROR
                        );
                    } else if ($nodeReq) {
                        // we can skip this group...
                        break;
                    }
                    continue;
                }
                if ($nodeMaxRepeat > 1) {
                    $e = $this->parseSegmentRepetition($sId, $data,
                        $nodeMaxRepeat, $nodeReq);
                } else {
                    $e = $this->parseSegment($sId, $data, $nodeReq);
                }
            } else if ($nodeName == 'group') {
                if ($nodeMaxRepeat > 1) {
                    $e = $this->parseSegmentGroupRepetition($nodeId, $data,
                        $node, $nodeMaxRepeat, $nodeReq);
                } else {
                    $e = $this->parseSegmentGroup($nodeId, $data, $node,
                        $nodeReq);
                }
            }
            if ($e !== null) {
                $elt[]       = $e;
                $hasChildren = true;
            }
        }
        $elt->id = $id . '_group';
        return $hasChildren ? $elt : null;
    }

    // }}}
    // parseSegmentRepetition() {{{

    /**
     * Handles an edifact segment repetition (maxrepeat attribute) and return
     * the corresponding EDI_EDIFACT_SegmentRepetition object.
     *
     * @param string $id        Id of the segment (UNB, UNH...)
     * @param array  &$data     Array of segments passed by reference
     * @param int    $maxrepeat Number of times the segment can be repeated
     * @param bool   $req       Set this to false if the segment is optional
     *
     * @access protected
     * @return EDI_EDIFACT_Segment
     * @throws EDI_Exception
     */
    protected function parseSegmentRepetition($id, &$data, $maxrepeat, $req=true)
    {
        $segments = array();
        foreach ($data as $sDataItem) {
            list($sId,) = $sDataItem;
            if ($id == $sId) {
                if (count($segments) == $maxrepeat) {
                    throw new EDI_Exception(
                        sprintf("segment \"%s\" cannot be repeated more "
                            . "than %d times",
                            $id, $maxrepeat),
                            EDI_Exception::E_EDI_SYNTAX_ERROR
                        );
                }
                $segments[] = $this->parseSegment($id, $data, $req);
                // we have added a segment, other segments are now conditional
                $req = false;
            } else {
                break;
            }
        }
        $segCount = count($segments);
        if ($segCount == 0 && $req) {
            throw new EDI_Exception(
                "segment \"$id\" is required.",
                EDI_Exception::E_EDI_SYNTAX_ERROR
            );
        }
        $elt     = new EDI_EDIFACT_SegmentContainer();
        $elt->id = $id . '_container';
        foreach ($segments as $segment) {
            $elt[] = $segment;
        }
        return $elt;
    }

    // }}}
    // parseSegment() {{{

    /**
     * Parses an edifact segment (UNB,UNH etc...) and return the correponding
     * EDI_EDIFACT_Segment object.
     *
     * @param string $id    Id of the segment (UNB, UNH...)
     * @param array  &$data Array of CDE or DE passed by reference
     * @param bool   $req   Set this to false if the CDE is optional
     *
     * @access protected
     * @return EDI_EDIFACT_Segment
     * @throws EDI_Exception
     */
    protected function parseSegment($id, &$data, $req=true)
    {
        if ($req && empty($data)) {
            throw new EDI_Exception(
                "segment \"$id\" is required.",
                EDI_Exception::E_EDI_SYNTAX_ERROR
            );
        }
        $elt     = new EDI_EDIFACT_Segment();
        $mapping = EDI_EDIFACT_MappingProvider::find(
            $id,
            $this->interchangeInfo['directory'],
            $this->interchangeInfo['syntaxVersion']
        );
        // children
        $children          = $mapping->children();
        list($sId, $sData) = $data[0];
        for ($i=0; $i<count($sData); $i++) {
            if (!isset($children[$i])) {
                throw new EDI_Exception(
                    sprintf('invalid token "%s" in segment "%s"', $sData[$i], $id),
                    EDI_Exception::E_EDI_SYNTAX_ERROR
                );
            }
            $eId  = (string)$children[$i]['id'];
            $eReq = (string)$children[$i]['required'] == 'true';
            if ($children[$i]->getName() == 'data_element') {
                $e = $this->parseDataElement($eId, $sData[$i], $eReq);
            } else {
                $e = $this->parseCompositeDataElement($eId, $sData[$i], $eReq);
            }
            $elt[] = $e;
        }
        if (isset($children[$i]) && 
          (string)$children[$i]['required'] == 'true') {
            throw new EDI_Exception(
                sprintf('%s "%s" is required in segment "%s"',
                    str_replace('_', ' ', $children[$i]->getName()),
                    (string)$children[$i]['id'], $id),
                EDI_Exception::E_EDI_SYNTAX_ERROR
            );
        }
        $elt->id          = $id;
        $elt->name        = (string)$mapping['name'];
        $elt->description = (string)$mapping['desc'];
        // remove the segment from the stack
        array_shift($data);
        return $elt;
    }

    // }}}
    // parseCompositeDataElement() {{{

    /**
     * Parses an edi composite data element (CDE) and build the correponding
     * EDI_EDIFACT_CompositeDataElement object.
     *
     * @param string $id   Id of the composite data element (S001, C507...)
     * @param array  $data Array of data element values
     * @param bool   $req  Set this to false if the CDE is optional
     *
     * @access protected
     * @return EDI_EDIFACT_CompositeDataElement
     * @throws EDI_Exception
     */
    protected function parseCompositeDataElement($id, $data, $req=true)
    {
        $elt = new EDI_EDIFACT_CompositeDataElement();
        if (empty($data)) {
            if ($req) {
                throw new EDI_Exception(
                    "composite data element \"$id\" is required",
                    EDI_Exception::E_EDI_SYNTAX_ERROR
                );
            }
            return $elt;
        }
        $mapping  = EDI_EDIFACT_MappingProvider::find(
            $id,
            $this->interchangeInfo['directory'],
            $this->interchangeInfo['syntaxVersion']
        );
        $children = $mapping->children();
        for ($i=0; $i<count($data); $i++) {
            if (!isset($children[$i])) {
                throw new EDI_Exception(
                    sprintf('invalid token "%s" in composite data element "%s"',
                        $data[$i], $id),
                    EDI_Exception::E_EDI_SYNTAX_ERROR
                );
            }
            $eId = (string)$children[$i]['id'];
            $elt[] = $this->parseDataElement($eId, $data[$i]);
        }
        if (isset($children[$i]) && 
          (string)$children[$i]['required'] == 'true') {
              throw new EDI_Exception(
                  sprintf('"%s" is required in composite data element "%s"',
                    (string)$children[$i]['id'], $id),
                  EDI_Exception::E_EDI_SYNTAX_ERROR
            );
        }
        $elt->id          = $id;
        $elt->name        = (string)$mapping['name'];
        $elt->description = (string)$mapping['desc'];
        return $elt;
    }

    // }}}
    // parseDataElement() {{{

    /**
     * Parses an edi data element (DE) and build the correponding
     * EDI_EDIFACT_DataElement object.
     *
     * @param string $id   Id of the data element (S001, C507...)
     * @param string $data Value of the data element
     *
     * @access protected
     * @return EDI_EDIFACT_DataElement
     * @throws EDI_Exception
     */
    protected function parseDataElement($id, $data)
    {
        $mapping   = EDI_EDIFACT_MappingProvider::find(
            $id,
            $this->interchangeInfo['directory'],
            $this->interchangeInfo['syntaxVersion']
        );
        $value     = is_array($data) ? $data[0] : $data;
        $type      = (string)$mapping['type'];
        $length    = isset($mapping['length']) ? (int)$mapping['length']: false;
        $maxlength = isset($mapping['maxlength']) ?
            (int)$mapping['maxlength']: false;
        // validate element value
        $l = strlen($value);
        if ($l > 0 && $length !== false && $l != $length) {
            // wrong length
            $msg = sprintf(
                'data element "%s" length must be "%s", got "%s"', 
                $id, $length, $l
            );
        } else if ($maxlength !== false && $l > $maxlength) {
            // max length exceeded
            $msg = sprintf(
                'data element "%s" length must be lower than "%s", got "%s"',
                $id,
                $maxlength,
                $l
            ); 
        } else if ($type !== false && !empty($value)) {
            $rx = '/[0-9]+'
                .preg_quote(EDI_EDIFACT_Interchange::$decimalChar).'?[0-9]*/';
            if ($type == 'n' && !preg_match($rx, $value, $tokens)) {
                // wrong type
                $msg = sprintf(
                    'data element "%s" must be a numeric string',
                    $id
                );
            } else if ($type == 'a' && preg_match('/[0-9]/', $value)) {
                $msg = sprintf(
                    'data element "%s" must be an alphabetic string',
                    $id
                ); 
            }
        }
        if (isset($msg)) {
            if ($this->strictValidation) {
                throw new EDI_Exception(
                    $msg,
                    E_EDI_SYNTAX_ERROR
                );
            }
        }
        // all is OK
        $elt              = new EDI_EDIFACT_DataElement();
        $elt->value       = $value;
        $elt->id          = $id;
        $elt->name        = (string)$mapping['name'];
        $elt->description = (string)$mapping['desc'];
        
        if ($this->translateCodes) {
        $vals = EDI_EDIFACT_MappingProvider::findCodesForDataElement($id,
							        	$this->interchangeInfo['directory'],
							        	$this->interchangeInfo['syntaxVersion']);
		if ($vals != null) {
	        	foreach($vals->children() as $child) {
	        		//L::debug($child['id'] . $child['desc']);
	        		if ($child['id'] == $value) {
	        			$elt->value = (string)$child['desc'];
	        			break;
	        		}
	        	}
		}        	 
        }
		//}
        return $elt;
    }

    // }}}
}
