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
use ZendService\Amazon\ProductAdvertising\Item\Attributes\ListPrice;

/**
 *
 * @category Zend
 * @package Zend_Service
 * @subpackage Amazon
 */
class Attributes
{

    /**
     *
     * @var string
     */
    protected $Binding;

    /**
     *
     * @var string
     */
    protected $Brand;

    /**
     *
     * @var string
     */
    protected $EAN;

    /**
     *
     * @var array
     */
    protected $Features = array();

    /**
     *
     * @var string
     */
    protected $Label;

    /**
     *
     * @var string
     */
    protected $LegalDisclaimer;

    /**
     *
     * @var Attributes\ListPrice
     */
    protected $ListPrice;

    /**
     *
     * @var string
     */
    protected $Manufacturer;

    /**
     *
     * @var string
     */
    protected $Model;

    /**
     *
     * @var string
     */
    protected $MPN;

    /**
     *
     * @var string
     */
    protected $PackageQuantity;

    /**
     *
     * @var string
     */
    protected $PartNumber;

    /**
     *
     * @var string
     */
    protected $ProductGroup;

    /**
     *
     * @var string
     */
    protected $ProductTypeName;

    /**
     *
     * @var string
     */
    protected $Publisher;

    /**
     *
     * @var string
     */
    protected $SKU;

    /**
     *
     * @var string
     */
    protected $Studio;

    /**
     *
     * @var string
     */
    protected $Title;

    /**
     *
     * @var string
     */
    protected $UPC;

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
        $map = array(
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
            'UPC'
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

    /**
     *
     * @return the $Binding
     */
    public function getBinding()
    {
        return $this->Binding;
    }

    /**
     *
     * @return the $Brand
     */
    public function getBrand()
    {
        return $this->Brand;
    }

    /**
     *
     * @return the $EAN
     */
    public function getEAN()
    {
        return $this->EAN;
    }

    /**
     *
     * @return the $Features
     */
    public function getFeatures()
    {
        return $this->Features;
    }

    /**
     *
     * @return the $Label
     */
    public function getLabel()
    {
        return $this->Label;
    }

    /**
     *
     * @return the $LegalDisclaimer
     */
    public function getLegalDisclaimer()
    {
        return $this->LegalDisclaimer;
    }

    /**
     *
     * @return ListPrice
     */
    public function getListPrice()
    {
        return $this->ListPrice;
    }

    /**
     *
     * @return the $Manufacturer
     */
    public function getManufacturer()
    {
        return $this->Manufacturer;
    }

    /**
     *
     * @return the $Model
     */
    public function getModel()
    {
        return $this->Model;
    }

    /**
     *
     * @return the $MPN
     */
    public function getMPN()
    {
        return $this->MPN;
    }

    /**
     *
     * @return the $PackageQuantity
     */
    public function getPackageQuantity()
    {
        return $this->PackageQuantity;
    }

    /**
     *
     * @return the $PartNumber
     */
    public function getPartNumber()
    {
        return $this->PartNumber;
    }

    /**
     *
     * @return the $ProductGroup
     */
    public function getProductGroup()
    {
        return $this->ProductGroup;
    }

    /**
     *
     * @return the $ProductTypeName
     */
    public function getProductTypeName()
    {
        return $this->ProductTypeName;
    }

    /**
     *
     * @return the $Publisher
     */
    public function getPublisher()
    {
        return $this->Publisher;
    }

    /**
     *
     * @return the $SKU
     */
    public function getSKU()
    {
        return $this->SKU;
    }

    /**
     *
     * @return the $Studio
     */
    public function getStudio()
    {
        return $this->Studio;
    }

    /**
     *
     * @return the $Title
     */
    public function getTitle()
    {
        return $this->Title;
    }

    /**
     *
     * @return the $UPC
     */
    public function getUPC()
    {
        return $this->UPC;
    }
}
