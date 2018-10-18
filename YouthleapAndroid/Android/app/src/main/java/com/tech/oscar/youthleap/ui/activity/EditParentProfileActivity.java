package com.tech.oscar.youthleap.ui.activity;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.DatePickerDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.drawable.BitmapDrawable;
import android.net.Uri;
import android.os.Bundle;
import android.provider.MediaStore;
import android.support.design.widget.TextInputLayout;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.DatePicker;
import android.widget.RadioGroup;
import android.widget.TextView;

import com.soundcloud.android.crop.Crop;
import com.tech.oscar.youthleap.AppConstant;
import com.tech.oscar.youthleap.AppGlobals;
import com.tech.oscar.youthleap.AppPreferences;
import com.tech.oscar.youthleap.R;
import com.tech.oscar.youthleap.model.UserModel;
import com.tech.oscar.youthleap.restapi.Config;
import com.tech.oscar.youthleap.restapi.EmptyResult;
import com.tech.oscar.youthleap.restapi.parent.ParentApi;
import com.tech.oscar.youthleap.restapi.user.GetProfileResult;
import com.tech.oscar.youthleap.restapi.user.UserApi;
import com.tech.oscar.youthleap.ui.view.MyAvatarImageView;
import com.tech.oscar.youthleap.util.CommonUtil;
import com.tech.oscar.youthleap.util.MessageUtil;
import com.tech.oscar.youthleap.util.ResourceUtil;

import java.io.File;
import java.util.Calendar;
import java.util.Date;

