<?php
// App Config
define('USE_TWIG', true);
define('USE_SSL', false);

define('HTTP_SERVER', 'http://192.240.118.114/divinos/admin/');
define('HTTPS_SERVER', 'https://192.240.118.114/divinos/admin/');

define('OLD_SERVER', 'http://beto.fox3.com.br/beto-v2/');

if (USE_SSL == true) {
  define('UPLOADS', 'https://192.240.118.114/divinos/framework/storage/uploads/');
  define('DEFAULT_IMG_USER', 'https://192.240.118.114/divinos/assets/img/default-avatar.png');
  define('DEFAULT_NO_PHOTO', 'https://192.240.118.114/divinos/assets/img/no_photo.png');
} else {
  define('UPLOADS', 'http://192.240.118.114/divinos/framework/storage/uploads/');
  define('DEFAULT_IMG_USER', 'http://192.240.118.114/divinos/assets/img/default-avatar.png');
  define('DEFAULT_NO_PHOTO', 'http://192.240.118.114/divinos/assets/img/no_photo.png');
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
define('DB_HOST', "localhost");
define('DB_PORT', "3306");
define('DB_USER', "divinos");
define('DB_PASSWORD', "f0x@1990_divino");
define('DB_NAME', "divinos");