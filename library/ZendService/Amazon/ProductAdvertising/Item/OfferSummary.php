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
class OfferSummary
{

    /**
     *
     * @var Price
     */
    protected $LowestNewPrice;

    /**
     *
     * @var Price
     */
    protected $LowestUsedPrice;

    /**
     *
     * @var int
     */
    protected $TotalNew;

    /**
     *
     * @var int
     */
    protected $TotalUsed;

    /**
     *
     * @var int
     */
    protected $TotalCollectible;

    /**
     *
     * @var int
     */
    protected $TotalRefurbished;

    /**
     * Parse the given Offer element
     *
     * @param DOMElement $dom
     */
    public function __construct(DOMElement $dom)
    {
        $xpath = new DOMXPath($dom->ownerDocument);
        $xpath->registerNamespace('az',
            'http://webservices.amazon.com/AWSECommerceService/' . ProductAdvertising::getVersion());

        $price = $xpath->query('./az:LowestNewPrice', $dom);
        if ($price->length >= 1) {
            $this->LowestNewPrice = new Price($price->item(0));
        }

        $price = $xpath->query('./az:LowestUsedPrice', $dom);
        if ($price->length >= 1) {
            $this->LowestUsedPrice = new Price($price->item(0));
        }

        $map = array(
            'TotalNew',
            'TotalUsed',
            'TotalCollectible',
            'TotalRefurbished',
        );
        foreach ($map as $el) {
            $element = $xpath->query("./az:$el/text()", $dom);
            if ($element->length >= 1) {
                $this->$el = (int) $element->item(0)->data;
            }
        }
    }

	/**
     * @return Price
     */
    public function getLowestNewPrice()
    {
        return $this->LowestNewPrice;
    }

	/**
     * @return Price
     */
    public function getLowestUsedPrice()
    {
        return $this->LowestUsedPrice;
    }

	/**
     * @return the $TotalNew
     */
    public function getTotalNew()
    {
        return $this->TotalNew;
    }

	/**
     * @return the $TotalUsed
     */
    public function getTotalUsed()
    {
        return $this->TotalUsed;
    }

	/**
     * @return the $TotalCollectible
     */
    public function getTotalCollectible()
    {
        return $this->TotalCollectible;
    }

	/**
     * @return the $TotalRefurbished
     */
    public function getTotalRefurbished()
    {
        return $this->TotalRefurbished;
    }


}
