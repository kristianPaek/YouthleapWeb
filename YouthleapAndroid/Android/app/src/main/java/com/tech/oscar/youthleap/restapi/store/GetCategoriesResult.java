package com.tech.oscar.youthleap.restapi.store;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;
import com.tech.oscar.youthleap.model.base.BaseStoreCategory;

import java.util.List;

public class GetCategoriesResult {
    @SerializedName("err_code") @Expose
    public int err_code;

    @SerializedName("err_msg") @Expose
    public String err_msg;

    @SerializedName("categories") @Expose
    public List<BaseStoreCategory> categories;
}
