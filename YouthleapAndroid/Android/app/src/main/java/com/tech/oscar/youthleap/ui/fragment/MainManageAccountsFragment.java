package com.tech.oscar.youthleap.ui.fragment;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.tech.oscar.youthleap.R;
import com.tech.oscar.youthleap.ui.activity.BaseActionBarActivity;
import com.tech.oscar.youthleap.ui.activity.MaMaParentsActivity;
import com.tech.oscar.youthleap.ui.activity.MaMaStudentsActivity;
import com.tech.oscar.youthleap.ui.activity.MaMaTutorsActivity;
import com.tech.oscar.youthleap.ui.activity.MainActivity;
import com.tech.oscar.youthleap.util.MessageUtil;

public class MainManageAccountsFragment extends BaseFragment implements
        View.OnClickListener {
    public static MainManageAccountsFragment instance;
    // UI
    // Data
    BaseActionBarActivity mActivity;

    public static MainManageAccountsFragment newInstance() {
        MainManageAccountsFragment fragment = new MainManageAccountsFragment();

        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        instance = this;
        mActivity = MainActivity.instance;
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        mView = inflater.inflate(R.layout.fragment_main_manage_accounts, null);

        Toolbar toolbar = mView.findViewById(R.id.toolbar);
        initActionBar(mActivity, toolbar);
        mActivity.setTitle(R.string.menu_manage_account);

        mView.findViewById(R.id.btn_tutor).setOnClickListener(this);
        mView.findViewById(R.id.btn_student).setOnClickListener(this);
        mView.findViewById(R.id.btn_parent).setOnClickListener(this);
        mView.findViewById(R.id.btn_attendance).setOnClickListener(this);

        return mView;
    }

    @Override
    public void onClick(View v) {
        switch (v.getId()) {
            case R.id.btn_tutor: {
                Intent intent = new Intent(mActivity, MaMaTutorsActivity.class);
                mActivity.startActivity(intent);
            }
            break;

            case R.id.btn_student: {
                Intent intent = new Intent(mActivity, MaMaStudentsActivity.class);
                mActivity.startActivity(intent);
            }
            break;

            case R.id.btn_parent: {
                Intent intent = new Intent(mActivity, MaMaParentsActivity.class);
                mActivity.startActivity(intent);
            }
            break;

            case R.id.btn_attendance:
                MessageUtil.showToast(mActivity, "Coming soon...");
                break;
        }
    }
}

