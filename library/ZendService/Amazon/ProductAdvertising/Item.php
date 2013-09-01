<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Service
 */
namespace ZendService\Amazon\ProductAdvertising;

use DOMElement;
use DOMXPath;
use ZendService\Amazon\ProductAdvertising\Item;
use ZendService\Amazon\ProductAdvertising\Item\Attributes;
use ZendService\Amazon\ProductAdvertising\Item\OfferSummary;

/**
 *
 * @category Zend
 * @package Zend_Service
 * @subpackage Amazon
 */
class Item
{

    /**
     *
     * @var string
     */
    protected $ASIN;

    /**
     *
     * @var string
     */
    protected $DetailPageURL;

    /**
     *
     * @var
     *
     */
    protected $ItemLinks;

    /**
     *
     * @var int
     */
    protected $SalesRank;

    /**
     *
     * @var int
     */
    protected $TotalReviews;

    /**
     *
     * @var int
     */
    protected $AverageRating;

    /**
     *
     * @var string
     */
    protected $SmallImage;

    /**
     *
     * @var string
     */
    protected $MediumImage;

    /**
     *
     * @var string
     */
    protected $LargeImage;

    /**
     *
     * @var string
     */
    protected $Subjects;

    /**
     * @var OfferSummary
     */
    protected $OfferSummary;

    /**
     *
     * @var OfferSet
     */
    protected $Offers;

    /**
     *
     * @var array of ImageSet
     */
    protected $ImageSets = array();

    /**
     *
     * @var CustomerReview
     */
    protected $CustomerReviews;

    /**
     *
     * @var array Of SimilarProduct
     */
    protected $SimilarProducts = array();

    /**
     *
     * @var Attributes
     */
    protected $Attributes;

    /**
     *
     * @var array
     */
    protected $Tracks = array();

    protected $dom;

    /**
     * Parse the given <Item> element
     *
     * @param DOMElement $dom
     *            @group ZF-9547
     */
    public function __construct(DOMElement $dom)
    {
        $xpath = new DOMXPath($dom->ownerDocument);
        $xpath->registerNamespace('az',
            'http://webservices.amazon.com/AWSECommerceService/' . ProductAdvertising::getVersion());
        $this->ASIN = $xpath->query('./az:ASIN/text()', $dom)->item(0)->data;

        $result = $xpath->query('./az:DetailPageURL/text()', $dom);
        if ($result->length == 1) {
            $this->DetailPageURL = $result->item(0)->data;
        }

        $result = $xpath->query('./az:ItemLinks/az:*', $dom);
        if ($result->length >= 1) {
            foreach ($result as $r) {
                $this->ItemLinks[] = new Item\ItemLink($r);
            }
        }

        foreach (array(
            'SmallImage',
            'MediumImage',
            'LargeImage'
        ) as $im) {
            $result = $xpath->query("./az:$im", $dom);
            if ($result->length == 1) {
                $this->$im = new Item\Image\Image($result->item(0));
            }
        }

        $result = $xpath->query('./az:SalesRank/text()', $dom);
        if ($result->length == 1) {
            $this->SalesRank = (int) $result->item(0)->data;
        }

        $result = $xpath->query('./az:CustomerReviews', $dom);
        if ($result->length == 1) {
            $this->CustomerReviews = new Item\CustomerReviews($result->item(0));
        }

        $result = $xpath->query('./az:EditorialReviews/az:*', $dom);
        if ($result->length >= 1) {
            foreach ($result as $r) {
                $this->EditorialReviews[] = new Item\EditorialReview($r);
            }
        }

        $result = $xpath->query('./az:SimilarProducts/az:*', $dom);
        if ($result->length >= 1) {
            foreach ($result as $r) {
                $this->SimilarProducts[] = new Item\SimilarProduct($r);
            }
        }

        $result = $xpath->query('./az:ImageSets/az:*', $dom);
        if ($result->length >= 1) {
            foreach ($result as $r) {
                $this->ImageSets[] = new Item\Image\ImageSet($r);
            }
        }

        $result = $xpath->query('./az:Tracks/az:Disc', $dom);
        if ($result->length > 1) {
            foreach ($result as $disk) {
                foreach ($xpath->query('./*/text()', $disk) as $t) {
                    // TODO: For consistency in a bugfix all tracks are appended to one single array
                    // Erroreous line: $this->Tracks[$disk->getAttribute('number')] = (string) $t->data;
                    $this->Tracks[] = (string) $t->data;
                }
            }
        } elseif ($result->length == 1) {
            foreach ($xpath->query('./*/text()', $result->item(0)) as $t) {
                $this->Tracks[] = (string) $t->data;
            }
        }

        $result = $xpath->query('./az:OfferSummary', $dom);
        if ($result->length == 1) {
            $this->OfferSummary = new Item\OfferSummary($result->item(0));
        }

        $result = $xpath->query('./az:Offers', $dom);
        $resultSummary = $xpath->query('./az:OfferSummary', $dom);
        if ($result->length > 1 || $resultSummary->length == 1) {
            $this->Offers = new Item\OfferSet($dom);
        }

        $result = $xpath->query('./az:ItemAttributes', $dom);
        if ($result->length == 1) {
            $this->Attributes = new Item\Attributes($result->item(0));
        }

        $this->dom = $dom;
    }

    /**
     *
     * @return the $ASIN
     */
    public function getASIN()
    {
        return $this->ASIN;
    }

    /**
     *
     * @return the $DetailPageURL
     */
    public function getDetailPageURL()
    {
        return $this->DetailPageURL;
    }

    /**
     *
     * @return the $ItemLinks
     */
    public function getItemLinks()
    {
        return $this->ItemLinks;
    }

    /**
     *
     * @return the $SalesRank
     */
    public function getSalesRank()
    {
        return $this->SalesRank;
    }

    /**
     *
     * @return the $TotalReviews
     */
    public function getTotalReviews()
    {
        return $this->TotalReviews;
    }

    /**
     *
     * @return the $AverageRating
     */
    public function getAverageRating()
    {
        return $this->AverageRating;
    }

    /**
     *
     * @return the $SmallImage
     */
    public function getSmallImage()
    {
        return $this->SmallImage;
    }

    /**
     *
     * @return the $MediumImage
     */
    public function getMediumImage()
    {
        return $this->MediumImage;
    }

    /**
     *
     * @return the $LargeImage
     */
    public function getLargeImage()
    {
        return $this->LargeImage;
    }

    /**
     *
     * @return the $Subjects
     */
    public function getSubjects()
    {
        return $this->Subjects;
    }

    /**
     *
     * @return OfferSummary
     */
    public function getOfferSummary()
    {
        return $this->OfferSummary;
    }

    /**
     *
     * @return the $Offers
     */
    public function getOffers()
    {
        return $this->Offers;
    }

    /**
     *
     * @return the $ImageSets
     */
    public function getImageSets()
    {
        return $this->ImageSets;
    }

    /**
     *
     * @return the $CustomerReviews
     */
    public function getCustomerReviews()
    {
        return $this->CustomerReviews;
    }

    /**
     *
     * @return the $SimilarProducts
     */
    public function getSimilarProducts()
    {
        return $this->SimilarProducts;
    }

    /**
     *
     * @return Item\Attributes
     */
    public function getAttributes()
    {
        return $this->Attributes;
    }

    /**
     *
     * @return the $Tracks
     */
    public function getTracks()
    {
        return $this->Tracks;
    }

    /**
     * Returns the item's original XML
     *
     * @return string
     */
    public function asXml()
    {
        return $this->dom->ownerDocument->saveXML($this->dom);
    }
}
