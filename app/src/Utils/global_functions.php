<?php

function array_keys_exist($keys, $array) {
	foreach ($keys as $key) {
		if (!array_key_exists($key, $array)) {
			return false;
		}
	}
	return true;
}

function getKey($key, $array) {
	if (array_key_exists($key, $array)) {
		return $array[$key];
	}
	return null;
}