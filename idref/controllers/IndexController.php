<?php
class Idref_IndexController extends Omeka_Controller_Action
{
    const IDREF_SUGGEST_URL = 'http://www.idref.fr/Sru/Solr?wt=json&version=2.2&start=0&rows=50&indent=on&fl=affcourt_z';
    
	 
	
	
    public function auteurProxyAction()
    {
		
		$config = array(
		'adapter' => 'Zend_Http_Client_Adapter_Proxy',
		'proxy_host' => IDREF_PROXY_HOST,
		'proxy_port' => IDREF_PROXY_PORT
		);
        $client = new Zend_Http_Client();
        $client->setUri(self::IDREF_SUGGEST_URL);
		$client->setConfig($config);
		
 		$request = $this->getRequest()->getParam('term');
		$request = strtolower($request);
		$request = regAccents($request);
		$trimmed = trim($request); 
		$trimmed_array = explode(" ",$trimmed);	
		$request = implode(" AND ", $trimmed_array);
		$request ="(" . $request . "*)";		
		$request = "all:".$request."  AND recordtype_z:a";
       	$client->setParameterGet('q',$request);
        $json = json_decode($client->request()->getBody(),true);
		$subjects=array();
		foreach($json[response][docs] as $d)
		{
		array_push($subjects, $d[affcourt_z]);
		}
		
        $this->_helper->json($subjects);
    }

	public function sujetProxyAction()
    {

		$config = array(
		'adapter' => 'Zend_Http_Client_Adapter_Proxy',
		'proxy_host' => IDREF_PROXY_HOST,
		'proxy_port' => IDREF_PROXY_PORT

		);
        $client = new Zend_Http_Client();
        $client->setUri(self::IDREF_SUGGEST_URL);
		$client->setConfig($config);
 		$request = $this->getRequest()->getParam('term');
		$request = strtolower($request);
		$request = regAccents($request);
		$trimmed = trim($request); 
		$trimmed_array = explode(" ",$trimmed);	
		$request = implode(" AND ", $trimmed_array);
		$request ="(" . $request . ")";		
		$request = "all:".$request;
       	$client->setParameterGet('q',$request);
        $json = json_decode($client->request()->getBody(),true);
		$subjects=array();
		foreach($json[response][docs] as $d)
		{
		array_push($subjects, $d[affcourt_z]);
		}
		
        $this->_helper->json($subjects);
    }

}
