package com.tech.oscar.youthleap.model.base;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class BaseSubject {
    @SerializedName("subject_id") @Expose
    public int subjectId = -1;

    @SerializedName("subject_name") @Expose
    public String subjectName;
}
