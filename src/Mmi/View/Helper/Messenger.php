<?php

/**
 * Mmi Framework (https://bitbucket.org/mariuszmilejko/mmicms/)
 * 
 * @link       https://bitbucket.org/mariuszmilejko/mmicms/
 * @copyright  Copyright (c) 2010-2015 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace Mmi\View\Helper;

class Messenger extends HelperAbstract {

	/**
	 * Metoda główna, wyświetla i czyści dostępne wiadomości
	 * @return string
	 */
	public function messenger() {
		$messenger = new \Mmi\Controller\Action\Helper\Messenger();
		$messages = $messenger->getMessages();
		if ($messenger->hasMessages()) {
			$html = '<ul id="messenger">';
			foreach ($messages as $message) {
				$class = ' class="notice warning"';
				$icon = '<i class="icon-warning-sign icon-large"></i>';
				if ($message['type']) {
					$class = ' class="notice ' . $message['type'] . '"';
					$icon = ($message['type'] == 'error') ? '<i class="icon-remove-sign icon-large"></i>' : '<i class="icon-ok icon-large"></i>';
				}
				$html .= '<li' . $class . '>' . $icon . '<div class="alert">' . $this->_prepareTranslatedMessage($message) . '<a class="close-alert" href="#"></a></div></li>';
			}
			$html .= '</ul>';
			$messenger->clearMessages();
			return $html;
		}
	}
	
	protected function _prepareTranslatedMessage(array $message = []) {
		$translatedMessage = ($this->view->getTranslate() !== null) ? $this->view->getTranslate()->_($message['message']) : $message['message'];
		array_unshift($message['vars'], $translatedMessage);
		return call_user_func_array('sprintf', $message['vars']);
	}

}
