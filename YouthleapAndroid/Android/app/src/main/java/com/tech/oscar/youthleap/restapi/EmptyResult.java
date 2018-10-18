package com.tech.oscar.youthleap.restapi;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class EmptyResult {
    @SerializedName("err_code") @Expose
    public int err_code;

    @SerializedName("err_msg") @Expose
    public String err_msg;
}
