<?php

return array(
	/*
  | Supported: "smtp", "mail", "sendmail", "mailgun", "mandrill", "log"
  */
  'driver'     => 'smtp',
  'host'       => 'mail.sysvale.com',
  'port'       => 25,
  'from'       => array('address' => "contato@sysvale.com", 'name' => "LibreClass"),
  'encryption' => 'tls',
  'username'   => "contato@sysvale.com",
  'password'   => "PASSWORD",
  'sendmail'   => '/usr/sbin/sendmail -bs',
  'pretend'    => false,
);
