package com.tech.oscar.youthleap.model.base;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class BaseStoreCategory {
    @SerializedName("id") @Expose
    public int id;

    @SerializedName("category_name") @Expose
    public String name;

    @SerializedName("create_time") @Expose
    public String createdAt;
}
