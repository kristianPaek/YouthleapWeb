package com.tech.oscar.youthleap.model;

import android.text.TextUtils;

import com.tech.oscar.youthleap.model.base.BaseSchool;
import com.tech.oscar.youthleap.model.base.BaseSubUser;
import com.tech.oscar.youthleap.model.base.BaseUser;
import com.tech.oscar.youthleap.restapi.user.GetProfileResult;
import com.tech.oscar.youthleap.restapi.user.LoginResult;
import com.tech.oscar.youthleap.util.DateTimeUtils;

import java.util.Date;

public class UserModel {
    public static final int TYPE_ADMIN = 1;
    public static final int TYPE_SCHOOL = 1 << 1;
    public static final int TYPE_TUTOR = 1 << 2;
    public static final int TYPE_STUDENT = 1 << 3;
    public static final int TYPE_PARENT = 1 << 4;

    public static final int AVATAR_SIZE = 256;

    public BaseUser user = new BaseUser();
    public BaseSubUser subUser = new BaseSubUser();
    public BaseSchool school = new BaseSchool();

    public void parse(LoginResult result) {
        user = result.user;
        subUser = result.sub_user;
        school = result.school;
    }

    public void parse(GetProfileResult result) {
        user = result.user;
        subUser = result.sub_user;
        school = result.school;
    }

    public int getUserId() {
        return subUser.id;
    }

    public int getUserType() {
        return user.user_type;
    }

    public void setUserType(int userType) {
        user.user_type = userType;
    }

    public String getFullName() {
        if (getUserType() == TYPE_ADMIN)
            return subUser.getFullName();
        else if (getUserType() == TYPE_SCHOOL)
            return getSchoolName();
        else if (getUserType() == TYPE_TUTOR)
            return subUser.getFullName();
        else if (getUserType() == TYPE_STUDENT)
            return subUser.getFullName();
        else if (getUserType() == TYPE_PARENT)
            return subUser.getFullName();

        return "";
    }

    public String getSchoolName() {
        return school.schoolName;
    }
}
