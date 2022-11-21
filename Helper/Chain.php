<?php
/**
 * Vbhex Software.
 *
 * @category  Vbhex
 * @package   Vbhex_Checkout
 * @author    Vbhex
 * @copyright Copyright (c) Vbhex Software Private Limited (https://vbhex.com)
 * @license   https://store.vbhex.com/license.html
 */

namespace Vbhex\Checkout\Helper;

use Magento\Framework\App\Http\Context as HttpContext;

/**
 * Vbhex Checkout Helper Chain.
 */
class Chain extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var HttpContext
     */
    private $httpContext;

    protected $_scopeConfig;

    /**
     * @var \Magento\Framework\App\Cache\ManagerFactory
     */
    protected $cacheManager;
    /**
     * @var \Vbhex\Checkout\Logger\Logger
     */
    protected $logger;
    /**
     * @param HttpContext $httpContext
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        HttpContext $httpContext,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->httpContext = $httpContext;
        $this->_scopeConfig = $scopeConfig;
    }

    public function getMaxModId(){
        $modContract = $this->getStoreConfig('payment/vbhex_checkout/mod_contract');
        $funcSelector = "0x22ae6f1b";
            $data = $funcSelector;
            $method  = "eth_call";
            $param1 = [
                "data"  => $data,
                "to"    =>  $modContract
            ];
            $params  = [$param1,"latest"];
            $result = $this->call($method,$params);
            if(is_array($result)){
                return json_encode($result,true);
            }
            return hexdec($result);
    }

    function commit_curl($url,$get=true,$header=0,$odata=null,$user=null,$pass=null,$apikey=null) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if(!$get){
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        if ($header == 3) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        if($user!=null && $pass!=null) {
            curl_setopt($ch, CURLOPT_USERPWD, "$user:$pass");
        }
        if($header==0) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        } else if($header==1 || $header==3 || $header==4) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Accept: application/json; charset=utf-8'));
        } else if ($header==2) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json; charset=utf-8','Content-Type: application/x-www-form-urlencoded; charset=utf-8'));
        } else if($header==5) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain;','Accept: text/plain;'));
        } else if($header==6) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Accept: application/json; charset=utf-8',$apikey));
        }
        if($odata != null && $header==1 && !$get) {
            curl_setopt($ch, CURLOPT_POSTFIELDS , json_encode($odata));
        } else if($odata != null && $header==2 && !$get) {
            curl_setopt($ch, CURLOPT_POSTFIELDS , http_build_query($odata));
        } else if($odata != null && $header==4 && !$get) {
            curl_setopt($ch, CURLOPT_POSTFIELDS , $odata);
        } else if($odata != null && $header==5 && !$get) {
            curl_setopt($ch, CURLOPT_POSTFIELDS , json_encode($odata));
        } else if($odata != null && $header==6 && !$get) {
            curl_setopt($ch, CURLOPT_POSTFIELDS , json_encode($odata));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    protected function call($method, $params=NULL) {
        if(empty($params)){
            $odata = [
            "jsonrpc"=> "2.0",
            "method"=> $method,
            'id' =>time()
            ];
        } else {
            $odata = [
            "jsonrpc"=> "2.0",
            "method"=> $method,
            "params"=> $params,
            'id' =>time()
            ];
        }
        $out = $this->commit_curl($this->getStoreConfig('payment/vbhex_checkout/rpc'),false,1,$odata);
        if(isset(json_decode($out,true)['result'])){
            return json_decode($out,true)['result'];
        } else {
            if(isset(json_decode($out,true)['error'])){
                $error = json_decode($out,true)['error'];
                $error['method'] = $method;
                $error['when'] = date('Y-m-d H:i:s');
                var_dump($error);
                return $error;
            }

        }
    }

    public function getStoreConfig($_env){

        $_val = $this->_scopeConfig->getValue($_env, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $_val;
     }

}
