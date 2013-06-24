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
 * @link      http://pear.php.net/package/EDI
 * @link      http://en.wikipedia.org/wiki/Electronic_Data_Interchange
 * @since     File available since release 0.1.0
 * @filesource
 */

/**
 * Include common functions.
 */
require_once 'EDI/Common/Utils.php';

/**
 * Abstract base class for all EDI elements.
 *
 * @category  File_Formats
 * @package   EDI
 * @author    Mark Foster <mark@myndtech.com>
 * @copyright 2013 Mark Foster
 * @license   http://opensource.org/licenses/mit-license.php MIT License 
 * @link      http://pear.php.net/package/EDI
 * @link      http://en.wikipedia.org/wiki/Electronic_Data_Interchange
 * @since     Class available since release 0.1.0
 */
abstract class EDI_Common_Element
{
    // Properties {{{

    /**
     * Properties array used by overloading.
     *
     * @var array $properties
     * @access protected
     */
    protected $properties = array(
        'id'          => null,
        'name'        => null,
        'description' => null,
        'value'       => null
    );

    // }}}
    // __construct {{{

    /**
     * Constructor.
     *
     * @param array $params An array of parameters
     *
     * @access public
     * @return void
     */
    public function __construct($params = array())
    {
        if (is_array($params)) {
            foreach ($params as $k=>$v) {
                if (property_exists($this, $k)) {
                    $this->$k = $v;
                }
            }
        }
    }

    // }}}
    // toEDI() {{{

    /**
     * Returns the edi representation of the element.
     *
     * @abstract
     * @access public
     * @return string
     */
    abstract public function toEDI();

    // }}}
    // toXml() {{{

    /**
     * Returns the xml representation of the element.
     *
     * @param bool $verbose If set to true xml comments will be included
     * @param int  $indent  The number of spaces for indentation
     *
     * @abstract
     * @access public
     * @return string
     */
    abstract public function toXml($verbose = false, $indent = 0);

    // }}}
    // __get() {{{

    /**
     * Overload method for getting properties.
     *
     * @param string $name Name of property
     *
     * @access public
     * @return mixed
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }
    }

    // }}} 
    // __set() {{{

    /**
     * Overload method for setting properties.
     *
     * @param string $name Name of property
     * @param mixed  $val  Value of property
     *
     * @access public
     * @return void
     */
    public function __set($name, $val)
    {
        $this->properties[$name] = $val;
    }

    // }}} 
    // __isset() {{{

    /**
     * Overload method for checking if the property is set.
     *
     * @param string $name Name of property
     *
     * @access public
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->properties[$name]);
    }

    // }}} 
    // __unset() {{{

    /**
     * Overload method for deleting a property.
     *
     * @param string $name Name of property
     *
     * @access public
     * @return void
     */
    public function __unset($name)
    {
        unset($this->properties[$name]);
    }

    // }}} 
}

/**
 * Abstract base class for EDI composite elements.
 *
 * @category  File_Formats
 * @package   EDI
 * @author    Mark Foster <mark@myndtech.com>
 * @copyright 2013 Mark Foster
 * @license   http://opensource.org/licenses/mit-license.php MIT License 
 * @link      http://en.wikipedia.org/wiki/Electronic_Data_Interchange
 */
