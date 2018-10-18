package com.tech.oscar.youthleap.restapi.wallet;

import retrofit2.Call;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;

public interface WalletApi {
    // Login
    @FormUrlEncoded
    @POST("/api/wallet/get_walletlist")
    Call<GetWalletsResult> getWallets(@Field("user_id") int user_id, @Field("psort") int psort, @Field("page") int page, @Field("size") int size,
                                     @Field("user_token") String user_token);
}
