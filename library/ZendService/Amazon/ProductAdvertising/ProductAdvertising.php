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

use DOMDocument;
use DOMXPath;
use Zend\Crypt\Hmac;
use ZendRest\Client\RestClient;
use ZendService\Amazon\Exception;

/**
 *
 * @category Zend
 * @package Zend_Service
 * @subpackage ProductAdvertising
 */
class ProductAdvertising
{

    /**
     * ProductAdvertising Web Services Access Key ID
     *
     * @var string
     */
    public $accessKeyId;

    /**
     *
     * @var string
     */
    protected $secretKey;

    /**
     *
     * @var string
     */
    protected $associateTag;

    /**
     * API Version
     *
     * @var string
     */
    protected static $version = '2011-08-01';

    /**
     *
     * @var string
     */
    protected $baseUri;

    /**
     * List of ProductAdvertising Web Service base URLs, indexed by country code
     *
     * @var array
     */
    protected $baseUriList = array(
        'US' => 'http://webservices.amazon.com',
        'UK' => 'http://webservices.amazon.co.uk',
        'DE' => 'http://webservices.amazon.de',
        'JP' => 'http://webservices.amazon.co.jp',
        'FR' => 'http://webservices.amazon.fr',
        'CA' => 'http://webservices.amazon.ca'
    );

    /**
     * Reference to REST client object
     *
     * @var RestClient
     */
    protected $rest;

    /**
     * Constructs a new ProductAdvertising Web Services Client
     *
     * @param string $accessKeyId
     *            Developer's ProductAdvertising accessKeyId
     * @param string $countryCode
     *            Country code for ProductAdvertising service; may be US, UK, DE, JP, FR, CA
     * @param string $secretKey
     *            API Secret Key
     * @param string $version
     *            API Version to use
     * @throws Exception\InvalidArgumentException
     * @return ProductAdvertising
     */
    public function __construct($accessKeyId, $secretKey = null, $associateTag = null, $countryCode = 'US', $version = null)
    {
        $this->accessKeyId = (string) $accessKeyId;
        $this->secretKey = $secretKey;
        $this->associateTag = $associateTag;
        
        if (! is_null($version))
            self::setVersion($version);
        
        $countryCode = (string) $countryCode;
        if (! isset($this->baseUriList[$countryCode])) {
            throw new Exception\InvalidArgumentException("Unknown country code: $countryCode");
        }
        
        $this->baseUri = $this->baseUriList[$countryCode];
    }

    /**
     * Search for Items
     *
     * @param array $options
     *            Options to use for the Search Query
     * @throws Exception\RuntimeException
     * @return ResultSet
     * @see http://www.amazon.com/gp/aws/sdk/main.html/102-9041115-9057709?s=AWSEcommerceService&v=2011-08-01&p=ApiReference/ItemSearchOperation
     */
    public function itemSearch(array $options)
    {
        $client = $this->getRestClient();
        $client->setUri($this->baseUri);
        
        $defaultOptions = array(
            'ResponseGroup' => 'Small'
        );
        $options = $this->prepareOptions('ItemSearch', $options, $defaultOptions);
        $client->getHttpClient()->resetParameters();
        $response = $client->restGet('/onca/xml', $options);
        
        if ($response->isClientError()) {
            throw new Exception\RuntimeException(
                'An error occurred sending request. Status code: ' . $response->getStatusCode());
        }
        
        $dom = new DOMDocument();
        $dom->loadXML($response->getBody());
        self::checkErrors($dom);
        
        return new ResultSet($dom);
    }

    /**
     * Look up item(s) by ASIN
     *
     * @param string $asin
     *            ProductAdvertising ASIN ID
     * @param array $options
     *            Query Options
     * @see http://www.amazon.com/gp/aws/sdk/main.html/102-9041115-9057709?s=AWSEcommerceService&v=2011-08-01&p=ApiReference/ItemLookupOperation
     * @throws Exception\RuntimeException
     * @return Item ResultSet
     */
    public function itemLookup($asin, array $options = array())
    {
        $client = $this->getRestClient();
        $client->setUri($this->baseUri);
        $client->getHttpClient()->resetParameters();
        
        $defaultOptions = array(
            'ResponseGroup' => 'Small'
        );
        $options['ItemId'] = (string) $asin;
        $options = $this->prepareOptions('ItemLookup', $options, $defaultOptions);
        $response = $client->restGet('/onca/xml', $options);
        
        if ($response->isClientError()) {
            throw new Exception\RuntimeException(
                'An error occurred sending request. Status code: ' . $response->getStatusCode());
        }
        
        $dom = new DOMDocument();
        $dom->loadXML($response->getBody());
        self::checkErrors($dom);
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('az', 'http://webservices.amazon.com/AWSECommerceService/' . self::getVersion());
        $items = $xpath->query('//az:Items/az:Item');
        
        if ($items->length == 1) {
            return new Item($items->item(0));
        }
        
        return new ResultSet($dom);
    }

