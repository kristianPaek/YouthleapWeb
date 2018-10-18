package com.tech.oscar.youthleap.ui.activity;

import android.Manifest;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.os.Bundle;
import android.os.Handler;
import android.support.v4.app.ActivityCompat;
import android.text.TextUtils;
import android.webkit.WebView;
import android.widget.Toast;

import com.fgtit.fpcore.FPMatch;
import com.tech.oscar.youthleap.AppConstant;
import com.tech.oscar.youthleap.AppGlobals;
import com.tech.oscar.youthleap.AppPreferences;
import com.tech.oscar.youthleap.R;
import com.tech.oscar.youthleap.model.UserModel;
import com.tech.oscar.youthleap.restapi.Config;
import com.tech.oscar.youthleap.restapi.user.LoginResult;
import com.tech.oscar.youthleap.restapi.user.UserApi;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class SplashActivity extends BaseActivity {
    public static SplashActivity instance;
    public static final int REQUEST_PERMISSION = 0x111;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        instance = this;
        setContentView(R.layout.activity_splash);

        WebView webView = findViewById(R.id.webview);
        webView.setInitialScale(1);
        webView.getSettings().setLoadWithOverviewMode(true);
        webView.getSettings().setUseWideViewPort(true);
        webView.getSettings().setJavaScriptEnabled(true);
        webView.loadUrl("file:///android_res/raw/splash.html");

        if (FPMatch.getInstance().InitMatch() == 0){
            Toast.makeText(getApplicationContext(), "Init Matcher Fail!", Toast.LENGTH_SHORT).show();
        } else {
            //Toast.makeText(getApplicationContext(), "Init Matcher OK!", Toast.LENGTH_SHORT).show();
        }
    }

    @Override
    protected void onResume() {
        super.onResume();
        verifyPermissions();
    }

    private String[] PERMISSIONS = {
            Manifest.permission.READ_EXTERNAL_STORAGE,
            Manifest.permission.WRITE_EXTERNAL_STORAGE,
            Manifest.permission.CAMERA,
            Manifest.permission.INTERNET,
    };

    private void verifyPermissions() {
        int permission = ActivityCompat.checkSelfPermission(instance, Manifest.permission.WRITE_EXTERNAL_STORAGE);
        if (permission != PackageManager.PERMISSION_GRANTED) {
            ActivityCompat.requestPermissions(
                    instance,
                    PERMISSIONS,
                    REQUEST_PERMISSION
            );

        } else {
            doNextStep();
        }
    }

    private void doNextStep() {
        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {

                boolean remember = AppPreferences.getBool(AppPreferences.KEY.REMEMBER, false);
                if (remember) {
                    doLogin();

                } else {
                    gotoLoginPage();
                }

            }
        }, /*10000*/AppConstant.DELAY_SPLASH);
    }

    private void doLogin() {
        final String email = AppPreferences.getStr(AppPreferences.KEY.SIGN_IN_USER_EMAIL, null);
        final String password = AppPreferences.getStr(AppPreferences.KEY.SIGN_IN_PASSWORD, null);
        if (!TextUtils.isEmpty(email) && !TextUtils.isEmpty(password)) {
            dlg_progress.show();
            if (Config.retrofit == null)
                Config.init();

            (Config.retrofit.create(UserApi.class))
                    .doLogin(email, password)
                    .enqueue(new Callback<LoginResult>() {
                        @Override
                        public void onResponse(Call<LoginResult> call, Response<LoginResult> response) {
                            dlg_progress.hide();
                            LoginResult result = response.body();
                            if (result != null && result.err_code == 0) {
                                UserModel user = new UserModel();
                                user.parse(result);

                                // save data
                                AppGlobals.userToken = result.user_token;

                                finish();
                                Intent intent = new Intent(instance, MainActivity.class);
                                MainActivity.mUser = user;
                                startActivity(intent);
                            } else {
                                gotoLoginPage();
                            }
                        }

                        @Override
                        public void onFailure(Call<LoginResult> call, Throwable t) {
                            dlg_progress.hide();
                            gotoLoginPage();
                        }
                    });
        } else {
            gotoLoginPage();
        }
    }

    private void gotoLoginPage() {
        finish();
        Intent intent = new Intent(instance, LoginActivity.class);
        startActivity(intent);
    }
}
