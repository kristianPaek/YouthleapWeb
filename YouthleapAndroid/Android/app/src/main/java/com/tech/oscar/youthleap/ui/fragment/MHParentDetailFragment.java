package com.tech.oscar.youthleap.ui.fragment;

import android.annotation.SuppressLint;
import android.app.Dialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.drawable.Drawable;
import android.support.design.widget.BottomSheetDialogFragment;
import android.support.v7.app.AlertDialog;
import android.text.SpannableString;
import android.text.style.UnderlineSpan;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.tech.oscar.youthleap.AppConstant;
import com.tech.oscar.youthleap.AppGlobals;
import com.tech.oscar.youthleap.R;
import com.tech.oscar.youthleap.model.ParentModel;
import com.tech.oscar.youthleap.model.UserModel;
import com.tech.oscar.youthleap.restapi.Config;
import com.tech.oscar.youthleap.restapi.EmptyResult;
import com.tech.oscar.youthleap.restapi.parent.ParentApi;
import com.tech.oscar.youthleap.ui.activity.BaseActionBarActivity;
import com.tech.oscar.youthleap.ui.activity.EditParentProfileActivity;
import com.tech.oscar.youthleap.ui.view.MyAvatarImageView;
import com.tech.oscar.youthleap.util.DateTimeUtils;
import com.tech.oscar.youthleap.util.MessageUtil;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;


public class MHParentDetailFragment extends BottomSheetDialogFragment {
    public static MHParentDetailFragment instance;
    // UI

    // Data
    BaseActionBarActivity mActivity;
    ParentModel mModel;

    public static MHParentDetailFragment newInstance(BaseActionBarActivity activity, ParentModel model) {
        MHParentDetailFragment fragment = new MHParentDetailFragment();
        fragment.mActivity = activity;
        fragment.mModel = model;

        return fragment;
    }

    @SuppressLint("RestrictedApi")
    @Override
    public void setupDialog(final Dialog dialog, int style) {
        super.setupDialog(dialog, style);

        View rootView = View.inflate(mActivity, R.layout.fragment_dlg_mh_parent, null);

        MyAvatarImageView img_avatar = rootView.findViewById(R.id.img_avatar);
        img_avatar.showImage(mModel.parent.getImage());

        TextView txt_name = rootView.findViewById(R.id.txt_name);
        txt_name.setText(mModel.parent.getFullName());
        Drawable d = getResources().getDrawable(mModel.parent.gender == AppConstant.GENDER_MALE ? R.drawable.gender_male : R.drawable.gender_female);
        txt_name.setCompoundDrawablesWithIntrinsicBounds(d,null, null,null);

        TextView txt_birthday = rootView.findViewById(R.id.txt_birthday);
        txt_birthday.setText(mModel.parent.getBirthdayStr());
        TextView txt_mobile = rootView.findViewById(R.id.txt_mobile);
        txt_mobile.setText(mModel.parent.mobileNo);

        TextView txt_email = rootView.findViewById(R.id.txt_email);
        SpannableString content = new SpannableString(mModel.parent.email);
        content.setSpan(new UnderlineSpan(), 0, mModel.parent.email.length(), 0);
        txt_email.setText(content);

        TextView txt_location = rootView.findViewById(R.id.txt_location);
        txt_location.setText(mModel.parent.getLocation());

        ImageView img_edit = rootView.findViewById(R.id.img_edit);
        ImageView img_lock = rootView.findViewById(R.id.img_lock);
        ImageView img_delete = rootView.findViewById(R.id.img_delete);

        img_edit.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dismiss();

                Intent intent = new Intent(mActivity, EditParentProfileActivity.class);
                EditParentProfileActivity.mUser = new UserModel();
                EditParentProfileActivity.mUser.subUser = mModel.parent;
                mActivity.startActivity(intent);
            }
        });

        img_lock.setImageResource(mModel.parent.isActive > 0 ? R.drawable.ic_action_unlock : R.drawable.ic_action_lock);
        img_lock.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                new AlertDialog.Builder(mActivity)
                        .setMessage(mModel.parent.isActive > 0 ? R.string.dialog_deactive : R.string.dialog_active)
                        .setPositiveButton(R.string.OK, new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                mActivity.dlg_progress.show();
                                (Config.retrofit.create(ParentApi.class))
                                        .activeParent(mModel.parent.id, mModel.parent.isActive > 0 ? 0 : 1, AppGlobals.userToken)
                                        .enqueue(new Callback<EmptyResult>() {
                                            @Override
                                            public void onResponse(Call<EmptyResult> call, Response<EmptyResult> response) {
                                                mActivity.dlg_progress.hide();
                                                EmptyResult result = response.body();
                                                if (result != null && result.err_code == 0) {
                                                    mModel.parent.isActive = mModel.parent.isActive > 0 ? 0 : 1;
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
                                (Config.retrofit.create(ParentApi.class))
                                        .removeParent(mModel.parent.id, AppGlobals.userToken)
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