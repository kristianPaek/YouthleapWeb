package com.android.widget.slidingtab;

import java.util.ArrayList;

import android.app.Fragment;
import android.app.FragmentManager;
import android.content.Context;
import android.os.Bundle;

public class FragmentSlidingTabAdapter extends FragmentPagerAdapter {

    private Context mContext;
    private final ArrayList<TabInfo> mTabs = new ArrayList<TabInfo>();

    static final class TabInfo {
        private final String tag;
        private final String title;
        private final Class<?> clss;
        private final Bundle args;
        private Fragment fragment;

        TabInfo(String _tag, String _title, Class<?> _class, Bundle _args) {
            tag = _tag;
            title = _title;
            clss = _class;
            args = _args;
        }
    }

    public FragmentSlidingTabAdapter(Context context, FragmentManager fm) {
        super(fm);
        mContext = context;
    }

    public void addTab(String tag, int titleid, Class<?> clss, Bundle args) {
        TabInfo info = new TabInfo(tag, mContext.getString(titleid), clss, args);
        mTabs.add(info);
    }

    /**
     * @return the number of pages to display
     */
    @Override
    public int getCount() {
        return mTabs.size();
    }

    @Override
    public Fragment getItem(int position) {
        TabInfo tab = mTabs.get(position);
        if (tab.fragment == null) {
            tab.fragment = Fragment.instantiate(mContext, tab.clss.getName(), tab.args);
        }
        return tab.fragment;
    }

    @Override
    public CharSequence getPageTitle(int position) {
        return mTabs.get(position).title;
    }

    @Override
    public String getItemTag(int position) {
        return mTabs.get(position).tag;
    }

}
