package com.tech.oscar.youthleap.restapi.student;

import com.tech.oscar.youthleap.restapi.EmptyResult;

import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.Multipart;
import retrofit2.http.POST;
import retrofit2.http.Part;

public interface StudentApi {
    // Login
    @FormUrlEncoded
    @POST("/api/student/get_students")
    Call<GetStudentResult> getStudents(@Field("class_id") int class_id, @Field("psort") int psort, @Field("page") int page, @Field("size") int size,
                                       @Field("user_token") String user_token);

    @Multipart
    @POST("/api/student/save")
    Call<EmptyResult> saveStudent(@Part("id") RequestBody id,
                                  @Part("youthleapuser_id") RequestBody youthleapuser_id,
                                  @Part("first_name") RequestBody first_name,
                                  @Part("middle_name") RequestBody middle_name,
                                  @Part("last_name") RequestBody last_name,
                                  @Part("gender") RequestBody gender,
                                  @Part("dob") RequestBody dob,
                                  @Part("mobile_no") RequestBody mobile_no,
                                  @Part("email") RequestBody email,
                                  @Part("state") RequestBody state,
                                  @Part("city") RequestBody city,
                                  @Part("address") RequestBody address,
                                  @Part MultipartBody.Part image,
                                  @Part("user_token") RequestBody user_token);

    @FormUrlEncoded
    @POST("/api/student/active")
    Call<EmptyResult> activeStudent(@Field("student_id") int student_id, @Field("is_active") int is_active, @Field("user_token") String user_token);

    @FormUrlEncoded
    @POST("/api/student/remove")
    Call<EmptyResult> removeStudent(@Field("student_id") int student_id, @Field("user_token") String user_token);
}
