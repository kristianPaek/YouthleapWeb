package com.tech.oscar.youthleap.ui.fragment;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v7.app.ActionBar;
import android.support.v7.widget.Toolbar;
import android.view.LayoutInflater;
import android.view.View;

import com.tech.oscar.youthleap.R;
import com.tech.oscar.youthleap.ui.activity.BaseActionBarActivity;

public abstract class BaseFragment extends Fragment {
	// UI
	protected View mView;
	protected LayoutInflater mInflater;
	
	@Override
	public void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
	}

	public void initActionBar(BaseActionBarActivity activity, Toolbar toolbar) {
		if (activity == null || toolbar == null)
			return;

		activity.setSupportActionBar(toolbar);
		toolbar.setTitleTextColor(getResources().getColor(android.R.color.white));

		ActionBar actionBar = activity.getSupportActionBar();
		if (actionBar == null)
			return;

		actionBar.setDisplayHomeAsUpEnabled(true);
		actionBar.setDisplayShowHomeEnabled(true);
		actionBar.setHomeAsUpIndicator(R.drawable.ic_action_menu_white);
	}
}