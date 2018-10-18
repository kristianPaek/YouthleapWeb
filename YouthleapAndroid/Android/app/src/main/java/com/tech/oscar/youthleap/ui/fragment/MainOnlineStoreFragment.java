package com.tech.oscar.youthleap.ui.fragment;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.tech.oscar.youthleap.R;
import com.tech.oscar.youthleap.ui.activity.BaseActionBarActivity;
import com.tech.oscar.youthleap.ui.activity.MaOnProductActivity;
import com.tech.oscar.youthleap.ui.activity.MaOnProductCategoryActivity;
import com.tech.oscar.youthleap.ui.activity.MainActivity;

public class MainOnlineStoreFragment extends BaseFragment implements
        View.OnClickListener {
    public static MainOnlineStoreFragment instance;
    // UI
    // Data
    BaseActionBarActivity mActivity;

    public static MainOnlineStoreFragment newInstance() {
        MainOnlineStoreFragment fragment = new MainOnlineStoreFragment();

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
        mView = inflater.inflate(R.layout.fragment_main_online_store, null);

        Toolbar toolbar = mView.findViewById(R.id.toolbar);
        initActionBar(mActivity, toolbar);
        mActivity.setTitle(R.string.menu_online_store);

        mView.findViewById(R.id.btn_category).setOnClickListener(this);
        mView.findViewById(R.id.btn_product).setOnClickListener(this);

        return mView;
    }

    @Override
    public void onClick(View v) {
        switch (v.getId()) {
            case R.id.btn_category: {
                Intent intent = new Intent(mActivity, MaOnProductCategoryActivity.class);
                mActivity.startActivity(intent);
            }
            break;

            case R.id.btn_product: {
                Intent intent = new Intent(mActivity, MaOnProductActivity.class);
                mActivity.startActivity(intent);
            }
            break;
        }
    }
}

