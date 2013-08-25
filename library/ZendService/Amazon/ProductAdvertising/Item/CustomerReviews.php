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
use Zend\Uri\UriFactory;

/**
 * @category   Zend
 * @package    Zend_Service
 * @subpackage Amazon
 */
class CustomerReviews
{
    /**
     * @var Uri
     */
    public $IFrameURL;

    /**
     * @var boolean
     */
    public $HasReviews;

    /**
     * Assigns values to properties relevant to CustomerReviews
     *
     * @param DOMElement $dom
     */
    public function __construct(DOMElement $dom)
    {
        $xpath = new DOMXPath($dom->ownerDocument);
        $xpath->registerNamespace('az', 'http://webservices.amazon.com/AWSECommerceService/' . ProductAdvertising::getVersion());

        $this->IFrameURL = UriFactory::factory($xpath->query('./az:IFrameURL/text()', $dom)->item(0)->data);
        $this->HasReviews = (boolean) $xpath->query('./az:IFrameURL/text()', $dom)->item(0)->data;
    }
}
