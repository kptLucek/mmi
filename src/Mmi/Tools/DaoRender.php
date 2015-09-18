<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Mmi\Tools;

//nie ma tu jeszcze autoloadera ładowanie CliAbstract
require_once 'CliAbstract.php';

/**
 * Renderer DAO, rekordów, zapytań itd.
 */
class DaoRenderer extends CliAbstract {

	/**
	 * Metoda uruchamiająca
	 */
	public function run() {

		//odbudowanie wszystkich DAO/Record/Query/Field/Join
		foreach (\App\Registry::$db->tableList(\App\Registry::$config->db->schema) as $tableName) {
			//bez generowania dla DB_CHANGELOG
			if (strtoupper($tableName) == 'DB_CHANGELOG') {
				continue;
			}
			//buduje struktruę dla tabeli
			\Mmi\Orm\Builder::buildFromTableName($tableName);
		}
	}

}

//powołanie obiektu
new DaoRenderer();
