package com.tech.oscar.youthleap.util;

import android.util.Base64;

public class Base64Util {
	public static String fromByte(byte[] data) {
		byte[] encoded = Base64.encode(data, Base64.DEFAULT);
		return new String(encoded);
	}

	public static byte[] fromString(String data) {
		return Base64.decode(data.getBytes(), Base64.DEFAULT);
	}
}
