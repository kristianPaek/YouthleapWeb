package com.tech.oscar.youthleap.util;

import android.content.Context;
import android.location.Address;
import android.location.Geocoder;
import android.location.LocationManager;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;

import com.tech.oscar.youthleap.YouthLeapApp;

import org.json.JSONArray;
import org.json.JSONObject;

import java.io.IOException;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;


public class DeviceUtil {
	/*
	 * network connection
	 */
	public static boolean isNetworkAvailable(Context context) {
		boolean isConnected = false;
		try{
			ConnectivityManager cm = (ConnectivityManager)context.getSystemService(Context.CONNECTIVITY_SERVICE);

			NetworkInfo activeNetwork = cm.getActiveNetworkInfo();
			isConnected = activeNetwork != null && activeNetwork.isConnectedOrConnecting();

		} catch(Exception e) {
			e.printStackTrace();  
			return false;
		}

		return isConnected;
	}

	// check wifi availble
	public static boolean isWifiAvailable() {
		ConnectivityManager connManager = (ConnectivityManager) YouthLeapApp.getContext().getSystemService(Context.CONNECTIVITY_SERVICE);
		NetworkInfo mWifi = connManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI);
		return mWifi.isConnected();
	}

	/*
	 * Location service
	 */
	public static boolean isLocationServiceAvailable(Context context) {
		LocationManager locationManager = (LocationManager) context.getSystemService(Context.LOCATION_SERVICE);

		// getting GPS status
		boolean isGPSEnabled = locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER);
		// getting network status
		boolean isNetworkEnabled = locationManager.isProviderEnabled(LocationManager.NETWORK_PROVIDER);

		return isGPSEnabled || isNetworkEnabled;
	}

	public static String getLocationName(double latitude, double longitude) {
		Geocoder geocoder = new Geocoder(YouthLeapApp.getContext(), Locale.getDefault());
		try {
			List<Address> addresses = geocoder.getFromLocation(latitude, longitude, 1);
			if (addresses != null && addresses.size() > 0) {
				Address address = addresses.get(0);
				if (address == null)
					return String.format(Locale.getDefault(), "*Lon:%.3f, Lat:%.3f", longitude, latitude);

				String country = address.getCountryName();
				if (country == null) {
					country = "";
				}
				String adminArea = address.getAdminArea();
				if (adminArea == null) {
					adminArea = "";
				}
				String locality = address.getLocality();
				if (locality == null) {
					locality = "";
				}
				String thoroghfare = address.getThoroughfare();
				if (thoroghfare == null) {
					thoroghfare = "";
				}
				String subthoroghfare = address.getSubThoroughfare();
				if (subthoroghfare == null) {
					subthoroghfare = "";
				}
				String area = address.getSubLocality();
				if (area == null) {
					area = "";
				}

				String locationName = subthoroghfare + " " + thoroghfare + " " + locality + " " + adminArea + " " + country;
				locationName = locationName.replace("  ", " ");
				locationName = locationName.replace("  ", " ");

				return locationName;

			} else {
				return String.format(Locale.getDefault(), "*Lon:%.3f, Lat:%.3f", longitude, latitude);
			}
		} catch (IOException e) {
			e.printStackTrace();
		}

		return "";
	}

	public static String getLocationNameByHTTP(double latitude, double longitude) {
		String url = String.format(Locale.getDefault(),
				"https://maps.googleapis.com/maps/api/geocode/json?latlng=%f,%f&key=AIzaSyBk70FaR1YJ2gIH5TpO2OMssQhSdhSA7fM",
				latitude, longitude);
		try {
			String response = HttpApi.call(url, HttpApi.METHOD.GET, new HashMap<String, String>());
			JSONObject object = new JSONObject(response);
			JSONArray resultArr = object.getJSONArray("results");
			if (resultArr != null && resultArr.length() > 0) {
				String address = resultArr.getJSONObject(0).getString("formatted_address");
				if (address != null)
					return address;
			}
			return String.format(Locale.getDefault(), "*Lon:%.3f, Lat:%.3f", longitude, latitude);

		} catch (Exception e) {
			e.printStackTrace();
		}

		return "";
	}
}
