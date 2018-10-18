package com.tech.oscar.youthleap.util;

import android.util.Log;

import java.io.BufferedReader;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.HashMap;


public class HttpApi {
    public static class METHOD {
        public static final int GET = 0x1000;
        public static final int POST = 0x1001;
    }

    public static String call(String url, int method, HashMap<String, String> header) {
        if (method != METHOD.GET && method != METHOD.POST)
            return null;

        try {
            InputStream is = callToInputStream(url, method, header);

            // get response
            BufferedReader reader = new BufferedReader(new InputStreamReader(is, "UTF-8"), 8);
            StringBuilder sb = new StringBuilder();
            String line = null;
            while ((line = reader.readLine()) != null) {
                sb.append(line + "\n");
            }
            is.close();

            return sb.toString();

        } catch (Exception e) {
            e.printStackTrace();
        }

        return null;
    }

    public static InputStream callToInputStream(String strUrl, int method, HashMap<String, String> header) {
        InputStream is = null;

        try {
            URL url = new URL(strUrl);
            HttpURLConnection conn = (HttpURLConnection) url.openConnection();
            conn.setReadTimeout(10000 /* milliseconds */);
            conn.setConnectTimeout(15000 /* milliseconds */);
            if (method == METHOD.GET)
                conn.setRequestMethod("GET");
            else
                conn.setRequestMethod("POST");
            conn.setDoInput(true);

            // set header
            if (header != null && header.size() > 0) {
                for (String key: header.keySet()) {
                    conn.setRequestProperty(key, header.get(key));
                }
            }

            // Starts the query
            conn.connect();
            int responseCode = conn.getResponseCode();
            String responseString = conn.getResponseMessage();
            Log.e("Response", String.format("The response is: %d, %s", responseCode, responseString));
            
            if (responseCode != 200) {
            	is = conn.getErrorStream();
            } else {
            	is = conn.getInputStream();	
            }

            return is;

            // Makes sure that the InputStream is closed after the app is
            // finished using it.
        } catch (Exception e) {
            e.printStackTrace();
        }

        return is;
    }
}