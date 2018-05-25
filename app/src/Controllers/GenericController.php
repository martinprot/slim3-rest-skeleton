<?php

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\DataAccess\ResourceAccess;
use App\Utils\ResponseSerializer;

/**
 * Class GenericController.
 */
class GenericController
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \App\ResourceAccess
     */
    protected $dataaccess;

    /**
     * @param \Psr\Log\LoggerInterface       $logger
     * @param \App\ResourceAccess                $dataaccess
     */
    public function __construct(LoggerInterface $logger, ResourceAccess $dataaccess)
    {
        $this->logger = $logger;
        $this->dataaccess = $dataaccess;
    }

	public function getAll(Request $request, Response $response, $args)
    {
		$serializer = new ResponseSerializer($response);
		$arrparams = $request->getParams();
		try {
			$all = $this->dataaccess->getAll(null, $arrparams);
		}
		catch (PDOException $e) {
			return $serializer->pdoError($e);
		}
		catch (InputException $e) {
			return $serializer->inputError($e);
		}
		return $serializer->success($all);
    }

    public function get(Request $request, Response $response, $args)
    {
		$serializer = new ResponseSerializer($response);
        $result = $this->dataaccess->get(null, $args);
        if ($result == null) {			
			return $serializer->error(404, "the resource cannot be found");
        } else {
			return $serializer->success($result);
        }
    }

    public function add(Request $request, Response $response, $args) 
	{
		$serializer = new ResponseSerializer($response);
		$request_data = $request->getParsedBody();
		try {
			$last_inserted_id = $this->dataaccess->add(null, $request_data);
			if ($last_inserted_id > 0) {
				$data = $this->dataaccess->getById($last_inserted_id);
				return $serializer->created($data);
			} else {
				return $serializer->error(403, "the resource cannot be created");
			}
		}
		catch (PDOException $e) {
            return $serializer->pdoError($e);
		}
		catch (InputException $e) {
            return $serializer->inputError($e);
		}
	}

    public function update(Request $request, Response $response, $args)
    {
		$serializer = new ResponseSerializer($response);
		$request_data = $request->getParsedBody();
		try {
			$isupdated = $this->dataaccess->update(null, $args, $request_data);
			if ($isupdated) {
				return $serializer->updated();
			} else {
				return $serializer->error(404, "the resource cannot be found");
			}
		}
		catch (PDOException $e) {
            return $serializer->pdoError($e);
		}
		catch (InputException $e) {
            return $serializer->inputError($e);
		}
	}

    public function delete(Request $request, Response $response, $args)
    {
		$serializer = new ResponseSerializer($response);
		try {
			$isdeleted = $this->dataaccess->delete(null, $args);
			if ($isdeleted) {
				return $serializer->deleted();
			} else {
				return $serializer->error(404, "the resource cannot be found");
			}
		}
		catch (PDOException $e) {
            return $serializer->pdoError($e);
		}
		catch (InputException $e) {
            return $serializer->inputError($e);
		}
	}
}
