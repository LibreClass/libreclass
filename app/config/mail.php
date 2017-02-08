<?php

return array(
	/*
  | Supported: "smtp", "mail", "sendmail", "mailgun", "mandrill", "log"
  */
  'driver'     => 'smtp',
  'host'       => 'mail.libreclass.com',
  'port'       => 25,
  'from'       => array('address' => "contato@libreclass.com", 'name' => "LibreClass"),
  'encryption' => 'tls',
  'username'   => "contato@libreclass.com",
  'password'   => "SECRET",
  'sendmail'   => '/usr/sbin/sendmail -bs',
  'pretend'    => false,
);
