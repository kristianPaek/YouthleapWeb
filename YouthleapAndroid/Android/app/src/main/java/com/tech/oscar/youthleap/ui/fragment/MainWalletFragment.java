package com.tech.oscar.youthleap.ui.fragment;

import android.annotation.TargetApi;
import android.graphics.drawable.Drawable;
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
import com.tech.oscar.youthleap.AppGlobals;
import com.tech.oscar.youthleap.R;
import com.tech.oscar.youthleap.adapter.LoadMoreRecyclerViewAdapter;
import com.tech.oscar.youthleap.model.WalletModel;
import com.tech.oscar.youthleap.model.base.BaseSubUser;
import com.tech.oscar.youthleap.restapi.Config;
import com.tech.oscar.youthleap.restapi.wallet.GetWalletsResult;
import com.tech.oscar.youthleap.restapi.wallet.WalletApi;
import com.tech.oscar.youthleap.ui.activity.BaseActionBarActivity;
import com.tech.oscar.youthleap.ui.activity.MainActivity;
import com.tech.oscar.youthleap.ui.view.MyAvatarImageView;
import com.tech.oscar.youthleap.util.DateTimeUtils;
import com.tech.oscar.youthleap.util.MessageUtil;

import java.util.ArrayList;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;


public class MainWalletFragment extends BaseFragment implements
        View.OnClickListener,
        SwipeRefreshLayout.OnRefreshListener,
        LoadMoreRecyclerViewAdapter.LoadMoreRecyclerViewAdapterListener {
    // UI
    SwipeRefreshLayout refresh_layout;

    // Data
    BaseActionBarActivity mActivity;

    BaseSubUser mUser;

    ArrayList<WalletModel> mWalletList = new ArrayList<>() ;
    LoadMoreRecyclerViewAdapter<WalletModel> mRecyclerAdapter;
    int mPageIndex = 0;
    int mPageCount = 10;
    boolean hasMoreData = false;

    public static MainWalletFragment newInstance(BaseSubUser subUser) {
        MainWalletFragment fragment = new MainWalletFragment();
        fragment.mUser = subUser;

        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        mActivity = MainActivity.instance;
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        mView = inflater.inflate(R.layout.fragment_main_ewallet, null);

        Toolbar toolbar = mView.findViewById(R.id.toolbar);
        initActionBar(mActivity, toolbar);
        mActivity.setTitle(R.string.menu_ewallet);

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
        mRecyclerAdapter = new LoadMoreRecyclerViewAdapter<>(recycler_view, mActivity, mWalletList, 1);
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

        (Config.retrofit.create(WalletApi.class))
                .getWallets(mUser.youthleapuser_id, 0, mPageIndex, mPageCount, AppGlobals.userToken)
                .enqueue(new Callback<GetWalletsResult>() {
                    @Override
                    public void onResponse(Call<GetWalletsResult> call, Response<GetWalletsResult> response) {
                        GetWalletsResult result = response.body();

                        if (isLoadMore)
                            mRecyclerAdapter.setLoaded();
                        else
                            refresh_layout.setRefreshing(false);

                        if (result != null && result.err_code == 0) {
                            if (isLoadMore)
                                mWalletList.remove(mWalletList.size() - 1);
                            else
                                mWalletList.clear();

                            mWalletList.addAll(result.wallets);
                            mRecyclerAdapter.notifyDataSetChanged();

                            hasMoreData = result.wallets.size() >= mPageCount;
                        } else {
                            MessageUtil.showToast(mActivity, result.err_msg, true);
                        }
                    }

                    @Override
                    public void onFailure(Call<GetWalletsResult> call, Throwable t) {
                        if (isLoadMore)
                            mRecyclerAdapter.setLoaded();
                        else
                            refresh_layout.setRefreshing(false);

                        MessageUtil.showToast(mActivity, t.getMessage(), true);
                    }
                });
    }

    class ViewHolder extends RecyclerView.ViewHolder {
        CardView layout_container;

        MyAvatarImageView img_avatar;
        TextView txt_name;
        TextView txt_time;
        TextView txt_point;
        TextView txt_purpose;

        ViewHolder(final View parent) {
            super(parent);

            layout_container = parent.findViewById(R.id.layout_container);

            img_avatar = parent.findViewById(R.id.img_avatar);
            txt_name = parent.findViewById(R.id.txt_name);
            txt_time = parent.findViewById(R.id.txt_time);
            txt_point = parent.findViewById(R.id.txt_point);
            txt_purpose = parent.findViewById(R.id.txt_purpose);
        }
    }

    @TargetApi(21)
    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder viewHolder, int position) {
        final WalletModel model = mWalletList.get(position);
        if (model != null) {
            final ViewHolder holder = (ViewHolder) viewHolder;
            holder.layout_container.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                }
            });

            holder.img_avatar.showImage(model.student.getImage());
            holder.txt_name.setText(model.student.getFullName());
            holder.txt_time.setText(DateTimeUtils.dateToString(model.wallet.transactAt, DateTimeUtils.DEFAULT_FORMAT_TIME));
            holder.txt_point.setText(model.wallet.points+"");
            Drawable d = getResources().getDrawable(model.wallet.transactType > 0 ? R.drawable.arrow_up : R.drawable.arrow_down);
            holder.txt_point.setCompoundDrawablesWithIntrinsicBounds(d,null, null,null);
            holder.txt_purpose.setText(model.purpose.displayName);
        }
    }

    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        final View view = LayoutInflater.from(mActivity).inflate(R.layout.cell_main_ewallet, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onLoadMore() {
        Log.e("iSemester", "onLoadMore");
        if (hasMoreData) {
            mWalletList.add(null);
            mRecyclerAdapter.notifyItemInserted(mWalletList.size() - 1);

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