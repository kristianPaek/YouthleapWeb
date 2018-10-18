package com.tech.oscar.youthleap.restapi.user;

import com.tech.oscar.youthleap.restapi.EmptyResult;

import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.Multipart;
import retrofit2.http.POST;
import retrofit2.http.Part;

public interface UserApi {
    @FormUrlEncoded
    @POST("/api/user/login")
    Call<LoginResult> doLogin(@Field("email") String email, @Field("password") String password);

    @FormUrlEncoded
    @POST("/api/user/get_profile")
    Call<GetProfileResult> getProfile(@Field("user_id") int user_id, @Field("user_token") String user_token);

    @FormUrlEncoded
    @POST("/api/profile/password")
    Call<EmptyResult> changePassword(@Field("user_id") int user_id, @Field("old_password") String old_password,
                                     @Field("new_password") String new_password, @Field("user_token") String user_token);

    @Multipart
    @POST("/api/user/save_finger_fp05")
    Call<EmptyResult> saveFingerPrint(@Part("user_id") RequestBody user_id, @Part("user_token") RequestBody user_token,
                                      @Part MultipartBody.Part image, @Part("finger_data") RequestBody finger_data);

    @Multipart
    @POST("/api/profile/save")
    Call<EmptyResult> saveSchool(@Part("id") RequestBody id,
                                 @Part("youthleapuser_id") RequestBody youthleapuser_id,
                                 @Part("school_name") RequestBody school_name,
                                 @Part("gender") RequestBody gender,
                                 @Part("dob") RequestBody dob,
                                 @Part("mobile_no") RequestBody mobile_no,
                                 @Part("email") RequestBody email,
                                 @Part("state") RequestBody state,
                                 @Part("city") RequestBody city,
                                 @Part("address") RequestBody address,
                                 @Part MultipartBody.Part image,
                                 @Part("user_token") RequestBody user_token);
}
