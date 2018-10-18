package com.tech.oscar.youthleap.model.base;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class BaseClass {
    @SerializedName("class_id") @Expose
    public String classId;

    @SerializedName("class_name") @Expose
    public String className;
}
