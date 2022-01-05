<?php
// App Config
define('USE_TWIG', true);
define('USE_SSL', false);

define('HTTP_SERVER', 'http://rep.fox3.com.br/divino/');
define('HTTPS_SERVER', 'https://rep.fox3.com.br/divino/');
// define('HTTP_SERVER', 'http://rep.fox3.com.br/divino/');
// define('HTTPS_SERVER', 'https://rep.fox3.com.br/divino/');

define('OLD_SERVER', 'http://beto.fox3.com.br/beto-v2/');

if (USE_SSL == true) {
  define('UPLOADS', 'http://rep.fox3.com.br/divino/framework/storage/uploads/');
  define('DEFAULT_IMG_USER', 'http://rep.fox3.com.br/divino/assets/img/default-avatar.png');
  define('DEFAULT_NO_PHOTO', 'http://rep.fox3.com.br/divino/assets/img/no_photo.png');
} else {
  define('UPLOADS', 'http://rep.fox3.com.br/divino/framework/storage/uploads/');
  define('DEFAULT_IMG_USER', 'http://rep.fox3.com.br/divino/assets/img/default-avatar.png');
  define('DEFAULT_NO_PHOTO', 'http://rep.fox3.com.br/divino/assets/img/no_photo.png');
}

// // DB Config
// define('DB_TYPE', "mysql");
// define('DB_HOST', "189.126.106.70");
// define('DB_PORT', "3306");
// define('DB_USER', "fox3");
// define('DB_PASSWORD', "f0x1990");
// define('DB_NAME', "divino");

// DB Config
define('DB_TYPE', "mysql");
define('DB_HOST', "192.240.118.114");
define('DB_PORT', "3306");
define('DB_USER', "divinos");
define('DB_PASSWORD', "f0x@1990_divino");
define('DB_NAME', "divinos");