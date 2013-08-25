<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Service
 */
namespace ZendService\Amazon\ProductAdvertising;

use DOMDocument;
use DOMXPath;

/**
 *
 * @category Zend
 * @package Zend_Service
 * @subpackage Amazon
 */
class ItemResultSet implements \SeekableIterator
{

    /**
     * A DOMNodeList of <Item> elements
     *
     * @var \DOMNodeList
     */
    protected $results = null;

    /**
     * Amazon Web Service Return Document
     *
     * @var DOMDocument
     */
    protected $dom;

    /**
     * XPath Object for $this->_dom
     *
     * @var DOMXPath
     */
    protected $xpath;

    /**
     * Current index for SeekableIterator
     *
     * @var int
     */
    protected $currentIndex = 0;

    /**
     * Create an instance of Zend_Service_Amazon_ResultSet and create the necessary data objects
     *
     * @param DOMDocument $dom
     * @return void
     */
    public function __construct(DOMDocument $dom)
    {
        $this->dom = $dom;
        $this->xpath = new DOMXPath($dom);
        $this->xpath->registerNamespace('az',
            'http://webservices.amazon.com/AWSECommerceService/' . ProductAdvertising::getVersion());
        $this->results = $this->xpath->query('//az:Item');
    }

    /**
     * Total Number of results returned
     *
     * @return int Total number of results returned
     */
    public function totalResults()
    {
        $result = $this->xpath->query('//az:TotalResults/text()');

        return (int) (isset($result->item(0)->data) ? $result->item(0)->data : 0);
    }

    /**
     * Total Number of pages returned
     *
     * @return int Total number of pages returned
     */
    public function totalPages()
    {
        $result = $this->xpath->query('//az:TotalPages/text()');

        return (int) (isset($result->item(0)->data) ? $result->item(0)->data : 0);
    }

    /**
     * Implement SeekableIterator::current()
     *
     * @return Item
     */
    public function current()
    {
        $dom = $this->results->item($this->currentIndex);
        if ($dom === null) {
            throw new Exception\RuntimeException('no results found');
        }

        return new Item($dom);
    }

    /**
     * Implement SeekableIterator::key()
     *
     * @return int
     */
    public function key()
    {
        return $this->currentIndex;
    }

    /**
     * Implement SeekableIterator::next()
     *
     * @return void
     */
    public function next()
    {
        $this->currentIndex++;
    }

    /**
     * Implement SeekableIterator::rewind()
     *
     * @return void
     */
    public function rewind()
    {
        $this->currentIndex = 0;
    }

    /**
     * Implement SeekableIterator::seek()
     *
     * @param int $index
     * @throws Exception\OutOfBoundsException
     * @return void
     */
    public function seek($index)
    {
        $indexInt = (int) $index;
        if ($indexInt >= 0 && (null === $this->results || $indexInt < $this->results->length)) {
            $this->currentIndex = $indexInt;
        } else {
            throw new Exception\OutOfBoundsException("Illegal index '$index'");
        }
    }

    /**
     * Implement SeekableIterator::valid()
     *
     * @return boolean
     */
    public function valid()
    {
        return null !== $this->results && $this->currentIndex < $this->results->length;
    }

    public function asXml()
    {
        return $this->dom->saveXML();
    }
}
