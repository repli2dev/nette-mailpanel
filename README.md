MailPanel
=========

Panel for Nette Debug panel.
Instead of sending, all e-mails are stored into session from which they can be viewed in debug bar.
Supports plaintexts and HTML e-mail, multiple recipients etc.

* Authors: Jan Marek, Jan Drábek
* License: New BSD

Based on http://git.yavanna.cz/p/mailpanel/ by Jan Drábek and

Demo
----

![](http://i59.tinypic.com/4q6kb7.png)

Installation
------------

Install library via composer:

```
composer require jandrabek/nette-mailpanel
```


Register different mailer in config.neon (or in something like config.development.neon)

```
services:
    nette.mailer: JanDrabek\MailPanel\SessionMailer
```

```
nette:
    debugger:
        bar: [JanDrabek\MailPanel\MailPanel]
# Or when you register multiple extensions
#       bar:
#           - JanDrabek\MailPanel\MailPanel
```

Usage
-----

```php
class ExamplePresenter extends BasePresenter {

	private $mailer;

	public function injectMailer(Nette\Mail\IMailer $mailer) {
		$this->mailer = $mailer;
	}

	public function renderDefault() {
		$mail = new Nette\Mail\Message;
		$mail->setFrom('foo@bar.net');
		$mail->addTo('john@doe.cz');
		$mail->setSubject('Subject');
		$mail->setBody('Message body');

		$this->mailer->send($mail);
	}

}
```

Potential problems
------------------

Due to the method of populating mails into the iframe element with javascript, there can be some problems. When it happen, please, send me an e-mail or open an issue. Thanks :)
 
