package com.tech.oscar.youthleap.restapi.wallet;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;
import com.tech.oscar.youthleap.model.TutorModel;
import com.tech.oscar.youthleap.model.WalletModel;

import java.util.List;

public class GetWalletsResult {
    @SerializedName("err_code") @Expose
    public int err_code;

    @SerializedName("err_msg") @Expose
    public String err_msg;

    @SerializedName("wallets") @Expose
    public List<WalletModel> wallets;
}
