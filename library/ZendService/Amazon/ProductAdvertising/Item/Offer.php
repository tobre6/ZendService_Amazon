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
use DOMText;
use DOMXPath;
use ZendService\Amazon\ProductAdvertising\ProductAdvertising;

/**
 *
 * @category Zend
 * @package Zend_Service
 * @subpackage Amazon
 */
class Offer
{

    /**
     *
     * @var string
     */
    protected $MerchantId;

    /**
     *
     * @var string
     */
    protected $MerchantName;

    /**
     *
     * @var string
     */
    protected $GlancePage;

    /**
     *
     * @var string
     */
    protected $Condition;

    /**
     *
     * @var string
     */
    protected $OfferListingId;

    /**
     *
     * @var string
     */
    protected $Price;

    /**
     *
     * @var string
     */
    protected $CurrencyCode;

    /**
     *
     * @var string
     */
    protected $Availability;

    /**
     *
     * @var boolean
     */
    protected $IsEligibleForSuperSaverShipping = false;

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

        $map = array(
            'MerchantId' => './az:Merchant/az:MerchantId/text()',
            'MerchantName' => './az:Merchant/az:Name/text()',
            'GlancePage' => './az:Merchant/az:GlancePage/text()',
            'Condition' => './az:OfferAttributes/az:Condition/text()',
            'OfferListingId' => './az:OfferListing/az:OfferListingId/text()',
            'Price' => './az:OfferListing/az:Price/az:Amount/text()',
            'CurrencyCode' => './az:OfferListing/az:Price/az:CurrencyCode/text()',
            'Availability' => './az:OfferListing/az:Availability/text()',
            'IsEligibleForSuperSaverShipping' => './az:OfferListing/az:IsEligibleForSuperSaverShipping/text()'
        );

        foreach ($map as $param_name => $xquery) {
            $query_result = $xpath->query($xquery, $dom);
            if ($query_result->length <= 0) {
                continue;
            }
            $text = $query_result->item(0);
            if (! $text instanceof DOMText) {
                continue;
            }
            $this->$param_name = (string) $text->data;
        }

        if (isset($this->IsEligibleForSuperSaverShipping)) {
            $this->IsEligibleForSuperSaverShipping = (bool) $this->IsEligibleForSuperSaverShipping;
        }
    }

    /**
     *
     * @return the $MerchantId
     */
    public function getMerchantId()
    {
        return $this->MerchantId;
    }

    /**
     *
     * @return the $MerchantName
     */
    public function getMerchantName()
    {
        return $this->MerchantName;
    }

    /**
     *
     * @return the $GlancePage
     */
    public function getGlancePage()
    {
        return $this->GlancePage;
    }

    /**
     *
     * @return the $Condition
     */
    public function getCondition()
    {
        return $this->Condition;
    }

    /**
     *
     * @return the $OfferListingId
     */
    public function getOfferListingId()
    {
        return $this->OfferListingId;
    }

    /**
     *
     * @return the $Price
     */
    public function getPrice()
    {
        return $this->Price;
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
     * @return the $Availability
     */
    public function getAvailability()
    {
        return $this->Availability;
    }

    /**
     *
     * @return the $IsEligibleForSuperSaverShipping
     */
    public function getIsEligibleForSuperSaverShipping()
    {
        return $this->IsEligibleForSuperSaverShipping;
    }
}
