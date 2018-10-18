package com.tech.oscar.youthleap.ui.fragment;

import android.annotation.SuppressLint;
import android.app.Dialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.drawable.Drawable;
import android.support.design.widget.BottomSheetDialogFragment;
import android.support.v7.app.AlertDialog;
import android.text.SpannableString;
import android.text.TextUtils;
import android.text.style.UnderlineSpan;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.tech.oscar.youthleap.AppConstant;
import com.tech.oscar.youthleap.AppGlobals;
import com.tech.oscar.youthleap.R;
import com.tech.oscar.youthleap.model.StudentModel;
import com.tech.oscar.youthleap.model.UserModel;
import com.tech.oscar.youthleap.restapi.Config;
import com.tech.oscar.youthleap.restapi.EmptyResult;
import com.tech.oscar.youthleap.restapi.student.StudentApi;
import com.tech.oscar.youthleap.ui.activity.BaseActionBarActivity;
import com.tech.oscar.youthleap.ui.activity.EditStudentProfileActivity;
import com.tech.oscar.youthleap.ui.view.MyAvatarImageView;
import com.tech.oscar.youthleap.ui.view.TagView;
import com.tech.oscar.youthleap.util.DateTimeUtils;
import com.tech.oscar.youthleap.util.MessageUtil;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;


public class MHStudentDetailFragment extends BottomSheetDialogFragment {
    public static MHStudentDetailFragment instance;
    // UI

    // Data
    BaseActionBarActivity mActivity;
    StudentModel mModel;

    public static MHStudentDetailFragment newInstance(BaseActionBarActivity activity, StudentModel model) {
        MHStudentDetailFragment fragment = new MHStudentDetailFragment();
        fragment.mActivity = activity;
        fragment.mModel = model;

        return fragment;
    }

    @SuppressLint("RestrictedApi")
    @Override
    public void setupDialog(final Dialog dialog, int style) {
        super.setupDialog(dialog, style);

        View rootView = View.inflate(mActivity, R.layout.fragment_dlg_mh_student, null);

        MyAvatarImageView img_avatar = rootView.findViewById(R.id.img_avatar);
        img_avatar.showImage(mModel.student.getImage());

        TextView txt_name = rootView.findViewById(R.id.txt_name);
        txt_name.setText(String.format(mModel.student.getFullName()));
        Drawable d = getResources().getDrawable(mModel.student.gender == AppConstant.GENDER_MALE ? R.drawable.gender_male : R.drawable.gender_female);
        txt_name.setCompoundDrawablesWithIntrinsicBounds(d,null, null,null);

        TextView txt_birthday = rootView.findViewById(R.id.txt_birthday);
        txt_birthday.setText(mModel.student.getBirthdayStr());
        TextView txt_mobile = rootView.findViewById(R.id.txt_mobile);
        txt_mobile.setText(mModel.student.mobileNo);

        TextView txt_email = rootView.findViewById(R.id.txt_email);
        SpannableString content = new SpannableString(mModel.student.email);
        content.setSpan(new UnderlineSpan(), 0, mModel.student.email.length(), 0);
        txt_email.setText(content);

        TextView txt_location = rootView.findViewById(R.id.txt_location);
        txt_location.setText(mModel.student.getLocation());

        TagView tag_class = rootView.findViewById(R.id.tag_class);
        if (!TextUtils.isEmpty(mModel.mclass.className)) {
            tag_class.setVisibility(View.VISIBLE);
            tag_class.clear();
            tag_class.setItemMaxLength(20);
            tag_class.setTextColor(getResources().getColor(android.R.color.black));
            tag_class.setTextSize(12);
            tag_class.setPaddingSize(32, 6, 32, 6);
            tag_class.setItemBackground(R.drawable.bg_round_white);
            tag_class.addItem(mModel.mclass.className, "");

        } else {
            tag_class.setVisibility(View.GONE);
        }

        ImageView img_edit = rootView.findViewById(R.id.img_edit);
        ImageView img_lock = rootView.findViewById(R.id.img_lock);
        ImageView img_delete = rootView.findViewById(R.id.img_delete);

        img_edit.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dismiss();

                Intent intent = new Intent(mActivity, EditStudentProfileActivity.class);
                EditStudentProfileActivity.mUser = new UserModel();
                EditStudentProfileActivity.mUser.subUser = mModel.student;
                mActivity.startActivity(intent);
            }
        });

        img_lock.setImageResource(mModel.student.isActive > 0 ? R.drawable.ic_action_unlock : R.drawable.ic_action_lock);
        img_lock.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                new AlertDialog.Builder(mActivity)
                        .setMessage(mModel.student.isActive > 0 ? R.string.dialog_deactive : R.string.dialog_active)
                        .setPositiveButton(R.string.OK, new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                mActivity.dlg_progress.show();
                                (Config.retrofit.create(StudentApi.class))
                                        .activeStudent(mModel.student.id, mModel.student.isActive > 0 ? 0 : 1, AppGlobals.userToken)
                                        .enqueue(new Callback<EmptyResult>() {
                                            @Override
                                            public void onResponse(Call<EmptyResult> call, Response<EmptyResult> response) {
                                                mActivity.dlg_progress.hide();
                                                EmptyResult result = response.body();
                                                if (result != null && result.err_code == 0) {
                                                    mModel.student.isActive = mModel.student.isActive > 0 ? 0 : 1;
                                                    dismiss();

                                                } else {
                                                    MessageUtil.showToast(mActivity, result.err_msg, true);
                                                }
                                            }

                                            @Override
                                            public void onFailure(Call<EmptyResult> call, Throwable t) {
                                                mActivity.dlg_progress.hide();
                                                MessageUtil.showToast(mActivity, t.getMessage(), true);
                                            }
                                        });
                            }
                        })
                        .setNegativeButton(R.string.Cancel, null)
                        .show();
            }
        });

        img_delete.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                new AlertDialog.Builder(mActivity)
                        .setMessage(R.string.dialog_remove)
                        .setPositiveButton(R.string.OK, new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                mActivity.dlg_progress.show();
                                (Config.retrofit.create(StudentApi.class))
                                        .removeStudent(mModel.student.id, AppGlobals.userToken)
                                        .enqueue(new Callback<EmptyResult>() {
                                            @Override
                                            public void onResponse(Call<EmptyResult> call, Response<EmptyResult> response) {
                                                mActivity.dlg_progress.hide();
                                                EmptyResult result = response.body();
                                                if (result != null && result.err_code == 0) {
                                                    dismiss();

                                                } else {
                                                    MessageUtil.showToast(mActivity, result.err_msg, true);
                                                }
                                            }

                                            @Override
                                            public void onFailure(Call<EmptyResult> call, Throwable t) {
                                                mActivity.dlg_progress.hide();
                                                MessageUtil.showToast(mActivity, t.getMessage(), true);
                                            }
                                        });
                            }
                        })
                        .setNegativeButton(R.string.Cancel, null)
                        .show();
            }
        });

        dialog.setContentView(rootView);
    }
}