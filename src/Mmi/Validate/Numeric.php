<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Mmi\Validate;

class Numeric extends ValidateAbstract {

	/**
	 * Treść wiadomości
	 */
	const INVALID = 'Wprowadzona wartość nie jest liczbą';

	/**
	 * Walidacja liczb
	 * @param mixed $value wartość
	 * @return boolean
	 */
	public function isValid($value) {
		if (!is_numeric($value)) {
			$this->_error(self::INVALID);
			return false;
		}
		return true;
	}

}
