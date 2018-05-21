<?php

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use App\Utils\ResponseSerializer as ResponseSerializer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class TestController
 */
final class TestController
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param string $powered
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger  = $logger;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function needsToBeAuth(Request $request, Response $response, $args)
    {
		$serializer = new ResponseSerializer($response);
		return $serializer->success();
	}
	
	/**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function needsToBeLoggued(Request $request, Response $response, $args)
    {
		$user = $request->getAttribute('user');
		$serializer = new ResponseSerializer($response);
		return $serializer->success($user);
    }
}