import okhttp3.MediaType;
import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class EditParentProfileActivity extends BaseActionBarActivity implements OnClickListener {
    public static EditParentProfileActivity instance;
    // UI
    MyAvatarImageView img_avatar;

    TextInputLayout til_first_name;
    TextInputLayout til_middle_name;
    TextInputLayout til_last_name;

    TextInputLayout til_birthday;
    RadioGroup radgrp_gender;

    TextInputLayout til_mobile;
    TextInputLayout til_email;

    TextInputLayout til_state;
    TextInputLayout til_city;
    TextInputLayout til_address;

    // Data
    public static UserModel mUser;

    private boolean mBHasPhoto = false;
    private Bitmap mOrgBmp = null;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        // TODO Auto-generated method stub
        super.onCreate(savedInstanceState);

        instance = this;
        setContentView(R.layout.activity_edit_parent_profile);

        initActionBar();
        if (mUser.getUserId() < 0) {
            setTitle(R.string.Add_parent);
        } else {
            setTitle(R.string.Edit_profile);
        }

        img_avatar = findViewById(R.id.img_avatar);
        final CheckBox chk_remeber = findViewById(R.id.chk_remeber);
        chk_remeber.setChecked(AppPreferences.getBool(AppPreferences.KEY.REMEMBER, false));
        chk_remeber.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                AppPreferences.setBool(AppPreferences.KEY.REMEMBER, isChecked);
            }
        });
        TextView btn_change_password = findViewById(R.id.btn_change_password);

        til_first_name = findViewById(R.id.til_first_name);
        til_middle_name = findViewById(R.id.til_middle_name);
        til_last_name = findViewById(R.id.til_last_name);

        til_birthday = findViewById(R.id.til_birthday);
        radgrp_gender = findViewById(R.id.radgrp_gender);

        til_mobile = findViewById(R.id.til_mobile);
        til_email = findViewById(R.id.til_email);

        til_state = findViewById(R.id.til_state);
        til_city = findViewById(R.id.til_city);
        til_address = findViewById(R.id.til_address);

        // listener
        img_avatar.setOnClickListener(this);
        findViewById(R.id.btn_change_password).setOnClickListener(this);
        til_birthday.setOnClickListener(this);
        findViewById(R.id.edt_birthday).setOnClickListener(this);
        findViewById(R.id.btn_save).setOnClickListener(this);

        // visible
        String loginUserEmail = AppPreferences.getStr(AppPreferences.KEY.SIGN_IN_USER_EMAIL, null);
        if (loginUserEmail != null && loginUserEmail.equals(mUser.subUser.email)) {
            chk_remeber.setVisibility(View.VISIBLE);
            btn_change_password.setVisibility(View.VISIBLE);
        } else {
            chk_remeber.setVisibility(View.GONE);
            btn_change_password.setVisibility(View.GONE);
        }

        // get data
        if (mUser.getUserId() > 0) {
            dlg_progress.show();
            (Config.retrofit.create(UserApi.class))
                    .getProfile(mUser.getUserId(), AppGlobals.userToken)
                    .enqueue(new OnGetProfileCallback());
        }
    }


    @Override
    public void onClick(View v) {
        // TODO Auto-generated method stub
        switch (v.getId()) {
            case R.id.img_avatar: {
                new AlertDialog.Builder(instance)
                        .setMessage(R.string.dialog_choose_photo)
                        .setPositiveButton(R.string.CAMERA, new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                Intent intent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
                                intent.putExtra(MediaStore.EXTRA_OUTPUT, Uri.fromFile(new File(ResourceUtil.getCameraFilePath())));
                                startActivityForResult(intent, AppConstant.REQ_CAMERA_CROP);
                            }
                        })
                        .setNegativeButton(R.string.GALLERY, new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                Crop.pickImage(instance);
                            }
                        })
                        .show();
            }
            break;

            case R.id.btn_change_password: {
                Intent intent = new Intent(instance, ChangePasswordActivity.class);
                ChangePasswordActivity.mUser = mUser;
                startActivity(intent);
            }
            break;

            case R.id.til_birthday:
            case R.id.edt_birthday: {
                CommonUtil.hideKeyboard(instance, til_birthday);
                showDatePicker();
            }
            break;

            case R.id.btn_save: {
                doSave();
            }
            break;
        }
    }

    @SuppressWarnings("deprecation")
    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent intent) {
        super.onActivityResult(requestCode, resultCode, intent);
        if (resultCode == Activity.RESULT_OK) {
            String avatarFileName = ResourceUtil.getAvatarFilePath();
            Uri outputUri = Uri.fromFile(new File(avatarFileName));

            if (requestCode == AppConstant.REQ_CAMERA_CROP) {
                String cameraFileName = ResourceUtil.getCameraFilePath();
                Uri inputUri = Uri.fromFile(new File(cameraFileName));
                Crop.of(inputUri, outputUri)
                        .withMaxSize(UserModel.AVATAR_SIZE, UserModel.AVATAR_SIZE)
                        .asSquare()
                        .start(instance);

            } else if (requestCode == Crop.REQUEST_PICK) {
                Crop.of(intent.getData(), outputUri)
                        .withMaxSize(UserModel.AVATAR_SIZE, UserModel.AVATAR_SIZE)
                        .asSquare()
                        .start(instance);

            } else if (requestCode == Crop.REQUEST_CROP) {
                // select gallery for picture
                Bitmap bm = BitmapFactory.decodeFile(avatarFileName);
                if (bm != null) {
                    if (mOrgBmp != null)
                        mOrgBmp.recycle();
                    mOrgBmp = bm;
                    img_avatar.setImageDrawable(new BitmapDrawable(mOrgBmp));
                    mBHasPhoto = true;
                } else {
                    Log.i(getString(R.string.app_name), "Bitmap is null");
                }
            }
        }
    }

    private void showProfile() {
        img_avatar.showImage(mUser.subUser.getImage());

        til_first_name.getEditText().setText(mUser.subUser.firstName);
        til_middle_name.getEditText().setText(mUser.subUser.middleName);
        til_last_name.getEditText().setText(mUser.subUser.lastName);

        til_birthday.getEditText().setText(mUser.subUser.getBirthdayStr());
        radgrp_gender.check(mUser.subUser.gender == AppConstant.GENDER_MALE ? R.id.rad_male : R.id.rad_female);

        til_mobile.getEditText().setText(mUser.subUser.mobileNo);
        til_email.getEditText().setText(mUser.user.email);

        til_state.getEditText().setText(mUser.subUser.state);
        til_city.getEditText().setText(mUser.subUser.city);
        til_address.getEditText().setText(mUser.subUser.address);
    }

    /**
     * Define Request Callback Listener
     */
    class OnGetProfileCallback implements Callback<GetProfileResult> {
        @Override
        public void onResponse(Call<GetProfileResult> call, Response<GetProfileResult> response) {
            dlg_progress.hide();
            GetProfileResult result = response.body();
            if (result != null && result.err_code == 0) {
                mUser.parse(result);
                showProfile();
            } else {
                MessageUtil.showToast(instance, result.err_msg, true);
            }
        }

        @Override
        public void onFailure(Call<GetProfileResult> call, Throwable t) {
            dlg_progress.hide();
            MessageUtil.showToast(instance, t.getMessage(), true);
        }
    }

    private boolean isValid() {
        String firstName = til_first_name.getEditText().getText().toString().trim();
        if (TextUtils.isEmpty(firstName)) {
            til_first_name.setError(getString(R.string.invalid_first_name_empty));
            til_first_name.requestFocus();
            return false;
        } else {
            til_first_name.setError(null);
        }
//        String middleName = til_middle_name.getEditText().getText().toString().trim();
//        if (TextUtils.isEmpty(middleName)) {
//            til_middle_name.setError(getString(R.string.invalid_middle_name_empty));
//            til_middle_name.requestFocus();
//            return false;
//        } else {
//            til_middle_name.setError(null);
//        }
        String lastName = til_last_name.getEditText().getText().toString().trim();
        if (TextUtils.isEmpty(lastName)) {
            til_last_name.setError(getString(R.string.invalid_last_name_empty));
            til_last_name.requestFocus();
            return false;
        } else {
            til_last_name.setError(null);
        }
        String mobile = til_mobile.getEditText().getText().toString().trim();
        if (TextUtils.isEmpty(mobile)) {
            til_mobile.setError(getString(R.string.invalid_mobile_empty));
            til_mobile.requestFocus();
            return false;
        } else {
            til_mobile.setError(null);
        }
        String email = til_email.getEditText().getText().toString().trim();
        if (TextUtils.isEmpty(mobile)) {
            til_email.setError(getString(R.string.invalid_email_empty));
            til_email.requestFocus();
            return false;
        } else {
            til_email.setError(null);
        }
        if (!CommonUtil.isValidEmail(email)) {
            til_email.setError(getString(R.string.invalid_email));
            til_email.requestFocus();
            return false;
        } else {
            til_email.setError(null);
        }
        String state = til_state.getEditText().getText().toString().trim();
        if (TextUtils.isEmpty(state)) {
            til_state.setError(getString(R.string.invalid_state_empty));
            til_state.requestFocus();
            return false;
        } else {
            til_state.setError(null);
        }
        String city = til_city.getEditText().getText().toString().trim();
        if (TextUtils.isEmpty(city)) {
            til_city.setError(getString(R.string.invalid_city_empty));
            til_city.requestFocus();
            return false;
        } else {
            til_city.setError(null);
        }
        String address = til_address.getEditText().getText().toString().trim();
        if (TextUtils.isEmpty(address)) {
            til_address.setError(getString(R.string.invalid_address_empty));
            til_address.requestFocus();
            return false;
        } else {
            til_address.setError(null);
        }
        return true;
    }

    private void showDatePicker() {
        final Calendar cal = Calendar.getInstance();

        Date currDate = new Date();
        if (mUser.subUser.getBirthday() != null)
            currDate = mUser.subUser.getBirthday();
        cal.setTime(currDate);

        int year = cal.get(Calendar.YEAR);
        int monthOfYear = cal.get(Calendar.MONTH);
        int dayOfMonth = cal.get(Calendar.DAY_OF_MONTH);

        new DatePickerDialog(instance, new DatePickerDialog.OnDateSetListener() {

            @Override
            public void onDateSet(DatePicker view, int year, int monthOfYear, int dayOfMonth) {
                cal.set(Calendar.YEAR, year);
                cal.set(Calendar.MONTH, monthOfYear);
                cal.set(Calendar.DAY_OF_MONTH, dayOfMonth);

                mUser.subUser.setBirthday(cal.getTime());
                til_birthday.getEditText().setText(mUser.subUser.getBirthdayStr());
            }
        }, year, monthOfYear, dayOfMonth).show();
    }

    private void doSave() {
        if (isValid()) {
            RequestBody id = RequestBody.create(MediaType.parse("text/plain"), mUser.getUserId()+"");
            RequestBody youthleapuser_id = RequestBody.create(MediaType.parse("text/plain"), mUser.subUser.youthleapuser_id+"");
            RequestBody first_name = RequestBody.create(MediaType.parse("text/plain"), til_first_name.getEditText().getText().toString().trim());
            RequestBody middle_name = RequestBody.create(MediaType.parse("text/plain"), til_middle_name.getEditText().getText().toString().trim());
            RequestBody last_name = RequestBody.create(MediaType.parse("text/plain"), til_last_name.getEditText().getText().toString().trim());
            RequestBody gender = RequestBody.create(MediaType.parse("text/plain"), radgrp_gender.getCheckedRadioButtonId() == R.id.rad_male ?
                    AppConstant.GENDER_MALE+"" : AppConstant.GENDER_FEMALE+"");
            RequestBody dob = RequestBody.create(MediaType.parse("text/plain"), mUser.subUser.getBirthdayStr());
            RequestBody mobile_no = RequestBody.create(MediaType.parse("text/plain"), til_mobile.getEditText().getText().toString().trim());
            RequestBody email = RequestBody.create(MediaType.parse("text/plain"), til_email.getEditText().getText().toString().trim());
            RequestBody state = RequestBody.create(MediaType.parse("text/plain"), til_state.getEditText().getText().toString().trim());
            RequestBody city = RequestBody.create(MediaType.parse("text/plain"), til_city.getEditText().getText().toString().trim());
            RequestBody address = RequestBody.create(MediaType.parse("text/plain"), til_address.getEditText().getText().toString().trim());

            MultipartBody.Part avatarImg = null;
            if (mBHasPhoto) {
                File file = new File(ResourceUtil.getAvatarFilePath());
                RequestBody reqFile = RequestBody.create(MediaType.parse("image/*"), file);
                avatarImg = MultipartBody.Part.createFormData("user_avatar", file.getName(), reqFile);
            }

            RequestBody token = RequestBody.create(MediaType.parse("text/plain"), AppGlobals.userToken);

            dlg_progress.show();
            (Config.retrofit.create(ParentApi.class))
                    .saveParent(id, youthleapuser_id, first_name, middle_name, last_name, gender, dob, mobile_no, email, state, city, address, avatarImg, token)
                    .enqueue(new Callback<EmptyResult>() {
                        @Override
                        public void onResponse(Call<EmptyResult> call, Response<EmptyResult> response) {
                            dlg_progress.hide();
                            EmptyResult result = response.body();
                            if (result != null && result.err_code == 0) {
                                MessageUtil.showToast(instance, R.string.Saved);
                                onBackPressed();

                                if (mUser.getUserId() > 0) {
                                    if (MainActivity.instance != null) {
                                        Intent broadcast = new Intent(AppConstant.ACTION_CHANGED_MY_PROFILE);
                                        MainActivity.instance.sendBroadcast(broadcast);
                                    }
                                }
                                else {
                                }
                            }
                            else {
                                MessageUtil.showToast(instance, response.message(), true);
                            }
                        }

                        @Override
                        public void onFailure(Call<EmptyResult> call, Throwable t) {
                            dlg_progress.hide();
                            MessageUtil.showToast(instance, t.getMessage(), true);
                        }
                    });
        }
    }
}
