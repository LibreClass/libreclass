<?php

return array(
	/*
  | Supported: "smtp", "mail", "sendmail", "mailgun", "mandrill", "log"
  */
  'driver'     => $_ENV['EMAIL_DRIVER'],
  'host'       => $_ENV['EMAIL_HOST'],
  'port'       => $_ENV['EMAIL_PORT'],
  'from'       => array('address' => $_ENV['EMAIL_FROMADD'], 'name' => $_ENV['EMAIL_FROMNAM']),
  'encryption' => $_ENV['EMAIL_ENC'],
  'username'   => $_ENV['EMAIL_UNAME'],
  'password'   => $_ENV['EMAIL_PASS'],
  'sendmail'   => '/usr/sbin/sendmail -bs',
  'pretend'    => false,
);
