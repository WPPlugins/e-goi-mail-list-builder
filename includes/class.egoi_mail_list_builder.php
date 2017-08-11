<?php/** * Egoi Mail List Builder main class**/class EgoiMailListBuilder {	private $egoiMailListBuilderData = array(		'api_key' => '',		'client_id' => ''	);		private $egoiMailListBuilderError = array(		"exists" => false,		"error" => "",		"description" => "",	);	private $egoiMailListBuilderComments = array(		"subscribe_enable" => true,		//"hide_subscribe" => false,		"subscribe_text" => "Subscribe!",		"subscribe_list" => ""	);	private $egoiMailListBuilderOptIn = array(		'double_opt_in' => 0	);	private $egoiMailListBuilderErrorCodes = array();	private $egoiMailListBuilderWidgetText = array();	function __construct($api_key = '', $logout = false) {		require(EGOI_MAIL_LIST_BUILDER_DIR.'includes/error_codes.php');		$this->egoiMailListBuilderErrorCodes = $errorCodes;		require(EGOI_MAIL_LIST_BUILDER_DIR.'includes/widget_text.php');		$this->egoiMailListBuilderWidgetText = $widgetText;		if(!empty($api_key)) {			$params = array(				'apikey' => $api_key, 				'plugin_key' => EGOI_MAIL_LIST_BUILDER_PLUGIN_KEY);			$result = $this->connectXmlrpc('getClientData',$params);			if(is_string($result)){				if(array_key_exists((string)$result,$this->egoiMailListBuilderErrorCodes)) {						$this->egoiMailListBuilderError['exists'] = true;						$this->egoiMailListBuilderError['error'] = (string)$result;						$this->egoiMailListBuilderError['description'] = $this->egoiMailListBuilderErrorCodes[(string)$result];				}			}			else {				$this->egoiMailListBuilderData['api_key'] = $api_key;				$this->egoiMailListBuilderData['client_id'] = $result['CLIENTE_ID'];			}		}		else {			if(!$logout){				$params = array(					'apikey' => $this->egoiMailListBuilderData['api_key'], 					'plugin_key' => EGOI_MAIL_LIST_BUILDER_PLUGIN_KEY);				$result = $this->connectXmlrpc('getClientData',$params);				if(is_string($result)){					if(array_key_exists((string)$result,$this->egoiMailListBuilderErrorCodes)) {							$this->egoiMailListBuilderError['exists'] = true;							$this->egoiMailListBuilderError['error'] = (string)$result;							$this->egoiMailListBuilderError['description'] = $this->egoiMailListBuilderErrorCodes[(string)$result];					}				}				else {					$this->egoiMailListBuilderData['client_id'] = $result['CLIENTE_ID'];				}			}		}	}		private function connectXmlrpc($callType, $params) {		$client = new Zend_XmlRpc_Client(EGOI_MAIL_LIST_BUILDER_XMLRPC_URL);		$client->getHttpClient()->setConfig(array('timeout'=>60));		$result = $client->call($callType, array($params));		return $result;	}		public function __get($egoiMailListBuilderName) {        if (array_key_exists($egoiMailListBuilderName, $this->egoiMailListBuilderData)) {            return $this->egoiMailListBuilderData[$egoiMailListBuilderName];        }        else if (array_key_exists($egoiMailListBuilderName, $this->egoiMailListBuilderError)) {            return $this->egoiMailListBuilderError[$egoiMailListBuilderName];        }        else if (array_key_exists($egoiMailListBuilderName, $this->egoiMailListBuilderWidgetText)) {            return $this->egoiMailListBuilderWidgetText[$egoiMailListBuilderName];        }        else if (array_key_exists($egoiMailListBuilderName, $this->egoiMailListBuilderComments)) {            return $this->egoiMailListBuilderComments[$egoiMailListBuilderName];        }		else if (array_key_exists($egoiMailListBuilderName, $this->egoiMailListBuilderOptIn)) {			return $this->egoiMailListBuilderOptIn[$egoiMailListBuilderName];		}		else {			return null;		}	}		public function __set($egoiMailListBuilderName, $egoiMailListBuilderValue) {		if (array_key_exists($egoiMailListBuilderName, $this->egoiMailListBuilderData)) {        	$this->egoiMailListBuilderData[$egoiMailListBuilderName] = $egoiMailListBuilderValue;    	}    	else if (array_key_exists($egoiMailListBuilderName, $this->egoiMailListBuilderError)) {            $this->egoiMailListBuilderError[$egoiMailListBuilderName] = $egoiMailListBuilderValue;        }        else if (array_key_exists($egoiMailListBuilderName, $this->egoiMailListBuilderWidgetText)) {            $this->egoiMailListBuilderWidgetText[$egoiMailListBuilderName] = $egoiMailListBuilderValue;        }        else if (array_key_exists($egoiMailListBuilderName, $this->egoiMailListBuilderComments)) {            $this->egoiMailListBuilderComments[$egoiMailListBuilderName] = $egoiMailListBuilderValue;        }		else if (array_key_exists($egoiMailListBuilderName, $this->egoiMailListBuilderOptIn)) {			$this->egoiMailListBuilderOptIn[$egoiMailListBuilderName] = $egoiMailListBuilderValue;		}		else {			return null;		}    }		public function isAuthed() {		if(			!empty($this->egoiMailListBuilderData['client_id']) && 			isset($this->egoiMailListBuilderData['client_id']) && 			!empty($this->egoiMailListBuilderData['api_key']))		{			return true;		}		else		{			return false;		}	}		public function getClient() {		$params = array(			'plugin_key' => EGOI_MAIL_LIST_BUILDER_PLUGIN_KEY,			'apikey' => $this->egoiMailListBuilderData['api_key']		);		$result = $this->connectXmlrpc('getClientData',$params);		if(is_string($result)){			if(array_key_exists((string)$result,$this->egoiMailListBuilderErrorCodes)) {					$this->egoiMailListBuilderError['exists'] = true;					$this->egoiMailListBuilderError['error'] = (string)$result;					$this->egoiMailListBuilderError['description'] = $this->egoiMailListBuilderErrorCodes[(string)$result];			}		}		else {			return $result;		}	}		public function getLists() {		$params = array(			'plugin_key' => EGOI_MAIL_LIST_BUILDER_PLUGIN_KEY,			'apikey' => $this->egoiMailListBuilderData['api_key']		);		$result = $this->connectXmlrpc('getLists',$params);		if(is_string($result)){			if(array_key_exists((string)$result,$this->egoiMailListBuilderErrorCodes)) {				$this->egoiMailListBuilderError['exists'] = true;				$this->egoiMailListBuilderError['error'] = (string)$result;				$this->egoiMailListBuilderError['description'] = $this->egoiMailListBuilderErrorCodes[(string)$result];			}		}		else if(is_array($result)){			if(isset($result["ERROR"])){				if(array_key_exists((string)$result["ERROR"],$this->egoiMailListBuilderErrorCodes)) {					$this->egoiMailListBuilderError['exists'] = true;					$this->egoiMailListBuilderError['error'] = (string)$result["ERROR"];					$this->egoiMailListBuilderError['description'] = $this->egoiMailListBuilderErrorCodes[(string)$result["ERROR"]];				}			}			else {				return $result;			}		}	}		public function createList($nome = '', $idioma_lista = '') {		$params = array(			'plugin_key' => EGOI_MAIL_LIST_BUILDER_PLUGIN_KEY,			'apikey' => $this->egoiMailListBuilderData['api_key'],			'nome' => $nome,			'idioma_lista' => $idioma_lista,			'canal_email' => '1',			'canal_sms' => '0',			'canal_fax' => '0',			'canal_voz' => '0',			'canal_mms' => '0'		);		$result = $this->connectXmlrpc('createList',$params);		if(is_string($result)){			if(array_key_exists((string)$result,$this->egoiMailListBuilderErrorCodes)) {					$this->egoiMailListBuilderError['exists'] = true;					$this->egoiMailListBuilderError['error'] = (string)$result;					$this->egoiMailListBuilderError['description'] = $this->egoiMailListBuilderErrorCodes[(string)$result];			}		}		if(is_array($result)){			if(isset($result["ERROR"])){				if(array_key_exists((string)$result["ERROR"],$this->egoiMailListBuilderErrorCodes)) {						$this->egoiMailListBuilderError['exists'] = true;						$this->egoiMailListBuilderError['error'] = (string)$result["ERROR"];						$this->egoiMailListBuilderError['description'] = $this->egoiMailListBuilderErrorCodes[(string)$result["ERROR"]];				}			}		}	}		public function updateList($listID = '',$name = '', $idioma_lista = '') {		$params = array(			'plugin_key' => EGOI_MAIL_LIST_BUILDER_PLUGIN_KEY,			'apikey' => $this->egoiMailListBuilderData['api_key'],			'listID' => $listID,			'name' => $name,			'language' => $idioma_lista		);		$params = php_xmlrpc_encode($params);		$result = $this->connectXmlrpc('updateList',$params);		if(is_string($result)){			if(isset($result["ERROR"])){				if(array_key_exists((string)$result,$this->egoiMailListBuilderErrorCodes)) {						$this->egoiMailListBuilderError['exists'] = true;						$this->egoiMailListBuilderError['error'] = (string)$result;						$this->egoiMailListBuilderError['description'] = $this->egoiMailListBuilderErrorCodes[(string)$result];				}			}		}	}		public function addSubscriber($listID = '', $first_name = '', $last_name = '', $email = '', $cellphone = '', $lang = '', $birth_date = '') {		$params = array(			'plugin_key' => EGOI_MAIL_LIST_BUILDER_PLUGIN_KEY,			'apikey' => $this->egoiMailListBuilderData['api_key'],			'status' => $this->egoiMailListBuilderContactForm7OptIn['double_opt_in'],			'listID' => $listID,			'first_name' => $first_name,			'last_name' => $last_name,			'email' => $email,			'cellphone' => $cellphone,			'lang' => $lang,			'birth_date' => $birth_date		);		$result = $this->connectXmlrpc('addSubscriber',$params);				if(is_string($result)){			if(array_key_exists((string)$result,$this->egoiMailListBuilderErrorCodes)) {				/*$this->egoiMailListBuilderError['exists'] = true;				$this->egoiMailListBuilderError['error'] = (string)$result;				$this->egoiMailListBuilderError['description'] = $this->egoiMailListBuilderErrorCodes[(string)$result];*/				return $this->egoiMailListBuilderErrorCodes[(string)$result];			}		}		if(is_array($result)){			if(isset($result["ERROR"])){				if(array_key_exists((string)$result["ERROR"],$this->egoiMailListBuilderErrorCodes)) {					/*$this->egoiMailListBuilderError['exists'] = true;					$this->egoiMailListBuilderError['error'] = (string)$result["ERROR"];					$this->egoiMailListBuilderError['description'] = $this->egoiMailListBuilderErrorCodes[(string)$result["ERROR"]];*/					return $this->egoiMailListBuilderErrorCodes[(string)$result["ERROR"]];				}			}		}			}	public function checkSubscriber($listID, $subscriber){		$params = array(			'plugin_key' => EGOI_MAIL_LIST_BUILDER_PLUGIN_KEY,			'apikey' => $this->egoiMailListBuilderData['api_key'],			'listID' => $listID,			'subscriber' => $subscriber		);		$result = $this->connectXmlrpc('subscriberData',$params);		if(is_array($result)){			if(isset($result['ERROR'])){				if(array_key_exists($result['ERROR'],$this->egoiMailListBuilderErrorCodes)) {					return -1;				}			}			else{				return $result['subscriber']['STATUS'];			}		}	}}?>