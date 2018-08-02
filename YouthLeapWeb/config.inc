<?php
define('VERSION',		'1.0.0');

define('DEBUG_MODE',		0);
define('ENABLE_SSL',		0);

define('TIME_ZONE',		'America/New_York');

/* Frontend setting */
define('FRONTEND_APP',	'application');

/* EShop, Bookstore setting */
define('ENABLE_ESHOP',			true);
define('ENABLE_BOOKSTORE',			false);

/* DB related */
define('DB_HOSTNAME',		'localhost');
define('DB_USER',		'root');
define('DB_PASSWORD',	'');
define('DB_NAME',		'youthleap');
define('DB_PORT',		3306);

/* Memcached */
define("CACHE_USE_MEMCACHE",	false);
define('MEMCACHE_SERVER',		'localhost');
define('MEMCACHE_PORT',		11211);

/* Contact info */
define('CONTACT_TEL',		'123456789');

/* E-mail related */
define('MAIL_ENABLE',		1);
define('MAIL_FROM',		'admin@gmail.com');
define('MAIL_FROMNAME',		'YouthLeap');
define('MAIL_SMTP_AUTH',		1);
define('MAIL_SMTP_USE_SSL',		0);
define('MAIL_SMTP_SERVER',		'mail');
define('MAIL_SMTP_USER',		'admin');
define('MAIL_SMTP_PASSWORD',		'123');
define('MAIL_SMTP_PORT',		'25');

/* Security policy */
define('PASSWORD_MIN_LENGTH',		5);
define('LOGIN_FAIL_LOCK',		5);
define('RANDOM_SEED',		'YouthLeap');
define('DIST_INC_COUNT',		2);
define('DIST_INC_NO',		0);

/* Media image resize */
define('MEDIA_APP_W',		120);
define('MEDIA_APP_H',		120);
define('MEDIA_THUMB_W',	220);
define('MEDIA_THUMB_H',	220);
define('MEDIA_MD_W',		400);
define('MEDIA_MD_H',		400);
define('MEDIA_LG_W',		1200);
define('MEDIA_LG_H',		1200);

/* Solr Search Engine */
define('SOLR_HOSTNAME',	'192.168.13.66');
define('SOLR_PORT',		8983);
define('SOLR_PATH',		'/solr/pic');

/* URLs */
define('SCHOOL_URL',	'http://localhost/youthleap/school/');
define('TUTOR_URL',	'http://localhost/youthleap/tutor/');
define('STUDENT_URL',	'http://localhost/youthleap/student/');
define('PARENT_URL',	'http://localhost/youthleap/parent/');

/* Language */
define('DEFAULT_LANGUAGE',		'en_es');
