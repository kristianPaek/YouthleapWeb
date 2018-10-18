package com.tech.oscar.youthleap.ui.fragment;

import android.annotation.TargetApi;
import android.os.Bundle;
import android.os.Handler;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.widget.CardView;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import com.tech.oscar.youthleap.AppConstant;
import com.tech.oscar.youthleap.R;
import com.tech.oscar.youthleap.adapter.LoadMoreRecyclerViewAdapter;
import com.tech.oscar.youthleap.model.MoodModel;
import com.tech.oscar.youthleap.ui.activity.BaseActionBarActivity;
import com.tech.oscar.youthleap.ui.activity.MainActivity;
import com.tech.oscar.youthleap.ui.view.MyAvatarImageView;
import com.tech.oscar.youthleap.util.DateTimeUtils;

import java.util.ArrayList;
import java.util.Date;


public class MainMoodFragment extends BaseFragment implements
        View.OnClickListener,
        SwipeRefreshLayout.OnRefreshListener,
        LoadMoreRecyclerViewAdapter.LoadMoreRecyclerViewAdapterListener {

    public static MainMoodFragment instance;
    // UI
    SwipeRefreshLayout refresh_layout;

    // Data
    BaseActionBarActivity mActivity;
    ArrayList<MoodModel> mDataList = new ArrayList<>() ;
    ArrayList<MoodModel> mMoodList = new ArrayList<>() ;
    LoadMoreRecyclerViewAdapter<MoodModel> mRecyclerAdapter;
    int mPageIndex = 0;
    int mPageCount = 10;
    boolean hasMoreData = false;

    public static MainMoodFragment newInstance() {
        MainMoodFragment fragment = new MainMoodFragment();

        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        instance = this;
        mActivity = MainActivity.instance;

        MoodModel model = new MoodModel();
        model.moodRes = R.drawable.mood_uneasy;
        model.studentName = "Tim H. McGraw";
        model.range = "On no twenty spring of in esteem spirit likely estate. Continue new you declared differed learning bringing honoured. At mean";
        model.color = "red";
        model.createAt = new Date();

        for (int i = 0; i < 150; i++)
            mDataList.add(model);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        mView = inflater.inflate(R.layout.fragment_main_mood, null);

        Toolbar toolbar = mView.findViewById(R.id.toolbar);
        initActionBar(mActivity, toolbar);
        mActivity.setTitle(R.string.menu_mood);

        refresh_layout = mView.findViewById(R.id.refresh_layout);
        refresh_layout.setOnRefreshListener(this);

        refresh_layout.setProgressViewOffset(false, 100, 200);
        refresh_layout.setColorSchemeResources(android.R.color.black,
                android.R.color.holo_green_dark,
                android.R.color.holo_orange_dark,
                android.R.color.holo_blue_dark);
        refresh_layout.post(new Runnable() {
            @Override
            public void run() {
                refresh_layout.setRefreshing(true);
                onRefresh();
            }
        });

        RecyclerView recycler_view = mView.findViewById(R.id.recycler_view);
        recycler_view.setLayoutManager(new LinearLayoutManager(mActivity));
        mRecyclerAdapter = new LoadMoreRecyclerViewAdapter<>(recycler_view, mActivity, mMoodList, 1);
        recycler_view.setAdapter(mRecyclerAdapter);
        mRecyclerAdapter.setLoadMoreRecyclerViewAdapterListener(this);

        mView.findViewById(R.id.btn_add).setOnClickListener(this);

        return mView;
    }

    @Override
    public void onClick(View v) {
        // TODO Auto-generated method stub
        switch (v.getId()) {
            case R.id.btn_add: {
                //Intent intent = new Intent(mActivity, AddPostActivity.class);
                //mActivity.startActivity(intent);
            }
            break;
        }
    }

    @Override
    public void onRefresh() {
        // TODO Auto-generated method stub
        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                getServerData(false);
            }
        }, AppConstant.DELAY_LOADING_MORE);
    }

    private void getServerData(final boolean isLoadMore) {
        if (isLoadMore)
            mPageIndex++;
        else
            mPageIndex = 0;

        if (isLoadMore)
            mRecyclerAdapter.setLoaded();
        else
            refresh_layout.setRefreshing(false);

        if (isLoadMore)
            mMoodList.remove(mMoodList.size() - 1);
        else
            mMoodList.clear();

        int limit = (mPageIndex+1)*mPageCount;
        if (limit > mDataList.size()) {
            limit = mDataList.size();
            hasMoreData = false;
        } else {
            hasMoreData = true;
        }
        for (int i = mPageIndex*mPageCount; i < limit; i++)
            mMoodList.add(mDataList.get(i));

        mRecyclerAdapter.notifyDataSetChanged();
    }

    class ViewHolder extends RecyclerView.ViewHolder {
        CardView layout_container;

        ImageView img_mood;
        TextView txt_range;
        TextView txt_color;
        TextView txt_date;
        MyAvatarImageView img_user_avatar;
        TextView txt_username;

        ViewHolder(final View parent) {
            super(parent);

            layout_container = parent.findViewById(R.id.layout_container);

            img_mood = parent.findViewById(R.id.img_mood);
            txt_range = parent.findViewById(R.id.txt_range);
            txt_color = parent.findViewById(R.id.txt_color);
            txt_date = parent.findViewById(R.id.txt_date);
            img_user_avatar = parent.findViewById(R.id.img_user_avatar);
            txt_username = parent.findViewById(R.id.txt_username);
        }
    }

    @TargetApi(21)
    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder viewHolder, int position) {
        final MoodModel model = mMoodList.get(position);
        if (model != null) {
            final ViewHolder holder = (ViewHolder) viewHolder;
            holder.layout_container.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                }
            });

            holder.img_mood.setImageResource(model.moodRes);
            holder.txt_range.setText(model.range);
            holder.txt_color.setText(model.color);
            holder.txt_date.setText(DateTimeUtils.dateToString(model.createAt, DateTimeUtils.DEFAULT_FORMAT_TIME));
            holder.img_user_avatar.setImageResource(R.drawable.oscar);
            holder.txt_username.setText(model.studentName);
        }
    }

    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        final View view = LayoutInflater.from(mActivity).inflate(R.layout.cell_main_mood, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onLoadMore() {
        Log.e("iSemester", "onLoadMore");
        if (hasMoreData) {
            mMoodList.add(null);
            mRecyclerAdapter.notifyItemInserted(mMoodList.size() - 1);

            new Handler().postDelayed(new Runnable() {
                @Override
                public void run() {
                    getServerData(true);
                }
            }, AppConstant.DELAY_LOADING_MORE);

        } else {
            mRecyclerAdapter.setLoaded();
        }
    }
}