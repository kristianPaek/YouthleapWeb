package com.tech.oscar.youthleap.ui.activity;

import android.content.Intent;
import android.os.Bundle;
import android.support.design.widget.TextInputLayout;
import android.text.TextUtils;
import android.view.View;

import com.tech.oscar.youthleap.AppGlobals;
import com.tech.oscar.youthleap.AppPreferences;
import com.tech.oscar.youthleap.R;
import com.tech.oscar.youthleap.model.UserModel;
import com.tech.oscar.youthleap.restapi.Config;
import com.tech.oscar.youthleap.restapi.user.LoginResult;
import com.tech.oscar.youthleap.restapi.user.UserApi;
import com.tech.oscar.youthleap.util.CommonUtil;
import com.tech.oscar.youthleap.util.MessageUtil;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class LoginActivity extends BaseActivity implements View.OnClickListener {
    public static LoginActivity instance;

    // UI
    TextInputLayout til_userid;
    TextInputLayout til_password;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        instance = this;
        setContentView(R.layout.activity_login);

        til_userid = findViewById(R.id.til_userid);
        til_password = findViewById(R.id.til_password);

        findViewById(R.id.btn_forgot_password).setOnClickListener(this);
        findViewById(R.id.btn_login).setOnClickListener(this);
    }

    @Override
    public void onClick(View v) {
        switch (v.getId()) {
            case R.id.btn_forgot_password: {
                Intent intent = new Intent(instance, ForgetPasswordActivity.class);
                startActivity(intent);
            }
            break;

            case R.id.btn_login: {
                if (isValid()) {
                    dlg_progress.show();

                    if (Config.retrofit == null) {
                        Config.init();
                    }

                    final String password = til_password.getEditText().getText().toString().trim();
                    (Config.retrofit.create(UserApi.class))
                            .doLogin(til_userid.getEditText().getText().toString().trim(), password)
                            .enqueue(new Callback<LoginResult>() {
                                @Override
                                public void onResponse(Call<LoginResult> call, Response<LoginResult> response) {
                                    dlg_progress.hide();
                                    LoginResult result = response.body();
                                    if (result != null && result.err_code == 0) {
                                        finish();

                                        UserModel user = new UserModel();
                                        user.parse(result);

                                        // save user token
                                        AppPreferences.setStr(AppPreferences.KEY.SIGN_IN_USER_EMAIL, user.subUser.email);
                                        AppPreferences.setStr(AppPreferences.KEY.SIGN_IN_PASSWORD, password);
                                        AppGlobals.userToken = result.user_token;

                                        Intent intent = new Intent(instance, MainActivity.class);
                                        MainActivity.mUser = user;
                                        startActivity(intent);
                                    } else {
                                        MessageUtil.showToast(instance, result.err_msg, true);
                                    }
                                }

                                @Override
                                public void onFailure(Call<LoginResult> call, Throwable t) {
                                    dlg_progress.hide();
                                    MessageUtil.showToast(instance, t.getMessage(), true);
                                }
                            });
                }
            }
            break;
        }
    }

    private boolean isValid() {
        String userid = til_userid.getEditText().getText().toString().trim();
        if (TextUtils.isEmpty(userid)) {
            til_userid.setError(getString(R.string.invalid_userid_empty));
            til_userid.requestFocus();
            return false;
        } else {
            til_userid.setError(null);
        }
        if (!CommonUtil.isValidEmail(userid)) {
            til_userid.setError(getString(R.string.invalid_email));
            til_userid.requestFocus();
            return false;
        } else {
            til_userid.setError(null);
        }
        String password = til_password.getEditText().getText().toString().trim();
        if (TextUtils.isEmpty(password)) {
            til_password.setError(getString(R.string.invalid_password_empty));
            til_password.requestFocus();
            return false;
        } else {
            til_password.setError(null);
        }
        return true;
    }

    /**
     * Define Request Callback Listener
     */
    class OnLoginCallback implements Callback<LoginResult> {
        @Override
        public void onResponse(Call<LoginResult> call, Response<LoginResult> response) {

        }

        @Override
        public void onFailure(Call<LoginResult> call, Throwable t) {

        }
    }
}
