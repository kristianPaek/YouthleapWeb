package com.tech.oscar.youthleap.ui.activity;

import android.os.Bundle;
import android.view.View;

import com.tech.oscar.youthleap.R;

public class ForgetPasswordActivity extends BaseActivity implements
        View.OnClickListener {
    public static ForgetPasswordActivity instance;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        instance = this;
        setContentView(R.layout.activity_forget_password);

        findViewById(R.id.btn_reset).setOnClickListener(this);
        findViewById(R.id.btn_cancel).setOnClickListener(this);
    }

    @Override
    public void onClick(View v) {
        switch (v.getId()) {
            case R.id.btn_reset:
                break;
            case R.id.btn_cancel:
                onBackPressed();
                break;
        }
    }
}