abstract class EDI_Common_CompositeElement extends EDI_Common_Element 
    implements ArrayAccess, Countable, RecursiveIterator
{
    // Properties {{{

    /**
     * Array containing children elements.
     *
     * @var array $properties
     * @access public
     */
    protected $children = array();

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
        $str = '';
        foreach ($this->children as $child) {
            if ($child instanceof EDI_Common_Element) {
                $str .= $child->toEDI();
            }
        }
        return $str;
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
        $str = '';
        if ($indent == 0) {
            // root node
            $str .= '<?xml version="1.0" encoding="utf-8"?>' . "\n";
        }
        $blank = str_repeat(' ', $indent);
        $id    = strtolower($this->id);
        $str  .= $blank . '<' . $id;
        if (!empty($this->name)) {
            $name = EDI_Common_Utils_escapeXmlString($this->name);
            $str .= ' name="' . utf8_encode($name) . '"';
        }
        $str .= '>';
        foreach ($this->children as $child) {
            if ($child instanceof EDI_Common_Element) {
                $str .= "\n" . $child->toXml($verbose, $indent+4);
            }
        }
        $str .= "\n" . $blank . '</' . $id . '>';
        return $str;
    }

    // }}}
    // offsetExists() {{{

    /**
     * Implementation of ArrayAccess::offsetExists()
     * 
     * @param int $offset Offset to check
     *
     * @access public
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->children[$offset]);
    }

    // }}}
    // offsetGet() {{{

    /**
     * Implementation of ArrayAccess::offsetGet()
     * 
     * @param int $offset Offset to retrieve
     *
     * @access public
     * @return EDI_Common_Element
     */
    public function offsetGet($offset)
    {
        if (isset($this->children[$offset])) { 
            return $this->children[$offset];
        }
    }

    // }}}
    // offsetSet() {{{

    /**
     * Implementation of ArrayAccess::offsetSet()
     * 
     * @param int                $offset Offset to modity
     * @param EDI_Common_Element $value  Value to set
     *
     * @access public
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (empty($offset)) {
            $offset = count($this->children);
        }
        $this->children[$offset] = $value;
    }

    // }}}
    // offsetUnset() {{{

    /**
     * Implementation of ArrayAccess::offsetUnset()
     * 
     * @param int $offset Offset to delete
     *
     * @access public
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->children[$offset]);
    }

    // }}}
    // count() {{{

    /**
     * Implementation of Countable::count()
     *
     * @access public
     * @return int
     */
    public function count()
    {
        return count($this->children);
    }

    // }}}
    // rewind() {{{

    /**
     * Implementation of Iterator::rewind()
     *
     * @access public
     * @return void
     */
    public function rewind()
    {
        reset($this->children);
    }

    // }}}
    // current() {{{

    /**
     * Implementation of Iterator::current()
     *
     * @access public
     * @return EDI_Common_Element
     */
    public function current()
    {
        return current($this->children);
    }

    // }}}
    // key() {{{

    /**
     * Implementation of Iterator::key()
     *
     * @access public
     * @return int
     */
    public function key()
    {
        return key($this->children);
    }

    // }}}
    // next() {{{

    /**
     * Implementation of Iterator::next()
     *
     * @access public
     * @return EDI_Common_Element
     */
    public function next()
    {
        return next($this->children);
    }

    // }}}
    // valid() {{{

    /**
     * Implementation of Iterator::valid()
     *
     * @access public
     * @return bool
     */
    public function valid()
    {
        return $this->current() !== false;
    }

    // }}}
    // getChildren() {{{

    /**
     * Implementation of RecursiveIterator::getChildren()
     *
     * @access public
     * @return bool
     */
    public function getChildren()
    {
        return $this->current();
    }

    // }}}
    // hasChildren() {{{

    /**
     * Implementation of RecursiveIterator::hasChildren()
     *
     * @access public
     * @return bool
     */
    public function hasChildren()
    {
        return $this->current() instanceof EDI_Common_CompositeElement;
    }

    // }}}
    // find() {{{

    /**
     * Find the EDI_Common_Element matching $value, the method compares first
     * the the element id with the value provided, then, its name.
     *
     * This method always return an array() that can be empty if no matching 
     * elements were found.
     *
     * @param string $value Value to search for
     *
     * @access public
     * @return mixed
     */
    public function find($value)
    {
        $ret = array();
        $it  = new RecursiveIteratorIterator(
            $this,
            RecursiveIteratorIterator::SELF_FIRST
        );
        while ($it->valid()) {
            $child = $it->current();
            if ($child->id == $value || $child->name == $value) {
                $ret[] = $child;
            }
            $it->next();
        }
        return $ret;
    }

    // }}} 
}
