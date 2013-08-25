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
use ZendService\Amazon\ProductAdvertising\ProductAdvertising;

/**
 * @category   Zend
 * @package    Zend_Service
 * @subpackage Amazon
 */
class ImageSet
{
    /**
     * @var Image
     */
    public $SwatchImage;

    /**
     * @var Image
     */
    public $SmallImage;

    /**
     * @var Image
     */
    public $ThumbnailImage;

    /**
     * @var Image
     */
    public $TinyImage;

    /**
     * @var Image
     */
    public $MediumImage;

    /**
     * @var Image
     */
    public $LargeImage;

    /**
     * Parse the given Offer Set Element
     *
     * @param DOMElement $dom
     */
    public function __construct(DOMElement $dom)
    {
        $xpath = new DOMXPath($dom->ownerDocument);
        $xpath->registerNamespace('az', 'http://webservices.amazon.com/AWSECommerceService/' . ProductAdvertising::getVersion());

        $map = array(
            'SwatchImage'       => './az:SwatchImage',
            'SmallImage'        => './az:SmallImage',
            'ThumbnailImage'    => './az:ThumbnailImage',
            'TinyImage'         => './az:TinyImage',
            'MediumImage'       => './az:MediumImage',
            'LargeImage'        => './az:LargeImage',
        );

        $a = $dom->ownerDocument->saveXml($dom);

        foreach ($map as $paramName => $xquery) {
            $queryResult = $xpath->query($xquery, $dom);
            if ($queryResult->length <= 0) {
                continue;
            }
            $item = $queryResult->item(0);

            $this->$paramName = new Image($item);
        }
    }
}
