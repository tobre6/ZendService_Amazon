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
class BrowseNode
{

    /**
     *
     * @var string
     */
    protected $BrowseNodeId;

    /**
     *
     * @var string
     */
    protected $Name;

    /**
     *
     * @var array
     */
    protected $Children;

    protected $IsCategoryRoot;

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

        $item = $xpath->query('./az:BrowseNodeId/text()', $dom);
        if ($item->length > 0) {
            $this->BrowseNodeId = (int) $item->item(0)->data;
        }

        $item = $xpath->query('./az:Name/text()', $dom);
        if ($item->length > 0) {
            $this->Name = $item->item(0)->data;
        }

        $item = $xpath->query('./az:IsCategoryRoot/text()', $dom);
        if ($item->length > 0) {
            $this->IsCategoryRoot = (boolean) $item->item(0)->data;
        }

        $items = $xpath->query('./az:Children/*', $dom);
        if ($items->length > 0) {
            foreach ($items as $item) {
                $this->Children = new self($item);
            }
        }
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
