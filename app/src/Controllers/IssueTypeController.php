<?php

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\DataAccess\IssueTypeAccess;
use App\Utils\ResponseSerializer;
use App\Utils\APIDate;
use App\DataAccess\InputException;

use PDO;
use PDOException;

/**
 * Class IssueTypeController.
 */
final class IssueTypeController extends GenericController
{

    public function __construct(LoggerInterface $logger, PDO $pdo)
    {
        $this->logger = $logger;
        $this->dataaccess = new IssueTypeAccess($logger, $pdo);
	}

	public function add(Request $request, Response $response, $args) 
	{
		$serializer = new ResponseSerializer($response);
		$authentifiedUser = $request->getAttribute("user");;
		if ($authentifiedUser["admin"] == false) {
			// only for admmin
			return $serializer->error(403, "not authorized.");
		}
		return parent::add($request, $response, $args);	
	}

	public function delete(Request $request, Response $response, $args)
	{
		$serializer = new ResponseSerializer($response);
		$authentifiedUser = $request->getAttribute("user");;
		if ($authentifiedUser["admin"] == false) {
			// only for admmin
			return $serializer->error(403, "not authorized.");
		}
		return parent::delete($request, $response, $args);
	}
}