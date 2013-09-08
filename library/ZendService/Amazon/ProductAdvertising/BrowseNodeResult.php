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
use ZendService\Amazon\ProductAdvertising\Item\BrowseNode;

/**
 *
 * @category Zend
 * @package Zend_Service
 * @subpackage Amazon
 */
class BrowseNodeResult
{

    /**
     * A DOMNodeList of <Item> elements
     *
     * @var \DOMNodeList
     */
    protected $BrowseNode;

    protected $item;

    /**
     * Amazon Web Service Return Document
     *
     * @var DOMDocument
     */
    protected $dom;

    /**
     * Create an instance of Zend_Service_Amazon_BrowseNode and create the necessary data objects
     *
     * @param DOMDocument $dom
     * @return void
     */
    public function __construct(DOMDocument $dom)
    {
        $this->dom = $dom;
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('az',
            'http://webservices.amazon.com/AWSECommerceService/' . ProductAdvertising::getVersion());

        /*$item = $this->xpath->query('//az:Item');
        $this->item = new Item($item);*/
        $browseNode = $this->xpath->query('//az:BrowseNodes/BrowseNode');
        $this->BrowseNode = new BrowseNode($browseNode);
    }

    public function asXml()
    {
        return $this->dom->saveXML();
    }
}
