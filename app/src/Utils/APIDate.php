<?php

namespace App\Utils;

class APIDate {

	private $date;
	private $containsTime;

	static $longFormat = "Y-m-d H:i:s";
	static $shortFormat = "Y-m-d H:i:s";

	public function __construct(DateTime $date) {
		$this->date = $date;
		$containsTime = true;
	}

	public static function fromString($dateString) {
		$instance = new self();
		$result = DateTime::createFromFormat($instance->longFormat, $dateString);
		if ($result !== false) {
			$instance->date = $result;
			$containsTime = true;
		}
		else {
			$result = DateTime::createFromFormat($instance->shortFormat, $dateString);
			if ($result !== false) {
				$instance->date = $result;
				$containsTime = false;
			}
			else {
				throw new Exception("wrong date format. Should be ". $instance->longFormat . " or " . $instance->shortFormat);
			}
		}
		return $instance;
	}

	public function rightDateFormat() {
		return $this->date != null;
	}

	public function toString() {
		if ($this->rightDateFormat()) {
			if ($this->containsTime) {
				return $this->date->format($this->longFormat);
			}
			else {
				return $this->date->format($this->shortFormat);
			}
		}
		else {
			return null;
		}
	}
}