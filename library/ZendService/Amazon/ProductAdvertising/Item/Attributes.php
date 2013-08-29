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
use ZendService\Amazon\ProductAdvertising\Item\Image\ListPrice;

/**
 * @category   Zend
 * @package    Zend_Service
 * @subpackage Amazon
 */
class Attributes
{
    /**
     * @var string
     */
    public $Binding;

    /**
     * @var string
     */
    public $Brand;

    /**
     * @var string
     */
    public $EAN;

    /**
     * @var array
     */
    public $Features = array ();

    /**
     * @var string
     */
    public $Label;

    /**
     * @var string
     */
    public $LegalDisclaimer;

    /**
     * @var Attributes\ListPrice
     */
    public $ListPrice;

    /**
     * @var string
     */
    public $Manufacturer;

    /**
     * @var string
     */
    public $Model;

    /**
     * @var string
     */
    public $MPN;

    /**
     * @var string
     */
    public $PackageQuantity;

    /**
     * @var string
     */
    public $PartNumber;

    /**
     * @var string
     */
    public $ProductGroup;

    /**
     * @var string
     */
    public $ProductTypeName;

    /**
     * @var string
     */
    public $Publisher;

    /**
     * @var string
     */
    public $SKU;

    /**
     * @var string
     */
    public $Studio;

    /**
     * @var string
     */
    public $Title;

    /**
     * @var string
     */
    public $UPC;

    /**
     * Assigns values to properties relevant to Attributes
     *
     * @param DOMElement $dom
     */
    public function __construct(DOMElement $dom)
    {
        $xpath = new DOMXPath($dom->ownerDocument);
        $xpath->registerNamespace('az', 'http://webservices.amazon.com/AWSECommerceService/' . ProductAdvertising::getVersion());
        $map = array (
            'Binding',
            'Brand',
            'EAN',
            'Label',
            'LegalDisclaimer',
            'Manufacturer',
            'Model',
            'MPN',
            'PackageQuantity',
            'PartNumber',
            'ProductGroup',
            'ProductTypeName',
            'Publisher',
            'SKU',
            'Studio',
            'Title',
            'UPC',
        );
        foreach ($map as $el) {
            $element = $xpath->query("./az:$el/text()", $dom);
            if ($element->length >= 1) {
                $this->$el = (string) $element->item(0)->data;
            }
        }

        $features = $xpath->query('./az:Feature', $dom);
        if ($features->length >= 1) {
            foreach ($features as $feature) {
                $this->Features[] = $feature->textContent;
            }
        }

        $listPrice = $xpath->query('./az:ListPrice', $dom);
        if ($listPrice->length >= 1) {
            $this->ListPrice = new Attributes\ListPrice($listPrice->item(0));
        }
    }
}
