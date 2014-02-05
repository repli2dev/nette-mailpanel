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

use Exception,
	Factories\IMailMessageFactory,
	MessageMailer,
	Nette;

class SessionDumpMailer extends MessageMailer
{
	/** @var int */
	private static $limit = 10000;

	/** @var Nette\Session\SessionSection */
	private static $storage;


	const SECTION_NAME = "DumpMailerStorage";


	/**
	 * Sets limit of maximal stored messages
	 * @param int value
	 */
	public function setLimit($value)
	{
		if (!is_numeric($value) || $value <= 0) {
			throw new Exception("Wrong value.");
		}
		self::$limit = $value;
	}


	/**
	 * Return limit of stored mails
	 * @return int
	 */
	public function getLimit()
	{
		return self::$limit;
	}


	/**
	 * Sends given message via this mailer
	 * @param Nette\Mail\Message $mail
	 */
	public function send(Nette\Mail\Message $mail)
	{
		if (self::$storage === NULL) {
			throw new Nette\InvalidStateException("No session given into mailer. Cannot send this message.");
		}
		if (self::$storage->queue === NULL || !self::$storage->queue instanceof Nette\ArrayList) {
			self::$storage->queue = new Nette\ArrayList();
		}
		$queue = self::$storage->queue;
		if (count($queue) >= $this->getLimit()) {
			$queue->offsetUnset($queue->getIterator()->key());
		}
		$queue[] = $mail;
	}


	/**
	 * Inject session into this object, it is used as temporary data storage
	 * @param IMailMessageFactory $messageFactory
	 * @param Nette\Http\Session $session
	 */
	public function __construct(IMailMessageFactory $messageFactory, Nette\Http\Session $session)
	{
		parent::__construct($messageFactory);
		self::$storage = $session->getSection(self::SECTION_NAME);
	}


}