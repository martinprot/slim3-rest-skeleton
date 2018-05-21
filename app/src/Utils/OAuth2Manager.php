<?php

namespace App\Utils;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use OAuth2\HttpFoundationBridge\Request as BridgeRequest;

class OAuth2Manager {
	
	 /**
     * @var oAuth2server
     */
    protected $oAuth2server;

    /**
     * @var authenticated
     */
	protected $authenticated;

    /**
     * @var userId
     */
	protected $userId;
	

	public function __construct($server, Request $request)
    {
		$this->oAuth2server = $server;
        
        // convert a request from PSR7 to hhtpFoundation
        $httpFoundationFactory = new HttpFoundationFactory();
        $symfonyRequest = $httpFoundationFactory->createRequest($request);
        $bridgeRequest = BridgeRequest::createFromRequest($symfonyRequest);

        if ($this->oAuth2server->verifyResourceRequest($bridgeRequest)) {
        	// store the user_id
        	$token = $this->oAuth2server->getAccessTokenData($bridgeRequest);
			$this->authenticated = true;
			if (!empty($token['user_id'])) {
				$this->userId = $token['user_id'];
			}
		}
		else {
			$this->authenticated = false;
			$this->userId = null;
		}
    }

	public function isAuthenticated() {
		return $this->authenticated;
	}

	public function isLoggued() {
		return $this->authenticated && $this->userId != null;
	}

	public function getUserId() {
		return $this->userId;
	}
};