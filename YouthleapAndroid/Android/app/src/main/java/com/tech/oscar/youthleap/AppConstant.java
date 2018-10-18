package com.tech.oscar.youthleap;


public class AppConstant {
	/*
	 * Fragments
	 */
	public static final int SW_FRAGMENT_MAIN_HOME = 0x011;
	public static final int SW_FRAGMENT_MAIN_WALLET = 0x012;
	public static final int SW_FRAGMENT_MAIN_VIDEO = 0x013;
	public static final int SW_FRAGMENT_MAIN_ONLINE_STORE = 0x014;
	public static final int SW_FRAGMENT_MAIN_MOOD = 0x015;
    public static final int SW_FRAGMENT_MAIN_ATTENDENCE = 0x016;

	/*
	Gender
	 */
	public static final int GENDER_MALE = 0;
	public static final int GENDER_FEMALE = 1;

	/*
	 * Request code
	 */
	public static final int REQ_CAMERA_CROP = 10001;

	/*
	 * Extra keys
	 */
	public static final String EK_EMAIL = "email";
	public static final String EK_VIDEO_PATH = "EK_VIDEO_PATH";
	public static final String EK_AUDIO_PATH = "EK_AUDIO_PATH";
	public static final String EK_URL = "EK_URL";
	public static final String EK_TYPE = "EK_TYPE";


	/*
	 * Camera
	 */
	public static final int CAPTURE_PHOTO = 1;
	public static final int CAPTURE_VIDEO = 2;
	public static final int CAPTURE_VIDEO_START = 3;
	public static final int CAPTURE_VIDEO_STOP = 4;

	/*
	 * Audio / Video length
	 */
	public static int MEDIA_MAX_LENGTH = 15000; // 15 seconds
	public static int MEDIA_MAX_SIZE = 10*1024*1024; // 10 Mb

	/*
	 * Video
	 */
	public static final int MIN_DURATION_MS = 1000 * 6;
	public static final int MAX_DURATION_MS = 1000 * 15;
	public static final int FRAME_COUNT = 10;

	/*
	 * Download
	 */
	public static final String APP_FILE_EXTENSION = ".isf";

	/*
	 * Broadcast action id
	 */
	// profile
	public static final String ACTION_CHANGED_MY_PROFILE = "action.changed.my.profile";
	// post

	// event
	public static final String ACTION_ADDED_EVENT = "action.added.event";
	public static final String ACTION_CHANGED_INTESTING_EVENT = "action.changed.interesting.event";

	// loading
	public static final int DELAY_SPLASH = 8500;
	public static final int DELAY_EXIT = 2000;
	public static final int DELAY_LOADING_MORE = 1000;
}
