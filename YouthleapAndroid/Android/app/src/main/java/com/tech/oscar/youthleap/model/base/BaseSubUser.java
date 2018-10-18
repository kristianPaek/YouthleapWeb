package com.tech.oscar.youthleap.model.base;

import android.text.TextUtils;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;
import com.tech.oscar.youthleap.AppConstant;
import com.tech.oscar.youthleap.restapi.Config;
import com.tech.oscar.youthleap.util.DateTimeUtils;

import java.util.Date;
import java.util.Locale;

public class BaseSubUser {
    @SerializedName("id") @Expose
    public int id = -1;

    @SerializedName("youthleapuser_id") @Expose
    public int youthleapuser_id = -1;

    @SerializedName("user_image") @Expose
    public String userImage;
    @SerializedName("user_thumb") @Expose
    public String userThumb;
    @SerializedName("user_fingerprint") @Expose
    public String userFingerPrint;

    @SerializedName("first_name") @Expose
    public String firstName;
    @SerializedName("middle_name") @Expose
    public String middleName;
    @SerializedName("last_name") @Expose
    public String lastName;

    @SerializedName("mobile_no") @Expose
    public String mobileNo;
    @SerializedName("email") @Expose
    public String email;
    @SerializedName("dob") @Expose
    public String birthday;
    @SerializedName("gender") @Expose
    public int gender = AppConstant.GENDER_MALE;

    @SerializedName("state") @Expose
    public String state;
    @SerializedName("city") @Expose
    public String city;
    @SerializedName("address") @Expose
    public String address;

    @SerializedName("is_active") @Expose
    public int isActive;

    public String getImage() {
        return String.format("%s%s", Config.BASE_URL, userImage);
    }

    public String getThumbImage() {
        return String.format("%s%s", Config.BASE_URL, userThumb);
    }

    public String getFullName() {
        return String.format(Locale.getDefault(), "%s %s %s", firstName, middleName, lastName);
    }

    public String getLocation() {
        return String.format(Locale.getDefault(), "%s, %s, %s", address, city, state);
    }

    public Date getBirthday() {
        if (TextUtils.isEmpty(birthday))
            return null;
        return DateTimeUtils.stringToDate(birthday, DateTimeUtils.DEFAULT_FORMAT);
    }

    public String getBirthdayStr() {
        Date birthDate = getBirthday();
        if (birthDate != null)
            return DateTimeUtils.dateToString(birthDate, DateTimeUtils.DEFAULT_FORMAT);
        return null;
    }

    public void setBirthday(Date date) {
        if (date != null) {
            birthday = DateTimeUtils.dateToString(date, DateTimeUtils.DEFAULT_FORMAT);
        }
    }
}
