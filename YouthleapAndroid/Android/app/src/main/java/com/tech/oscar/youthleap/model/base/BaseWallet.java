package com.tech.oscar.youthleap.model.base;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

import java.util.Date;

public class BaseWallet {
    @SerializedName("wallet_id") @Expose
    public int walletId;

    @SerializedName("user_id") @Expose
    public int userId;

    @SerializedName("points") @Expose
    public int points;

    @SerializedName("transaction_type_id") @Expose
    public int transactType;

    @SerializedName("transaction_date") @Expose
    public Date transactAt;

    @SerializedName("redeeptions_point") @Expose
    public int redeeptionPoint;
}
