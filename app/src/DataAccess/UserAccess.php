<?php

namespace App\DataAccess;

use Psr\Log\LoggerInterface;
use PDO;

/**
 * Class UserAccess.
 */
class UserAccess extends DataAccess {
	private static $tablename = "user";
	private static $idColumn = "id";
	    
    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param \PDO                     $pdo
     */
    public function __construct(LoggerInterface $logger, PDO $pdo)
    {
		parent::__construct($logger, $pdo, self::$tablename);
	}
	
	public function getUser($id) {
		return $this->get(null, [self::$idColumn => $id]);
	}
}