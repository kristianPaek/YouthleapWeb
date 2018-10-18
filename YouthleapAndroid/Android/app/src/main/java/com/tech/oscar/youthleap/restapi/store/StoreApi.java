package com.tech.oscar.youthleap.restapi.store;

import com.tech.oscar.youthleap.restapi.EmptyResult;

import retrofit2.Call;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;

public interface StoreApi {
    // Login
    @FormUrlEncoded
    @POST("/api/store/get_categories")
    Call<GetCategoriesResult> getCategories(@Field("psort") int psort, @Field("page") int page, @Field("size") int size,
                                            @Field("user_token") String user_token);

    @FormUrlEncoded
    @POST("/api/store/category_save")
    Call<EmptyResult> saveCategory(@Field("id") String id, @Field("category_name") String category_name,
                                   @Field("user_token") String user_token);

    @FormUrlEncoded
    @POST("/api/store/category_remove")
    Call<EmptyResult> removeCategory(@Field("id") String id,
                                   @Field("user_token") String user_token);
}
