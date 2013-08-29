<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Service
 */

namespace ZendService\Amazon\ProductAdvertising\Item\Attributes;

use DOMElement;
use DOMXPath;
use ZendService\Amazon\ProductAdvertising\ProductAdvertising;

/**
 * @category   Zend
 * @package    Zend_Service
 * @subpackage Amazon
 */
class ListPrice
{
    /**
     * @var string
     */
    public $Amount;

    /**
     *
     * @var string
     */
    public $CurrencyCode;

    /**
     *
     * @var string
     */
    public $FormattedPrice;

    /**
     * Assigns values to properties relevant to ListPrice
     *
     * @param DOMElement $dom
     */
    public function __construct(DOMElement $dom)
    {
        $xpath = new DOMXPath($dom->ownerDocument);
        $xpath->registerNamespace('az', 'http://webservices.amazon.com/AWSECommerceService/' . ProductAdvertising::getVersion());

        $this->Amount = (int) $xpath->query('./az:Amount/text()', $dom)->item(0)->data;
        $this->CurrencyCode  = $xpath->query('./az:CurrencyCode/text()', $dom)->item(0)->data;
        $this->FormattedPrice  = $xpath->query('./az:FormattedPrice/text()', $dom)->item(0)->data;
    }
}
