<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Mmi\View\Helper;

class HelperAbstract {

	/**
	 * Referencja do widoku
	 * @var \Mmi\View
	 */
	public $view;

	/**
	 * Metoda programisty końcowego, wykonuje się na końcu konstruktora
	 */
	public function init() {
		
	}

	/**
	 * Konstruktor, ustawia widok
	 */
	public function __construct() {
		$this->view = \Mmi\App\FrontController::getInstance()->getView();
		$this->init();
	}

}
