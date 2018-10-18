package com.tech.oscar.youthleap.util;

import android.app.ActivityManager;
import android.content.Context;
import android.content.Intent;
import android.content.res.Configuration;
import android.content.res.Resources;
import android.content.res.TypedArray;
import android.net.Uri;
import android.text.TextUtils;
import android.util.DisplayMetrics;
import android.view.View;
import android.view.inputmethod.InputMethodManager;

import com.tech.oscar.youthleap.R;
import com.tech.oscar.youthleap.YouthLeapApp;

import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

public class CommonUtil {
	/*
	 * Hide keyboard
	 */
	public static void hideKeyboard(Context context, View view) {
		InputMethodManager imm = (InputMethodManager) context.getSystemService(Context.INPUT_METHOD_SERVICE);
		imm.hideSoftInputFromWindow(view.getWindowToken(), 0);
	}

	/*
	 * Package name
	 */
	public static String getRunningAppPkg() {
		try {
			ActivityManager am = (ActivityManager) YouthLeapApp.getContext().getSystemService(Context.ACTIVITY_SERVICE);
			List<ActivityManager.RunningAppProcessInfo> procs = am.getRunningAppProcesses();
			if(procs != null && procs.size() > 0) {
				String foregroundTaskPackageName = procs.get(0).processName;
				return foregroundTaskPackageName;
			}
			return "";

		}
		catch (Exception e) {
			e.printStackTrace();
		}
		return "";
	}

	/*
	 * Mail
	 */
	// check validation
	public static boolean isValidEmail(String emailAddr) {
		if (!emailAddr.contains("@"))
			return false;
		if (!emailAddr.contains("."))
			return false;
		if (emailAddr.startsWith("@") || emailAddr.endsWith("@"))
			return false;
		if (emailAddr.startsWith(".") || emailAddr.endsWith("."))
			return false;

		return true;
	}

	/*
	 * Launch market
	 */
	public static void launchMarket() {
		Uri uri = Uri.parse("market://details?id=" + YouthLeapApp.getContext().getPackageName());
		Intent goToMarket = new Intent(Intent.ACTION_VIEW, uri);
		try {
			YouthLeapApp.getContext().startActivity(goToMarket);

		} catch (Exception e) {
			// open with browser
			Intent browserIntent = new Intent(Intent.ACTION_VIEW,
					Uri.parse("https://play.google.com/store/apps/details?id=" + YouthLeapApp.getContext().getPackageName()));
			browserIntent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
			YouthLeapApp.getContext().startActivity(browserIntent);
		}
	}

	// send email
	public static void SendEmail(Context context, ArrayList<String> to, String subject, String body, String attachment_url) {
		Intent intent = new Intent(Intent.ACTION_SEND);

		if (TextUtils.isEmpty(attachment_url)) {
			intent.setType("message/rfc822");

		} else {
			intent.setType("vnd.android.cursor.dir/email");
			intent.putExtra(Intent.EXTRA_STREAM, Uri.parse("file://" + attachment_url));
		}

		intent.putExtra(Intent.EXTRA_SUBJECT, subject);
		intent.putExtra(Intent.EXTRA_TEXT, body);
		if (to != null && to.size() > 0) {
			String[] toArr = to.toArray(new String[to.size()]);
			intent.putExtra(Intent.EXTRA_EMAIL, toArr);
		}

		context.startActivity(Intent.createChooser(intent, "Send Information"));
	}
	
	/*
	 * find index
	 */
	public static int findIndex(String [] strArr, String value) {
		if (strArr == null || strArr.length == 0)
			return -1;
		
		for (int i = 0; i < strArr.length; i++)
			if (strArr[i].equals(value))
				return i;
		
		return -1;
	}

    /*
     * mutil language
     */
    public static void changeLanguage(int index) {
        Resources res = YouthLeapApp.getContext().getResources();
        DisplayMetrics dm = res.getDisplayMetrics();
        Configuration conf = res.getConfiguration();
        switch (index) {
            case 0:
                conf.locale = Locale.US;
                break;
            case 1:
                conf.locale = Locale.CHINA;
                break;
            case 2:
                conf.locale = new Locale("es");
                break;
            case 3:
                conf.locale = new Locale("fa");
                break;
            case 4:
                conf.locale = new Locale("ru");
                break;
            case 5:
                conf.locale = new Locale("ar");
                break;
            default:
                conf.locale = new Locale("en");
                break;
        }
        res.updateConfiguration(conf, dm);
    }

    // UI
	public static int getToolbarHeight(Context context) {
		final TypedArray styledAttributes = context.getTheme().obtainStyledAttributes(
				new int[]{R.attr.actionBarSize});
		int toolbarHeight = (int) styledAttributes.getDimension(0, 0);
		styledAttributes.recycle();

		return toolbarHeight;
	}

	public static int getTabsHeight(Context context) {
		return (int) context.getResources().getDimension(R.dimen.tabsHeight);
	}
}
