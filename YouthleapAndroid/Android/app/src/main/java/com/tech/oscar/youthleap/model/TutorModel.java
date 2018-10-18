package com.tech.oscar.youthleap.model;

import com.tech.oscar.youthleap.model.base.BaseClass;
import com.tech.oscar.youthleap.model.base.BaseSubUser;
import com.tech.oscar.youthleap.model.base.BaseSubject;

import java.util.List;

public class TutorModel {
    public BaseSubUser tutor;
    public List<BaseClass> classes;
    public List<BaseSubject> subjects;
}
