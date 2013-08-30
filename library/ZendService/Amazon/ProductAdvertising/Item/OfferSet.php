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
class OfferSet
{

    /**
     *
     * @var string
     */
    protected $LowestNewPrice;

    /**
     *
     * @var string
     */
    protected $LowestNewPriceCurrency;

    /**
     *
     * @var string
     */
    protected $LowestUsedPrice;

    /**
     *
     * @var string
     */
    protected $LowestUsedPriceCurrency;

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
     *
     * @var array Of Offer
     */
    protected $Offers;

    /**
     * Parse the given Offer Set Element
     *
     * @param DOMElement $dom
     */
    public function __construct(DOMElement $dom)
    {
        $xpath = new DOMXPath($dom->ownerDocument);
        $xpath->registerNamespace('az',
            'http://webservices.amazon.com/AWSECommerceService/' . ProductAdvertising::getVersion());

        $offer = $xpath->query('./az:OfferSummary', $dom);
        if ($offer->length == 1) {
            $lowestNewPrice = $xpath->query('./az:OfferSummary/az:LowestNewPrice/az:Amount', $dom);
            if ($lowestNewPrice->length == 1) {
                $this->LowestNewPrice = (int) $xpath->query('./az:OfferSummary/az:LowestNewPrice/az:Amount/text()',
                    $dom)->item(0)->data;
                $this->LowestNewPriceCurrency = (string) $xpath->query(
                    './az:OfferSummary/az:LowestNewPrice/az:CurrencyCode/text()', $dom)->item(0)->data;
            }
            $lowestUsedPrice = $xpath->query('./az:OfferSummary/az:LowestUsedPrice/az:Amount', $dom);
            if ($lowestUsedPrice->length == 1) {
                $this->LowestUsedPrice = (int) $xpath->query('./az:OfferSummary/az:LowestUsedPrice/az:Amount/text()',
                    $dom)->item(0)->data;
                $this->LowestUsedPriceCurrency = (string) $xpath->query(
                    './az:OfferSummary/az:LowestUsedPrice/az:CurrencyCode/text()', $dom)->item(0)->data;
            }
            $this->TotalNew = (int) $xpath->query('./az:OfferSummary/az:TotalNew/text()', $dom)->item(0)->data;
            $this->TotalUsed = (int) $xpath->query('./az:OfferSummary/az:TotalUsed/text()', $dom)->item(0)->data;
            $this->TotalCollectible = (int) $xpath->query('./az:OfferSummary/az:TotalCollectible/text()', $dom)->item(0)->data;
            $this->TotalRefurbished = (int) $xpath->query('./az:OfferSummary/az:TotalRefurbished/text()', $dom)->item(0)->data;
        }
        $offers = $xpath->query('./az:Offers/az:Offer', $dom);
        if ($offers->length >= 1) {
            foreach ($offers as $offer) {
                $this->Offers[] = new Offer($offer);
            }
        }
    }

    /**
     *
     * @return the $LowestNewPrice
     */
    public function getLowestNewPrice()
    {
        return $this->LowestNewPrice;
    }

    /**
     *
     * @return the $LowestNewPriceCurrency
     */
    public function getLowestNewPriceCurrency()
    {
        return $this->LowestNewPriceCurrency;
    }

    /**
     *
     * @return the $LowestUsedPrice
     */
    public function getLowestUsedPrice()
    {
        return $this->LowestUsedPrice;
    }

    /**
     *
     * @return the $LowestUsedPriceCurrency
     */
    public function getLowestUsedPriceCurrency()
    {
        return $this->LowestUsedPriceCurrency;
    }

    /**
     *
     * @return the $TotalNew
     */
    public function getTotalNew()
    {
        return $this->TotalNew;
    }

    /**
     *
     * @return the $TotalUsed
     */
    public function getTotalUsed()
    {
        return $this->TotalUsed;
    }

    /**
     *
     * @return the $TotalCollectible
     */
    public function getTotalCollectible()
    {
        return $this->TotalCollectible;
    }

    /**
     *
     * @return the $TotalRefurbished
     */
    public function getTotalRefurbished()
    {
        return $this->TotalRefurbished;
    }

    /**
     *
     * @return the $Offers
     */
    public function getOffers()
    {
        return $this->Offers;
    }
}
