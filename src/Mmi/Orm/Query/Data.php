<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Mmi\Orm\Query;

class Data {

	/**
	 * Obiekt zapytania
	 * @var \Mmi\Orm\Query
	 */
	protected $_query;

	/**
	 * Konstruktor
	 * @param \Mmi\Orm\Query $query
	 */
	public function __construct(\Mmi\Orm\Query $query) {
		$this->_query = $query;
	}

	/**
	 * Fabryka obiektów
	 * @param \Mmi\Orm\Query $query
	 * @return \Mmi\Orm\Query
	 */
	public static function factory(\Mmi\Orm\Query $query) {
		return new self($query);
	}

	/**
	 * Pobiera ilość rekordów
	 * @return int
	 */
	public final function count($column = '*') {
		//wykonanie zapytania zliczającego na adapter
		$result = \Mmi\Orm::getAdapter()->select('COUNT(' . ($column === '*' ? '*' : \Mmi\Orm::getAdapter()->prepareField($column)) . ')', $this->_prepareFrom(), $this->_query->getQueryCompile()->where, $this->_query->getQueryCompile()->groupBy, '', null, null, $this->_query->getQueryCompile()->bind);
		return isset($result[0]) ? current($result[0]) : 0;
	}

	/**
	 * Pobiera wszystkie rekordy i zwraca ich kolekcję
	 * @return \Mmi\Orm\Record\Collection
	 */
	public final function find() {
		//odpytanie adaptera o rekordy
		$result = \Mmi\Orm::getAdapter()->select($this->_prepareFields(), $this->_prepareFrom(), $this->_query->getQueryCompile()->where, $this->_query->getQueryCompile()->groupBy, $this->_query->getQueryCompile()->order, $this->_query->getQueryCompile()->limit, $this->_query->getQueryCompile()->offset, $this->_query->getQueryCompile()->bind);
		//ustalenie klasy rekordu
		$recordName = $this->_query->getRecordName();
		$records = [];
		//tworzenie rekordów
		foreach ($result as $row) {
			$record = new $recordName();
			/* @var $record \Mmi\Orm\Record */
			$record->setFromArray($row)->clearModified();
			$records[] = $record;
		}
		//ustalenie klasy kolekcji rekordów
		$collectionName = $this->_query->getCollectionName();
		//zwrot kolekcji
		return new $collectionName($records);
	}

	/**
	 * Pobiera obiekt pierwszy ze zbioru
	 * null jeśli brak danych
	 * @param \Mmi\Orm\Query $q Obiekt zapytania
	 * @return \Mmi\Orm\Record\Ro
	 */
	public final function findFirst() {
		//odpytanie adaptera o rekordy
		$result = \Mmi\Orm::getAdapter()->select($this->_prepareFields(), $this->_prepareFrom(), $this->_query->getQueryCompile()->where, $this->_query->getQueryCompile()->groupBy, $this->_query->getQueryCompile()->order, 1, $this->_query->getQueryCompile()->offset, $this->_query->getQueryCompile()->bind);
		//null jeśli brak danych
		if (!is_array($result) || !isset($result[0])) {
			return null;
		}
		//ustalenie klasy rekordu
		$recordName = $this->_query->getRecordName();
		/* @var $record \Mmi\Orm\Record\Ro */
		$record = new $recordName;
		return $record->setFromArray($result[0])->clearModified();
	}

	/**
	 * Zwraca tablicę asocjacyjną (pary)
	 * @param string $keyName
	 * @param string $valueName
	 * @return array
	 */
	public final function findPairs($keyName, $valueName) {
		//odpytanie adaptera o rekordy
		$data = \Mmi\Orm::getAdapter()->select(\Mmi\Orm::getAdapter()->prepareField($keyName) . ', ' . \Mmi\Orm::getAdapter()->prepareField($valueName), $this->_prepareFrom(), $this->_query->getQueryCompile()->where, $this->_query->getQueryCompile()->groupBy, $this->_query->getQueryCompile()->order, $this->_query->getQueryCompile()->limit, $this->_query->getQueryCompile()->offset, $this->_query->getQueryCompile()->bind);
		$kv = [];
		foreach ($data as $line) {
			//przy wybieraniu tych samych pól tabela ma tylko jeden wiersz
			if (count($line) == 1) {
				$line = current($line);
			}
			//klucz to pierwszy element, wartość - drugi
			$kv[current($line)] = next($line);
		}
		return $kv;
	}

