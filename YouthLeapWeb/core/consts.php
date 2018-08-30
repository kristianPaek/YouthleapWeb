<?php

	// Error Code
	define('ERR_OK',                            '0');
	define('ERR_SQL',                           '1');
	define('ERR_INVALID_PKEY',                  '2');
	define('ERR_NODATA',                        '3');

	define('ERR_NODB',							'4');
	define('ERR_NOMEMCACHE',					'5');

	define('ERR_FAILLOGIN',                     '6');
	define('ERR_ALREADYLOGIN',                  '7');
	define('ERR_USER_DISABLED',                 '8');

	define('ERR_INVALID_REQUIRED',              '9');

	define('ERR_NOPRIV',                        '10');
	define('ERR_NOT_LOGINED',                   '11');
	define('ERR_FAIL_UPLOAD',                   '12');

	define('ERR_INVALID_IMAGE',                 '13');
	define('ERR_INVALID_PDF',                   '14');
	define('ERR_USER_LOCKED',                   '15');
	define('ERR_USER_UNACTIVATED',              '16');
	define('ERR_DUPLICATE_LOGINID',             '17');

	define('ERR_ALREADYINSTALLED',              '23');

	define('ERR_NOTFOUND_PAGE',                 '27');

	define('ERR_INVALID_OLDPWD',                '28');

	define('ERR_ALREADY_USING_LOGIN_ID',        '29');
	define('ERR_ALREADY_USING_EMAIL',           '30');
	define('ERR_ALREADY_USING_BALIAS',			'31');

	define('ERR_INVALID_ACTIVATE_KEY',          '35');
	define('ERR_ACTIVATE_EXPIRED',              '36');
	define('ERR_INVALID_EMAIL',                 '37');

	define('ERR_NOTFOUND_USER',                 '38');

	define('ERR_DELUSER',                       '40');
	define("ERR_DELBCAT",						'41');

	define('ERR_FAILWRITEFILE',					'50');
	define('ERR_UPLOAD_ZEROFILE',				'51');

	define('ERR_BLOG_SHORT_CONTENT',            '60');
	define('ERR_BLOG_ALREADY_LIKE',             '61');
	define('ERR_BLOG_ALREADY_DISLIKE',          '62');

	define('ERR_INQUIRY_ALREADY_LIKE',          '71');
	define('ERR_INQUIRY_ALREADY_DISLIKE',       '72');

	define('ERR_INVALID_CERT',					'1001');
	define('ERR_INVALID_TOKEN',									'73');

	define('CODE_UTYPE',                        0);
	define('CODE_SEX',                          1);
	define('CODE_PAGE',                         2);
	define('CODE_LANG',                         3);
	define('CODE_LOCK',                         4);
	define('CODE_ENABLE',                       5);
	define('CODE_LOGTYPE',											6);
	define("CODE_PSORT",												7);
	define("CODE_LOOKUP",												8);
	define("CODE_VIDEOTYPE",										9);
	define("CODE_TRANSTYPE",										10);

	// User Priv
	define('UTYPE_NONE',                        0);
	define('UTYPE_ADMIN',                       1); // Admin
	define('UTYPE_SCHOOL',                   		2); // School
	define('UTYPE_TUTOR',                   		4); // Tutor
	define('UTYPE_STUDENT',                   	8); // Student
	define('UTYPE_PARENT',                   		16); // Parent

	define('UTYPE_LOGINUSER',                   UTYPE_ADMIN | UTYPE_SCHOOL | UTYPE_TUTOR | UTYPE_STUDENT | UTYPE_PARENT);

	define('SEX_MAN',                           0); // Male
	define('SEX_WOMAN',                         1); // Female

	define('LANG_EN_ES',                        'en_es'); // English

	define('UNLOCKED',                          0); // Unlocked
	define('LOCKED',                            1); // Locked

	define('DISABLED',                          0); // Disable
	define('ENABLED',                           1); // Enable

	define('LOGTYPE_ACCESS',										0);
	define('LOGTYPE_OPERATION',									1);
	define('LOGTYPE_WARNING',										2);
	define('LOGTYPE_ERROR',											3);
	define('LOGTYPE_DEBUG',											4);
	define('LOGTYPE_BATCH',											5);
	
	define('ACTIONTYPE_HTML',                   0);
	define('ACTIONTYPE_AJAXJSON',               1);
	define('ACTIONTYPE_AJAXHTML',               2);

	define("PSORT_NEWEST",											0);
	define("PSORT_OLDEST",											1);

	define("PTYPE_TUTOR",												0);
	define("PTYPE_STUDENT",											1);

	define("LOOKUP_EDUCATION",									1);
	define("LOOKUP_RELATIONS",									2);
	define("LOOKUP_PURPOSE",										3);
	define("LOOKUP_VIDEO",											4);
	define("LOOKUP_MOOD",												5);

	define("LOOKUP_PARENT",											1);
	define("LOOKUP_CHILD",											2);

	define("VIDEO_PUBLIC",											0);
	define("VIDEO_PRIVATE",											1);

	define("TRANS_TYPE_IN",											-1);
	define("TRANS_TYPE_OUT",										1);
