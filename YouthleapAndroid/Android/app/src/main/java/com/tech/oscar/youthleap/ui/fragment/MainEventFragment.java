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
import android.widget.TextView;

import com.tech.oscar.youthleap.AppConstant;
import com.tech.oscar.youthleap.R;
import com.tech.oscar.youthleap.adapter.LoadMoreRecyclerViewAdapter;
import com.tech.oscar.youthleap.model.EventModel;
import com.tech.oscar.youthleap.ui.activity.BaseActionBarActivity;
import com.tech.oscar.youthleap.ui.activity.MainActivity;
import com.tech.oscar.youthleap.ui.view.TagView;
import com.tech.oscar.youthleap.util.DateTimeUtils;

import java.util.ArrayList;
import java.util.Date;


public class MainEventFragment extends BaseFragment implements
        View.OnClickListener,
        SwipeRefreshLayout.OnRefreshListener,
        LoadMoreRecyclerViewAdapter.LoadMoreRecyclerViewAdapterListener {

    public static MainEventFragment instance;
    // UI
    SwipeRefreshLayout refresh_layout;

    // Data
    BaseActionBarActivity mActivity;
    ArrayList<EventModel> mDataList = new ArrayList<>();
    ArrayList<EventModel> mEventList = new ArrayList<>();
    LoadMoreRecyclerViewAdapter<EventModel> mRecyclerAdapter;
    int mPageIndex = 0;
    int mPageCount = 10;
    boolean hasMoreData = false;

    public static MainEventFragment newInstance() {
        MainEventFragment fragment = new MainEventFragment();

        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        instance = this;
        mActivity = MainActivity.instance;

        EventModel model = new EventModel();
        model.name = "How about English class A?";
        model.classes = new ArrayList<>();
        model.classes.add("Basic A");
        model.classes.add("Basic B");
        model.classes.add("Basic C");
        model.classes.add("Professional A");
        model.classes.add("Professional B");
        model.subjects = new ArrayList<>();
        model.subjects.add("English");
        model.subjects.add("Physics");
        model.subjects.add("Mathematics");
        model.subjects.add("Chemistry");
        model.subjects.add("Algebra");
        model.subjects.add("Sports");
        model.macAddress = "ac:er:sa:2e:5g:23";
        model.entry = "Entire any had depend and figure winter. Change stairs and men likely wisdom new happen piqued six. Now taken him timed sex world get. Enjoyed married an feeling delight pursuit as offered. As admire roused length likely played pretty to no. Means had joy miles her merry solid order.";
        model.startAt = new Date();
        model.endAt = new Date();

        for (int i = 0; i < 150; i++)
            mDataList.add(model);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        mView = inflater.inflate(R.layout.fragment_main_event, null);

        Toolbar toolbar = mView.findViewById(R.id.toolbar);
        initActionBar(mActivity, toolbar);
        mActivity.setTitle(R.string.menu_attendance);

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
        mRecyclerAdapter = new LoadMoreRecyclerViewAdapter<>(recycler_view, mActivity, mEventList, 1);
        recycler_view.setAdapter(mRecyclerAdapter);
        mRecyclerAdapter.setLoadMoreRecyclerViewAdapterListener(this);

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
            mEventList.remove(mEventList.size() - 1);
        else
            mEventList.clear();

        int limit = (mPageIndex + 1) * mPageCount;
        if (limit > mDataList.size()) {
            limit = mDataList.size();
            hasMoreData = false;
        } else {
            hasMoreData = true;
        }
        for (int i = mPageIndex * mPageCount; i < limit; i++)
            mEventList.add(mDataList.get(i));

        mRecyclerAdapter.notifyDataSetChanged();
    }

    class ViewHolder extends RecyclerView.ViewHolder {
        CardView layout_container;

        TextView txt_name;
        TagView tag_class;
        TagView tag_subject;
        TextView txt_macaddress;
        TextView txt_entry;
        TextView txt_time;

        ViewHolder(final View parent) {
            super(parent);

            layout_container = parent.findViewById(R.id.layout_container);

            txt_name = parent.findViewById(R.id.txt_name);

            tag_class = parent.findViewById(R.id.tag_class);
            tag_class.setItemMaxLength(20);
            tag_class.setTextColor(getResources().getColor(android.R.color.black));
            tag_class.setTextSize(12);
            tag_class.setPaddingSize(32, 6, 32, 6);
            tag_class.setItemBackground(R.drawable.bg_round_strike_blue);

            tag_subject = parent.findViewById(R.id.tag_subject);
            tag_subject.setItemMaxLength(20);
            tag_subject.setTextColor(getResources().getColor(android.R.color.white));
            tag_subject.setTextSize(12);
            tag_subject.setPaddingSize(32, 6, 32, 6);
            tag_subject.setItemBackground(R.drawable.bg_round_green);

            txt_macaddress = parent.findViewById(R.id.txt_macaddress);
            txt_entry = parent.findViewById(R.id.txt_entry);
            txt_time = parent.findViewById(R.id.txt_time);
        }
    }

    @TargetApi(21)
    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder viewHolder, int position) {
        final EventModel model = mEventList.get(position);
        if (model != null) {
            final ViewHolder holder = (ViewHolder) viewHolder;
            holder.layout_container.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                }
            });

            holder.txt_name.setText(model.name);
            holder.tag_class.clear();
            for (int i = 0; i < model.classes.size(); i++)
                holder.tag_class.addItem(model.classes.get(i), null);
            holder.tag_subject.clear();
            for (int i = 0; i < model.subjects.size(); i++)
                holder.tag_subject.addItem(model.subjects.get(i), null);
            holder.txt_macaddress.setText(model.macAddress);
            holder.txt_entry.setText(model.entry);
            holder.txt_time.setText(String.format("%s - %s", DateTimeUtils.dateToString(model.startAt, DateTimeUtils.DEFAULT_FORMAT_TIME),
                    DateTimeUtils.dateToString(model.endAt, DateTimeUtils.DEFAULT_FORMAT_TIME)));
        }
    }

    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        final View view = LayoutInflater.from(mActivity).inflate(R.layout.cell_main_event, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onLoadMore() {
        Log.e("iSemester", "onLoadMore");
        if (hasMoreData) {
            mEventList.add(null);
            mRecyclerAdapter.notifyItemInserted(mEventList.size() - 1);

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