<?php

class Spore_Middleware_Weborama_Authentication
{
	protected $_applicationKey;
	protected $_privateKey;
	protected $_signatureString;
	protected $_signatureSha1;
	protected $_userEmail;

	/**
	 * Construct the authentication object
	 *
	 * @param array $args
	 */
	public function __construct($args)
	{
		if (isset($args['application_key']))
			$this->setApplicationKey($args['application_key']);
		else
			$this->setApplicationKey('');

		if (isset($args['private_key']))
			$this->setPrivateKey($args['private_key']);
		else
			$this->setPrivateKey('');

		if (isset($args['user_email']))
			$this->setUserEmail($args['user_email']);
		else
			$this->setUserEmail('');
	}

	/**
	 * Set application key
	 *
	 * @param string $applicationKey
	 */
	public function setApplicationKey($applicationKey)
	{
		$this->_applicationKey = $applicationKey;
	}

	/**
	 * Set private key
	 *
	 * @param string $privateKey
	 */
	public function setPrivateKey($privateKey)
	{
		$this->_privateKey = $privateKey;
	}

	/**
	 * @param string $privateKey
	 */
	public function setUserEmail($userEmail)
	{
		$this->_userEmail = $userEmail;
	}


	/**
	 * Add the application_key and private_key into the client's headers
	 *
	 * @param Spore $spore (reference)
	 */
	public function execute(&$spore)
	{
		// set signature string
		$this->setSignatureString($spore);
		$this->_signatureSha1 = sha1($this->_signatureString);

		// modify the request headers
		$client = RESTHttpClient:: getHttpClient();
		$client->createOrUpdateHeader('X-Weborama-AppKey', $this->_applicationKey);
		$client->createOrUpdateHeader('X-Weborama-Signature', $this->_signatureSha1);
		$client->createOrUpdateHeader('X-Weborama-User-Email', $this->_userEmail);
	}

	/**
	 * Generate signature string
	 *
	 * @param Spore $spore
	 */
	public function setSignatureString($spore)
	{
		// add request method and path
		$this->_signatureString = strtolower($spore->getRequestMethod()) . $spore->getRequestUrlPath();

		// add request params
		$string_params = '';
		$params = $spore->getRequestParams();
		ksort($params);
		foreach ($params as $key => $val) {
			$string_params .= "$key=$val";
		}
		$this->_signatureString .= $string_params;

		// add private key
		$this->_signatureString .= $this->_privateKey;
	}


	/**
	 * @return string
	 */
	public function getSignatureString()
	{
		return $this->_signatureString;
	}

	/**
	 * Get the _Http_Client object used for communication
	 *
	 * @return RESTHttpClient
	 */
	public function getHttpClient()
	{
		return $this->_httpClient;
	}
}
