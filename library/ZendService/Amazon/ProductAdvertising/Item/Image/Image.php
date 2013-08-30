<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Service
 */
namespace ZendService\Amazon\ProductAdvertising\Item\Image;

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
class Image
{

    /**
     * Image URL
     *
     * @var Uri\Uri
     */
    protected $Url;

    /**
     * Image height in pixels
     *
     * @var int
     */
    protected $Height;

    /**
     * Image width in pixels
     *
     * @var int
     */
    protected $Width;

    /**
     * Assigns values to properties relevant to Image
     *
     * @param DOMElement $dom
     */
    public function __construct(DOMElement $dom)
    {
        $xpath = new DOMXPath($dom->ownerDocument);
        $xpath->registerNamespace('az',
            'http://webservices.amazon.com/AWSECommerceService/' . ProductAdvertising::getVersion());

        $this->Url = Uri\UriFactory::factory($xpath->query('./az:URL/text()', $dom)->item(0)->data);
        $this->Height = (int) $xpath->query('./az:Height/text()', $dom)->item(0)->data;
        $this->Width = (int) $xpath->query('./az:Width/text()', $dom)->item(0)->data;
    }

    /**
     *
     * @return the $Url
     */
    public function getUrl()
    {
        return $this->Url;
    }

    /**
     *
     * @return the $Height
     */
    public function getHeight()
    {
        return $this->Height;
    }

    /**
     *
     * @return the $Width
     */
    public function getWidth()
    {
        return $this->Width;
    }
}
