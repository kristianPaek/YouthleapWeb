package com.tech.oscar.youthleap.restapi.student;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;
import com.tech.oscar.youthleap.model.StudentModel;
import com.tech.oscar.youthleap.model.TutorModel;

import java.util.List;

public class GetStudentResult {
    @SerializedName("err_code") @Expose
    public int err_code;

    @SerializedName("err_msg") @Expose
    public String err_msg;

    @SerializedName("students") @Expose
    public List<StudentModel> students;
}
