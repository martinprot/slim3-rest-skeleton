<?php

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\DataAccess\DataAccess;
use App\Utils\ResponseSerializer;

/**
 * Class BaseController.
 */
class BaseController
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \App\DataAccess
     */
    protected $dataaccess;

    /**
     * @param \Psr\Log\LoggerInterface       $logger
     * @param \App\DataAccess                $dataaccess
     */
    public function __construct(LoggerInterface $logger, DataAccess $dataaccess)
    {
        $this->logger = $logger;
        $this->dataaccess = $dataaccess;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getAll(Request $request, Response $response, $args)
    {
        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);

        $path = explode('/', $request->getUri()->getPath())[1];
		$arrparams = $request->getParams();
		$all = $this->dataaccess->getAll($path, $arrparams);

		$serializer = new ResponseSerializer($response);
		return $serializer->success($all);
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get(Request $request, Response $response, $args)
    {
        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);

        $path = explode('/', $request->getUri()->getPath())[1];

		$serializer = new ResponseSerializer($response);
        $result = $this->dataaccess->get($path, $args);
        if ($result == null) {			
			return $serializer->error(404, "the resource cannot be found");
        } else {
			return $serializer->success($result);
        }
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function add(Request $request, Response $response, $args)
    {
        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);

        $path = explode('/', $request->getUri()->getPath())[1];
        $request_data = $request->getParsedBody();

		$last_inserted_id = $this->dataaccess->add($path, $request_data);
		
        if ($last_inserted_id > 0) {
            $RequesPort = '';
		    if ($request->getUri()->getPort()!='')
		    {
		        $RequesPort = '.'.$request->getUri()->getPort();
		    }
            $LocationHeader = $request->getUri()->getScheme().'://'.$request->getUri()->getHost().$RequesPort.$request->getUri()->getPath().'/'.$last_inserted_id;

			$serializer = new ResponseSerializer($response->withHeader('Location', $LocationHeader));
			return $serializer->created();
        } else {
			$serializer = new ResponseSerializer($response);
            return $serializer->error(403, "the resource cannot be created");
        }
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update(Request $request, Response $response, $args)
    {
        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);

        $path = explode('/', $request->getUri()->getPath())[1];
        $request_data = $request->getParsedBody();

		$serializer = new ResponseSerializer($response);
        $isupdated = $this->dataaccess->update($path, $args, $request_data);
        if ($isupdated) {
            return $serializer->updated();
        } else {
            return $serializer->error(404, "the resource cannot be found");
        }
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete(Request $request, Response $response, $args)
    {
        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);

        $path = explode('/', $request->getUri()->getPath())[1];

		$serializer = new ResponseSerializer($response);
        $isdeleted = $this->dataaccess->delete($path, $args);
        if ($isdeleted) {
            return $serializer->updated();
        } else {
            return $serializer->error(404, "the resource cannot be found");
        }
    }
}
