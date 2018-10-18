package com.tech.oscar.youthleap.model.base;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

import java.util.Date;

public class BasePurpose {
    @SerializedName("lookup_id") @Expose
    public int lookupId;

    @SerializedName("parent_id") @Expose
    public int parentId;

    @SerializedName("displayName") @Expose
    public String displayName;

    @SerializedName("depth") @Expose
    public int depth;
}