    /**
     * Returns a reference to the REST client
     *
     * @return RestClient
     */
    public function getRestClient()
    {
        if ($this->rest === null) {
            $this->rest = new RestClient();
        }
        return $this->rest;
    }

    /**
     * Set REST client
     *
     * @param RestClient $client            
     * @return ProductAdvertising
     */
    public function setRestClient(RestClient $client)
    {
        $this->rest = $client;
        return $this;
    }

    /**
     * Prepare options for request
     *
     * @param string $query
     *            Action to perform
     * @param array $options
     *            User supplied options
     * @param array $defaultOptions
     *            Default options
     * @return array
     */
    protected function prepareOptions($query, array $options, array $defaultOptions)
    {
        $options['AWSAccessKeyId'] = $this->accessKeyId;
        $options['Service'] = 'AWSECommerceService';
        $options['Operation'] = (string) $query;
        $options['Version'] = self::getVersion();
        $options['AssociateTag'] = $this->associateTag;
        
        // de-canonicalize out sort key
        if (isset($options['ResponseGroup'])) {
            $responseGroup = explode(',', $options['ResponseGroup']);
            
            if (! in_array('Request', $responseGroup)) {
                $responseGroup[] = 'Request';
                $options['ResponseGroup'] = implode(',', $responseGroup);
            }
        }
        
        $options = array_merge($defaultOptions, $options);
        
        if ($this->secretKey !== null) {
            $options['Timestamp'] = gmdate("Y-m-d\TH:i:s\Z");
            ksort($options);
            $options['Signature'] = self::computeSignature($this->baseUri, $this->secretKey, $options);
        }
        
        return $options;
    }

    /**
     * Compute Signature for Authentication with ProductAdvertising Product Advertising Webservices
     *
     * @param string $baseUri            
     * @param string $secretKey            
     * @param array $options            
     * @return string
     */
    public static function computeSignature($baseUri, $secretKey, array $options)
    {
        $signature = self::buildRawSignature($baseUri, $options);
        return base64_encode(Hmac::compute($secretKey, 'sha256', $signature, Hmac::OUTPUT_BINARY));
    }

    /**
     * Build the Raw Signature Text
     *
     * @param string $baseUri            
     * @param array $options            
     * @return string
     */
    public static function buildRawSignature($baseUri, $options)
    {
        ksort($options);
        
        return sprintf("GET" . PHP_EOL . "%s" . PHP_EOL . "/onca/xml" . PHP_EOL . "%s", 
            str_replace('http://', '', $baseUri), http_build_query($options, '', '&', \PHP_QUERY_RFC3986));
    }

    /**
     * Check result for errors
     *
     * @param DOMDocument $dom            
     * @throws Exception\RuntimeException
     * @return void
     */
    protected static function checkErrors(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('az', 'http://webservices.amazon.com/AWSECommerceService/' . self::getVersion());
        
        if ($xpath->query('//az:Error')->length >= 1) {
            $code = $xpath->query('//az:Error/az:Code/text()')->item(0)->data;
            $message = $xpath->query('//az:Error/az:Message/text()')->item(0)->data;
            
            switch ($code) {
                case 'AWS.ECommerceService.NoExactMatches':
                    break;
                default:
                    throw new Exception\RuntimeException("$message ($code)");
            }
        }
    }

    /**
     * Set the ProductAdvertising API version
     *
     * @static
     *
     * @param string $version
     *            API Version
     */
    public static function setVersion($version)
    {
        if (! preg_match('/\d{4}-\d{2}-\d{2}/', $version)) {
            throw new Exception\InvalidArgumentException("$version is an invalid API Version");
        }
        self::$version = $version;
    }

    /**
     * Return the ProductAdvertising API version
     *
     * @static
     *
     * @return string
     */
    public static function getVersion()
    {
        return self::$version;
    }
}
