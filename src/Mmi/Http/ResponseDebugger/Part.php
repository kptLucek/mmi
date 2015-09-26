<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 * 
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Mmi\Http\ResponseDebugger;

/**
 * Klasa danych częściowych panelu deweloperskiego
 */
class Part {

	/**
	 * Zmienne środowiskowe
	 * @return string
	 */
	public static function getEnvHtml() {
		$env = \Mmi\App\FrontController::getInstance()->getEnvironment();
		return '<p style="margin: 0; padding: 0;">Connection: <b>' . $env->serverAddress . ':' . $env->serverPort . '</b> <---> <b>' . $env->remoteAddress . ':' . $env->remotePort . '</b></p>' .
			'<p style="margin: 0; padding: 0;">Browser: <b>' . substr($env->httpUserAgent, 0, 93) . '</b></p>' .
			'<p style="margin: 0; padding: 0;">Server: <b>' . $env->serverSoftware . ' + PHP ' . phpversion() . ' (' . php_sapi_name() . ', ' . php_uname('s') . ' ' . php_uname('m') . ': ' . php_uname('n') . ')</b></p>' .
			'<p style="margin: 0; padding: 0;">Path: <b>' . $env->scriptFilename . '</b></p>';
	}

	/**
	 * Konfiguracja PHP
	 * @return string
	 */
	public static function getConfigHtml() {
		return '<p style="margin: 0; padding: 0;">Include path: <b>' . ini_get('include_path') . '</b></p>' .
			'<p style="margin: 0; padding: 0;">Memory limit: <b>' . ini_get('memory_limit') . '</b></p>' .
			'<p style="margin: 0; padding: 0;">Register globals: <b>' . ((ini_get('register_globals') == 1) ? 'yes' : 'no') . '</b></p>' .
			'<p style="margin: 0 0 10px 0; padding: 0;">Magic quotes: <b>' . ((get_magic_quotes_gpc() == 1) ? 'yes' : 'no') . '</b></p>' .
			'<p style="margin: 0; padding: 0;">Short tags: <b>' . ((ini_get('short_open_tag') == 1) ? 'yes' : 'no') . '</b></p>' .
			'<p style="margin: 0; padding: 0;">Uploads allowed: <b>' . ((ini_get('file_uploads') == 1) ? 'yes' : 'no') . '</b></p>' .
			'<p style="margin: 0; padding: 0;">Upload maximal size: <b>' . ini_get('upload_max_filesize') . '</b></p>' .
			'<p style="margin: 0; padding: 0;">Upload directory: <b>' . ((ini_get('upload_tmp_dir')) ? ini_get('upload_tmp_dir') : 'system default') . '</b></p>' .
			'<p style="margin: 0; padding: 0;">POST maximal size: <b>' . ini_get('post_max_size') . '</b></p>';
	}

	/**
	 * Profiler DB
	 * @return string
	 */
	public static function getDbHtml() {
		if (\Mmi\Db\Profiler::count() == 0) {
			return 'No SQL queries.';
		}
		$html = '';
		$i = 0;
		//pętla po profilerze DB
		foreach (\Mmi\Db\Profiler::get() as $query) {
			$i++;
			$html .= $i . '. (<strong style="color: #' . self::_colorifyPercent($query['percent']) . '!important;">' . round($query['elapsed'], 4) . 's</strong>) - ' . Colorify::colorify($query['name']) . '<br />';
		}
		return $html;
	}

	/**
	 * Profiler
	 * @return string
	 */
	public static function getProfilerHtml() {
		$percentSum = 0;
		$html = '';
		//pętla po profilerze
		foreach (\Mmi\App\Profiler::get() as $event) {
			$percentSum += $event['percent'];
			$html .= '<div style="color: #' . self::_colorifyPercent($event['percent']) . '"><div style="float: left; min-width: 320px;">' . $event['name'] . '</div><div style="float: left; width: 60px;"><b>' . round($event['elapsed'], 4) . 's</b></div><div style="float: left; width: 60px;"><b>' . round($event['percent'], 2) . '%</b></div><div style="float: left;"><b>' . round($percentSum, 2) . '%</b></div></div><div style="clear: both"></div>';
		}
		return $html;
	}

	/**
	 * Rozszerzenia PHP
	 * @return string
	 */
	public static function getExtensionHtml() {
		$html = '';
		$extensions = get_loaded_extensions();
		//sortowanie rozszerzeń
		asort($extensions);
		$i = 0;
		//pętla po rozszerzeniach PHP
		foreach ($extensions as $ext) {
			$i++;
			$html .= $i . '. ' . $ext . '<br />';
		}
		return $html;
	}
	
	/**
	 * Kolorowanie wartości procentowej (0-100) w odcieniach czerwieni
	 * @param integer $percent
	 * @return string hex koloru
	 */
	protected static function _colorifyPercent($percent) {
		$boost = $percent * 15;
		return dechex(($boost > 255) ? 255 : $boost) . '2222';
	}

}