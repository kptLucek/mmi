<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Mmi\Validator;

class Equal extends ValidatorAbstract {

	/**
	 * Treść wiadomości
	 */
	const INVALID = 'Wprowadzona wartość nie jest poprawna';

	/**
	 * Komunikat zaznaczenia pola
	 */
	const CHECKBOX_INVALID = 'Zaznaczenie jest wymagane';

	/**
	 * Walidacja porówniania wartości
	 * @param mixed $value wartość
	 * @return boolean
	 */
	public function isValid($value) {
		if (!isset($this->_options['value']) || $this->_options['value'] != $value) {
			if (isset($this->_options['type']) && $this->_options['type'] == 'checkbox') {
				$this->_error(self::CHECKBOX_INVALID);
			} else {
				$this->_error(self::INVALID);
			}
			return false;
		}
		return true;
	}

}