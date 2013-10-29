MailPanel
=========

Panel for Nette DebugBar. Instead of sending all e-mails are stored into session from which they can be viewed in debugger bar. Supports plaintexts and HTML e-mail, multiple recipients etc.

Demo
----

![](http://i49.tinypic.com/54doav.png)

Startup
-------

 * Unpack archive/directory into directory which is indexed by RobotLoader
 * Register different mailer in config.neon (or in something like config.development.neon)

```
services:
    nette.mailer:
        class: JanDrabek\MailPanel\SessionDumpMailer
```

 * Add extension into DebugBar

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

All mails instantiated through:
```
$container->nette->createMail();
```
will be sent by SessionDumpMailer into session and will be available in DebugBar.

Potential problems
------------------

Due to the method of populating mails into the iframe element with javascript, there can be some problems. When it happen, please, send me an e-mail or open an issue. Thanks :)
 
