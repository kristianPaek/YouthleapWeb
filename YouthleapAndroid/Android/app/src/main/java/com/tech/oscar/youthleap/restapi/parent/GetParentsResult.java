package com.tech.oscar.youthleap.restapi.parent;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;
import com.tech.oscar.youthleap.model.ParentModel;
import com.tech.oscar.youthleap.model.TutorModel;

import java.util.List;

public class GetParentsResult {
    @SerializedName("err_code") @Expose
    public int err_code;

    @SerializedName("err_msg") @Expose
    public String err_msg;

    @SerializedName("parents") @Expose
    public List<ParentModel> parents;
}
