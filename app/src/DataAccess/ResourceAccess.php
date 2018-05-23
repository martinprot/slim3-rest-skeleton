<?php

namespace App\DataAccess;

use Psr\Log\LoggerInterface;
use PDO;
use Exception;


class InputException extends Exception {

	public static function missingParameter() {
		return new InputException("missing parameter", 1);
	}
	public static function wrongDateFormat() {
		return new self("wrong date format", 2);
	}

	public function __construct($message, $code = 0, Exception $previous = null) {
		parent::__construct($message, $code, $previous);
	}

	public function __toString() {
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}
};


/**
 * Class UserAccess.
 */
abstract class ResourceAccess extends DataAccess {
	// Must be overriden in subclasses
	abstract public function tableName();
	abstract public function idColumn();
	abstract public function requiredFields();
	abstract public function optionalFields();

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param \PDO                     $pdo
     */
    public function __construct(LoggerInterface $logger, PDO $pdo)
    {
		parent::__construct($logger, $pdo, $this->tableName());
	}

	public function getById($id) {
		return $this->get(null, [$this->idColumn() => $id]);
	}

	/**
     * @return array
     */
    public function getAll($path, $arrparams)
    {
		$all_parameters = array_merge($this->requiredFields(), $this->optionalFields());
		$arrparams = array_intersect_key($arrparams, array_flip($all_parameters));

		return parent::getAll($path, $arrparams);
	}

	public function add($path, $request_data) {
		$all_parameters = array_merge($this->requiredFields(), $this->optionalFields());
		$resource_data = array_intersect_key($request_data, array_flip($all_parameters));


		if (!array_keys_exist($this->requiredFields(), $request_data)) {
			throw InputException::missingParameter();
		}
		return parent::add($path, $resource_data);
	}

	public function update($path, $args, $request_data)
    {
		$all_parameters = array_merge($this->requiredFields(), $this->optionalFields());
		$resource_data = array_intersect_key($request_data, array_flip($all_parameters));

		return parent::update($path, $args, $resource_data);
	}
}