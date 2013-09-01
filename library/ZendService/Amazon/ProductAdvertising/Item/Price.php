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
use ZendService\Amazon\ProductAdvertising\ProductAdvertising;

/**
 *
 * @category Zend
 * @package Zend_Service
 * @subpackage Amazon
 */
class Price
{

    /**
     *
     * @var string
     */
    protected $Amount;

    /**
     *
     * @var string
     */
    protected $CurrencyCode;

    /**
     *
     * @var string
     */
    protected $FormattedPrice;

    /**
     * Assigns values to properties relevant to Price
     *
     * @param DOMElement $dom
     */
    public function __construct(DOMElement $dom)
    {
        $xpath = new DOMXPath($dom->ownerDocument);
        $xpath->registerNamespace('az',
            'http://webservices.amazon.com/AWSECommerceService/' . ProductAdvertising::getVersion());

        if ($xpath->query('./az:Amount/text()', $dom) == null || $xpath->query('./az:Amount/text()', $dom)->item(0) == null) {
            print_r($xpath->query('./az:Amount/text()', $dom)->item(0));
        }
        $this->Amount = (int) $xpath->query('./az:Amount/text()', $dom)->item(0)->data;
        $this->CurrencyCode = $xpath->query('./az:CurrencyCode/text()', $dom)->item(0)->data;
        $this->FormattedPrice = $xpath->query('./az:FormattedPrice/text()', $dom)->item(0)->data;
    }

    /**
     *
     * @return the $Amount
     */
    public function getAmount()
    {
        return $this->Amount;
    }

    /**
     *
     * @return the $CurrencyCode
     */
    public function getCurrencyCode()
    {
        return $this->CurrencyCode;
    }

    /**
     *
     * @return the $FormattedPrice
     */
    public function getFormattedPrice()
    {
        return $this->FormattedPrice;
    }
}
