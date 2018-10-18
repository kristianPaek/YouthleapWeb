package com.tech.oscar.youthleap.restapi.user;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;
import com.tech.oscar.youthleap.model.base.BaseSchool;
import com.tech.oscar.youthleap.model.base.BaseSubUser;
import com.tech.oscar.youthleap.model.base.BaseUser;

public class LoginResult {
    @SerializedName("err_code") @Expose
    public int err_code;

    @SerializedName("err_msg") @Expose
    public String err_msg;

    @SerializedName("user") @Expose
    public BaseUser user;

    @SerializedName("school") @Expose
    public BaseSchool school;

    @SerializedName("sub_user") @Expose
    public BaseSubUser sub_user;

    @SerializedName("user_token") @Expose
    public String user_token;
}