	/**
	 * Pobiera wartość maksymalną z kolumny
	 * @param string $keyName nazwa klucza
	 * @return string wartość maksymalna
	 */
	public final function findMax($keyName) {
		//odpytanie adaptera o rekord
		$result = \Mmi\Orm::getAdapter()->select('MAX(' . \Mmi\Orm::getAdapter()->prepareField($keyName) . ')', $this->_prepareFrom(), $this->_query->getQueryCompile()->where, $this->_query->getQueryCompile()->groupBy, $this->_query->getQueryCompile()->order, 1, null, $this->_query->getQueryCompile()->bind);
		return isset($result[0]) ? current($result[0]) : null;
	}

	/**
	 * Pobiera wartość minimalną z kolumny
	 * @param string $keyName nazwa klucza
	 * @return string wartość minimalna
	 */
	public final function findMin($keyName) {
		//odpytanie adaptera o rekord
		$result = \Mmi\Orm::getAdapter()->select('MIN(' . \Mmi\Orm::getAdapter()->prepareField($keyName) . ')', $this->_prepareFrom(), $this->_query->getQueryCompile()->where, $this->_query->getQueryCompile()->groupBy, $this->_query->getQueryCompile()->order, 1, null, $this->_query->getQueryCompile()->bind);
		return isset($result[0]) ? current($result[0]) : null;
	}

	/**
	 * Pobiera unikalne wartości kolumny
	 * @param string $keyName nazwa klucza
	 * @return array mixed wartości unikalne
	 */
	public final function findUnique($keyName) {
		//odpytanie adaptera o rekordy
		$data = \Mmi\Orm::getAdapter()->select('DISTINCT(' . \Mmi\Orm::getAdapter()->prepareField($keyName) . ')', $this->_prepareFrom(), $this->_query->getQueryCompile()->where, $this->_query->getQueryCompile()->groupBy, $this->_query->getQueryCompile()->order, null, null, $this->_query->getQueryCompile()->bind);
		$result = [];
		//dodaje kolejne wartości do tablicy
		foreach ($data as $line) {
			$result[] = current($line);
		}
		return $result;
	}

	/**
	 * Przygotowuje pola do selecta
	 * @return string
	 */
	protected final function _prepareFields() {
		//jeśli pusty schemat połączeń
		if (empty($this->_query->getQueryCompile()->joinSchema)) {
			return '*';
		}
		$fields = '';
		//pobranie struktury tabeli
		$mainStructure = \Mmi\Orm::getTableStructure($this->_query->getTableName());
		$table = \Mmi\Orm::getAdapter()->prepareTable($this->_query->getTableName());
		//pola z tabeli głównej
		foreach ($mainStructure as $fieldName => $info) {
			$fields .= $table . '.' . \Mmi\Orm::getAdapter()->prepareField($fieldName) . ', ';
		}
		//pola z tabel dołączonych
		foreach ($this->_query->getQueryCompile()->joinSchema as $tableName => $schema) {
			//pobranie struktury tabeli dołączonej
			$structure = \Mmi\Orm::getTableStructure($tableName);
			$joinedTable = \Mmi\Orm::getAdapter()->prepareTable($tableName);
			//pola tabeli dołączonej
			foreach ($structure as $fieldName => $info) {
				$fields .= $joinedTable . '.' . \Mmi\Orm::getAdapter()->prepareField($fieldName) . ' AS ' . \Mmi\Orm::getAdapter()->prepareField($tableName . '__' . $fieldName) . ', ';
			}
		}
		return rtrim($fields, ', ');
	}

	/**
	 * Przygotowuje sekcję FROM
	 * @return string
	 */
	protected final function _prepareFrom() {
		$table = \Mmi\Orm::getAdapter()->prepareTable($this->_query->getTableName());
		//jeśli brak joinów sama tabela
		if (empty($this->_query->getQueryCompile()->joinSchema)) {
			return $table;
		}
		$baseTable = $table;
		//przygotowanie joinów
		foreach ($this->_query->getQueryCompile()->joinSchema as $joinTable => $condition) {
			$targetTable = isset($condition[2]) ? $condition[2] : $baseTable;
			$joinType = isset($condition[3]) ? $condition[3] : 'JOIN';
			$table .= ' ' . $joinType . ' ' . \Mmi\Orm::getAdapter()->prepareTable($joinTable) . ' ON ' .
				\Mmi\Orm::getAdapter()->prepareTable($joinTable) . '.' . \Mmi\Orm::getAdapter()->prepareField($condition[0]) .
				' = ' . \Mmi\Orm::getAdapter()->prepareTable($targetTable) . '.' . \Mmi\Orm::getAdapter()->prepareField($condition[1]);
		}
		return $table;
	}

}
