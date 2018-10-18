package com.tech.oscar.youthleap.model.base;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class BaseSchool {
    @SerializedName("ID") @Expose
    public int schoolId = -1;

    @SerializedName("SchoolName") @Expose
    public String schoolName;
}
