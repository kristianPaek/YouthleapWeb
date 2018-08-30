<?php

	$g_err_msgs = array(
		ERR_OK => 'SUCCESS',	
		ERR_SQL => 'Database Error',
		ERR_INVALID_PKEY => 'Invalid primary key',
		ERR_NODATA => 'No data',		

		ERR_NODB => 'Database connection failed.',	
		ERR_NOMEMCACHE => 'Memcache connection failed.',	
														 
		ERR_FAILLOGIN => 'Email or Password is incorrect.',
		ERR_ALREADYLOGIN => 'Already Login.',
		ERR_USER_DISABLED => 'User disabled.',
														 
		ERR_INVALID_REQUIRED => '',
														 
		ERR_NOPRIV => 'No priv',
		ERR_NOT_LOGINED => 'Not Logined.',
		ERR_FAIL_UPLOAD => 'Fail upload',

		ERR_INVALID_IMAGE => 'Invalid Image.',
		ERR_INVALID_PDF => 'Invalid PDF.',
		ERR_USER_LOCKED => 'User Locked .',
		ERR_USER_UNACTIVATED =>'User Unactivated',
		ERR_DUPLICATE_LOGINID => 'Duplicate Logined.',
		
		ERR_ALREADYINSTALLED => 'Already Installed.',

		ERR_NOTFOUND_PAGE => 'Notfound Page.',

		ERR_INVALID_OLDPWD => 'Invalid oldpassword.',

		ERR_ALREADY_USING_LOGIN_ID => 'Already Using Login ID.',
		ERR_ALREADY_USING_EMAIL => 'Already Using Login Email.',
		ERR_ALREADY_USING_BALIAS => 'Already Using URL.',

		ERR_INVALID_ACTIVATE_KEY => 'Invalid Activate Key.',
		ERR_ACTIVATE_EXPIRED => 'Activate Expired.',
		ERR_INVALID_EMAIL => 'Invalid Email.',

		ERR_NOTFOUND_USER => 'Notfound User.',

		ERR_DELUSER => 'Error Delete User.',

		ERR_FAILWRITEFILE => 'Fail Write File.',

		ERR_UPLOAD_ZEROFILE => 'Cannot upload 0Byte File.',

		ERR_INVALID_CERT => 'Invalid Cert.',
		ERR_INVALID_TOKEN=> 'Invalid Token'
	);

	$g_codes = array(
		CODE_UTYPE => array(
			UTYPE_ADMIN => 'Admin',
			UTYPE_SCHOOL => 'School',
			UTYPE_TUTOR => 'Tutor',
			UTYPE_PARENT => 'Parent',
			UTYPE_STUDENT => 'Student'
		),
		CODE_SEX => array(
			SEX_MAN => 'Male',
			SEX_WOMAN => 'Female'
		),
		CODE_LANG => array(
			LANG_EN_ES => 'English'
		),
		CODE_LOCK => array(
			UNLOCKED => 'Unlock',
			LOCKED => 'Lock'
		),
		CODE_ENABLE => array(
			ENABLED => 'Enabled',
			DISABLED => 'Disabled'
		),
		CODE_LOGTYPE => array(
			LOGTYPE_ACCESS => 'Access',
			LOGTYPE_OPERATION => 'Operation',
			LOGTYPE_WARNING => 'Warning',
			LOGTYPE_ERROR => 'Error',
			LOGTYPE_DEBUG => 'Debug',
			LOGTYPE_BATCH => 'Batch'
		),
		CODE_PSORT => array(
			PSORT_NEWEST => "Newest",
			PSORT_OLDEST => "Oldest",
		),
		CODE_LOOKUP => array(
			LOOKUP_PARENT => "Parent",
			LOOKUP_CHILD => "Child"
		),
		CODE_VIDEOTYPE => array(
			VIDEO_PUBLIC => "Public",
			VIDEO_PRIVATE => "Private"
		),
		CODE_TRANSTYPE => array(
			TRANS_TYPE_IN => "TRANS TYPE IN",
			TRANS_TYPE_OUT => "TRANS TYPE OUT"
		)
	);

	$g_string = array(
		'Home' => 'Home'
	);

	define("STR_HOME",						"Home");
	define("STR_YOUTHLEAP",						"YouthLeap");
	define("STR_ERROR_BROWSER",				"Error Browser");
	define("STR_INSTALL_CHROME", 			"Install Chrome");
	define("STR_CATEGORY",					"Category");
	define("STR_TAG",						"Tag");
	define("STR_REQUIRED_LOGIN",			"Required Login.");
	define("STR_NOTICE",					"Notice");
	define("STR_ERROR",						"Error");
	define("STR_ERROR_SAVE",				"Save Error");
	define("STR_SAVE",						"Save");
	define("STR_SAVE_SUCCESS",				"Save Success");
	define("STR_DESC_OPINION_SUCCESS",		"Opinion Success.");
	define("STR_HOME_ABOUTWE",				"About Us");
	define("STR_CONTACT_INFO",				"Contact");
	define("STR_YouthLeap_ADDRESS",			"NewYork");
	define("STR_TEL",						"Tel");
	define("STR_ADMIN_MAIL",				"Admin Mail");
	define("STR_ADMIN_MAIL_CCS",			"");
	define("STR_OPINION_BOX",				"Opinion Box");
	define("STR_SEND",						"Send");
	define("STR_PH_OPINION",				"Please feel free to send us any comments or suggestions on our homepage.");
	define("STR_SIGNUP_NUM",				"Users");
	define("STR_TODAY_VISIT",				"Visits");
	define("STR_PERSON_NUM",				"");
	define("STR_SIGNIN",					"Signin");
	define("STR_SIGNOUT",					"Signout");
	define("STR_FORGOT_PASSWD",				"Forget password");
	define("STR_FORGOT_PASSWD_EMAIL",		"Forget mail password");
	define("STR_FORGOT_PASSWD_QA",			"Forget mail password question");
	define("STR_SUBSCRIBE",					"Subscribe");
	define("STR_SUBSCRIBE_COMPLETE",		"Subscribe complete");
	define("STR_MYINFO",					"myinfo");
	define("STR_POINTS",					"points");
	define("STR_PASSWORD",					"password");
	define("STR_MY_MESSAGES",				"My Messages");
	define("STR_PH_SEARCH_STRING",			"Search...");
	define("STR_SEARCH",					"Search");
	define("STR_NEW_MESSAGE",				"New Message");
	define("STR_REACH_NUM",					"arrived");
	define("STR_NO_MESSAGE",				"No");
	define("STR_VIEW_ALL",					"View All");
	define("STR_ARTICLE_SORT",				"Sort");
	define("STR_ARTICLE_SORT_NEWEST",		"Newest");
	define("STR_ARTICLE_SORT_READ_COUNT",	"Read count");
	define("STR_ARTICLE_SORT_LIKE_COUNT",	"Like count");
	define("STR_ARTICLE_VOLUME",			"Vol");
	define("STR_ARTICLE_NUMBER",			"No");
	define("STR_ARTICLE_AUTHOR",			"Author");
	define("STR_ARTICLE_PUBLISHER",			"Publisher");
	define("STR_ARTICLE_PUBDATE",			"Publish Date");
	define("STR_ARTICLE_PUBYEAR",			"year");
	define("STR_ARTICLE_PAGE_COUNT",		"Page count");
	define("STR_ARTICLE_PAGE",				"Page");
	define("STR_ARTICLE_ISBN",				"ISBN");
	define("STR_ARTICLE_WRITEDATE",			"Write Date");
	define("STR_DELETE_SUCCESS",			"Delete Success");
	define("STR_DELETE_ERROR",				"Delete Error");
	define("STR_DELETE",					"Delete");
	define("STR_CONTENT",					"Content");
	define("STR_ADD_IMAGE",					"Add Image");
	define("STR_ATTACHE",					"Attache");
	define("STR_UPLOAD",					"Upload");
	define("STR_INTRO_IMAGE",				"Intro Image");
	define("STR_CANCEL",					"Cancel");
	define("STR_INPUT_SEARCH_PH",			"Search...");
	define("STR_COMPANY_NAME",				"Company Name");
	define("STR_SITE_NAME",					"Site Name");
	define("STR_DOMAIN_NAME",				"Domain Name");
	define("STR_TEL_NUM",					"Tel Num");
	define("STR_FSITE",						"Social");
	define("STR_INPUT_CONTENT",				"Please input comments.");
