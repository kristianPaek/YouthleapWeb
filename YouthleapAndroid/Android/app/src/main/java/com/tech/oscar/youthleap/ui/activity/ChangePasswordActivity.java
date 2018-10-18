package com.tech.oscar.youthleap.ui.activity;

import android.os.Bundle;
import android.support.design.widget.TextInputLayout;
import android.text.TextUtils;
import android.view.View;

import com.tech.oscar.youthleap.AppGlobals;
import com.tech.oscar.youthleap.R;
import com.tech.oscar.youthleap.model.UserModel;
import com.tech.oscar.youthleap.restapi.Config;
import com.tech.oscar.youthleap.restapi.EmptyResult;
import com.tech.oscar.youthleap.restapi.user.UserApi;
import com.tech.oscar.youthleap.util.MessageUtil;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class ChangePasswordActivity extends BaseActionBarActivity implements
        View.OnClickListener {
    public static ChangePasswordActivity instance;

    // UI
    TextInputLayout til_old;
    TextInputLayout til_new;
    TextInputLayout til_confirm;

    // Data
    public static UserModel mUser;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        instance = this;
        setContentView(R.layout.activity_change_password);

        initActionBar();
        setTitle(R.string.Change_Password);

        til_old = findViewById(R.id.til_old);
        til_new = findViewById(R.id.til_new);
        til_confirm = findViewById(R.id.til_confirm);

        findViewById(R.id.btn_ok).setOnClickListener(this);
        findViewById(R.id.btn_cancel).setOnClickListener(this);
    }

    @Override
    public void onClick(View v) {
        switch (v.getId()) {
            case R.id.btn_ok:
                if (isValid()) {
                    changePassword();
                }
                break;
            case R.id.btn_cancel:
                onBackPressed();
                break;
        }
    }

    private boolean isValid() {
        String oldPassword = til_old.getEditText().getText().toString().trim();
        if (TextUtils.isEmpty(oldPassword)) {
            til_old.setError(getString(R.string.invalid_old_password_empty));
            til_old.requestFocus();
            return false;
        } else {
            til_old.setError(null);
        }
        String newPassword = til_new.getEditText().getText().toString().trim();
        if (TextUtils.isEmpty(newPassword)) {
            til_new.setError(getString(R.string.invalid_new_password_empty));
            til_new.requestFocus();
            return false;
        } else {
            til_new.setError(null);
        }
        String confirmPassword = til_confirm.getEditText().getText().toString().trim();
        if (!newPassword.equals(confirmPassword)) {
            til_confirm.setError(getString(R.string.invalid_password_not_match));
            til_confirm.requestFocus();
            return false;
        } else {
            til_confirm.setError(null);
        }
        return true;
    }

    private void changePassword() {
        dlg_progress.show();
        (Config.retrofit.create(UserApi.class))
                .changePassword(mUser.getUserId(), til_old.getEditText().getText().toString().trim(), til_new.getEditText().getText().toString().trim(), AppGlobals.userToken)
                .enqueue(new Callback<EmptyResult>() {
                    @Override
                    public void onResponse(Call<EmptyResult> call, Response<EmptyResult> response) {
                        dlg_progress.hide();
                        EmptyResult result = response.body();
                        if (result != null && result.err_code == 0) {
                            onBackPressed();
                            MessageUtil.showToast(instance, "Password changed!!", true);
                        } else {
                            MessageUtil.showToast(instance, result.err_msg, true);
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
