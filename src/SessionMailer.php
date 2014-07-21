<?php
/**
 * Session dump mailer - emails are stored into session (there are not sent)
 *
 * @author Jan Drábek
 * @author Matus Matula <matula.m at gmail (dot) com>
 * @version 2.0
 * @copyright New BSD
 */

namespace JanDrabek\MailPanel;


use Nette\Http\Session;
use Nette\Http\SessionSection;
use Nette\Mail\IMailer;
use Nette\Mail\Message;


/**
 * Session mailer - emails are stored into session
 *
 * @author Jan Drábek
 * @author Jan Marek
 * @license New BSD
 */
class SessionMailer implements IMailer {
	/** @var int */
	private $limit;

	/** @var SessionSection */
	private $sessionSection;

	public function __construct(Session $session, $limit = 100, $sectionName = __CLASS__) {
		$this->limit = $limit;
		$this->sessionSection = $session->getSection($sectionName);
	}


	/**
	 * Sends given message via this mailer
	 * @param Message $mail
	 */
	public function send(Message $mail) {
		$mails = array();
		if($this->sessionSection->offsetExists('sentMessages')) {
			$mails = $this->sessionSection->sentMessages;
		}

		if (count($mails) === $this->limit) {
			array_pop($mails);
		}

		// get message with generated html instead of set FileTemplate etc
		$reflectionMethod = $mail->getReflection()->getMethod('build');
		$reflectionMethod->setAccessible(TRUE);
		$builtMail = $reflectionMethod->invoke($mail);

		array_unshift($mails, $builtMail);

		$this->sessionSection->sentMessages = $mails;
	}


	public function getMessages($limit = NULL) {
		if($this->sessionSection->offsetExists('sentMessages')) {
			$messages = $this->sessionSection->sentMessages ?: array();
			return array_slice($messages, 0, $limit);
		}
		return array();
	}


	public function getMessageCount() {
		if($this->sessionSection->offsetExists('sentMessages')) {
			return count($this->sessionSection->sentMessages);
		}
		return 0;
	}


	public function clear() {
		$this->sessionSection->sentMessages = array();
	}


	public function deleteByIndex($index) {
		$messages = array();
		if($this->sessionSection->offsetExists('sentMessages')) {
			$messages = $this->sessionSection->sentMessages;
		}
		array_splice($messages, (int) $index, 1);
		$this->sessionSection->sentMessages = $messages;
	}

	/**
	 * Return limit of stored mails
	 * @return int
	 */
	public function getLimit() {
		return $this->limit;
	}

}
