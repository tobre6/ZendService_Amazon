<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Service
 */
namespace ZendService\Amazon\ProductAdvertising\Item;

use DOMElement;
use DOMXPath;
use Zend\Uri;
use ZendService\Amazon\ProductAdvertising\ProductAdvertising;

/**
 *
 * @category Zend
 * @package Zend_Service
 * @subpackage Amazon
 */
class ItemLink
{

    /**
     *
     * @var string
     */
    protected $Description;

    /**
     *
     * @var \Zend\Uri\Uri
     */
    protected $URL;

    /**
     * Assigns values to properties relevant to Attributes
     *
     * @param DOMElement $dom
     */
    public function __construct(DOMElement $dom)
    {
        $xpath = new DOMXPath($dom->ownerDocument);
        $xpath->registerNamespace('az',
            'http://webservices.amazon.com/AWSECommerceService/' . ProductAdvertising::getVersion());

        $this->Description = (string) $xpath->query('./az:Description/text()', $dom)->item(0)->data;
        $this->URL = Uri\UriFactory::factory($xpath->query('./az:URL/text()', $dom)->item(0)->data);
    }

    public function getDescription()
    {
        return $this->Description;
    }

    public function getURL()
    {
        return $this->URL;
    }
}
