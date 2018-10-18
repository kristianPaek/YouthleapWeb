package com.tech.oscar.youthleap.model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;
import com.tech.oscar.youthleap.model.base.BaseClass;
import com.tech.oscar.youthleap.model.base.BaseWallet;
import com.tech.oscar.youthleap.model.base.BaseFP05;
import com.tech.oscar.youthleap.model.base.BaseSubUser;

public class StudentModel {
    public BaseSubUser student;

    @SerializedName("class") @Expose
    public BaseClass mclass;

    @SerializedName("fp05") @Expose
    public BaseFP05 fp05;
}
