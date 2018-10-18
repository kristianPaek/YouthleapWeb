package com.tech.oscar.youthleap.ui.activity;

import android.annotation.TargetApi;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.os.Bundle;
import android.os.Handler;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.widget.CardView;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import com.tech.oscar.youthleap.AppConstant;
import com.tech.oscar.youthleap.AppGlobals;
import com.tech.oscar.youthleap.R;
import com.tech.oscar.youthleap.adapter.LoadMoreRecyclerViewAdapter;
import com.tech.oscar.youthleap.model.base.BaseStoreCategory;
import com.tech.oscar.youthleap.restapi.Config;
import com.tech.oscar.youthleap.restapi.EmptyResult;
import com.tech.oscar.youthleap.restapi.store.GetCategoriesResult;
import com.tech.oscar.youthleap.restapi.store.StoreApi;
import com.tech.oscar.youthleap.ui.dialog.EditStoreCategoryDialog;
import com.tech.oscar.youthleap.util.MessageUtil;

import java.util.ArrayList;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;


public class MaOnProductCategoryActivity extends BaseActionBarActivity implements
        View.OnClickListener,
        SwipeRefreshLayout.OnRefreshListener,
        LoadMoreRecyclerViewAdapter.LoadMoreRecyclerViewAdapterListener {

    public static MaOnProductCategoryActivity instance;
    // UI
    SwipeRefreshLayout refresh_layout;

    // Data
    ArrayList<BaseStoreCategory> mCategorytList = new ArrayList<>() ;
    LoadMoreRecyclerViewAdapter<BaseStoreCategory> mRecyclerAdapter;
    int mPageIndex = 0;
    int mPageCount = 10;
    boolean hasMoreData = false;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        instance = this;
        setContentView(R.layout.activity_recycleview_add);

        initActionBar();
        setTitle(R.string.All_product_categories);

        refresh_layout = findViewById(R.id.refresh_layout);
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

        RecyclerView recycler_view = findViewById(R.id.recycler_view);
        recycler_view.setLayoutManager(new LinearLayoutManager(instance));
        mRecyclerAdapter = new LoadMoreRecyclerViewAdapter<>(recycler_view, instance, mCategorytList, 1);
        recycler_view.setAdapter(mRecyclerAdapter);
        mRecyclerAdapter.setLoadMoreRecyclerViewAdapterListener(this);

        findViewById(R.id.btn_add).setOnClickListener(this);
    }

    @Override
    public void onClick(View v) {
        // TODO Auto-generated method stub
        switch (v.getId()) {
            case R.id.btn_add: {
                EditStoreCategoryDialog dlg = new EditStoreCategoryDialog(instance);
                EditStoreCategoryDialog.mCategory = null;
                dlg.show();
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

        (Config.retrofit.create(StoreApi.class))
                .getCategories(0, mPageIndex, mPageCount, AppGlobals.userToken)
                .enqueue(new Callback<GetCategoriesResult>() {
                    @Override
                    public void onResponse(Call<GetCategoriesResult> call, Response<GetCategoriesResult> response) {
                        GetCategoriesResult result = response.body();

                        if (isLoadMore)
                            mRecyclerAdapter.setLoaded();
                        else
                            refresh_layout.setRefreshing(false);

                        if (result != null && result.err_code == 0) {
                            if (isLoadMore)
                                mCategorytList.remove(mCategorytList.size() - 1);
                            else
                                mCategorytList.clear();

                            mCategorytList.addAll(result.categories);
                            mRecyclerAdapter.notifyDataSetChanged();

                            hasMoreData = result.categories.size() >= mPageCount;
                        } else {
                            MessageUtil.showToast(instance, result.err_msg, true);
                        }
                    }

                    @Override
                    public void onFailure(Call<GetCategoriesResult> call, Throwable t) {
                        if (isLoadMore)
                            mRecyclerAdapter.setLoaded();
                        else
                            refresh_layout.setRefreshing(false);

                        MessageUtil.showToast(instance, t.getMessage(), true);
                    }
                });
    }

    class ViewHolder extends RecyclerView.ViewHolder {
        CardView layout_container;

        TextView txt_name;
        TextView txt_time;
        ImageView img_edit;
        ImageView img_delete;

        ViewHolder(final View parent) {
            super(parent);

            layout_container = parent.findViewById(R.id.layout_container);

            txt_name = parent.findViewById(R.id.txt_name);
            txt_time = parent.findViewById(R.id.txt_time);
            img_edit = parent.findViewById(R.id.img_edit);
            img_delete = parent.findViewById(R.id.img_delete);
        }
    }

    @TargetApi(21)
    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder viewHolder, final int position) {
        final BaseStoreCategory model = mCategorytList.get(position);
        if (model != null) {
            final ViewHolder holder = (ViewHolder) viewHolder;
            holder.txt_name.setText(model.name);
            holder.txt_time.setText(model.createdAt);

            holder.img_edit.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    EditStoreCategoryDialog dlg = new EditStoreCategoryDialog(instance);
                    EditStoreCategoryDialog.mCategory = model;
                    dlg.show();
                }
            });

            holder.img_delete.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    new AlertDialog.Builder(instance)
                            .setMessage(R.string.dialog_remove)
                            .setPositiveButton(R.string.YES, new DialogInterface.OnClickListener() {
                                @Override
                                public void onClick(DialogInterface dialog, int which) {
                                    dlg_progress.show();
                                    (Config.retrofit.create(StoreApi.class))
                                            .removeCategory(model.id+"", AppGlobals.userToken)
                                            .enqueue(new Callback<EmptyResult>() {
                                                @Override
                                                public void onResponse(Call<EmptyResult> call, Response<EmptyResult> response) {
                                                    dlg_progress.hide();

                                                    EmptyResult result = response.body();
                                                    if (result != null && result.err_code == 0) {
                                                        mCategorytList.remove(position);
                                                        mRecyclerAdapter.notifyItemRemoved(position);
                                                        mRecyclerAdapter.notifyItemRangeChanged(position, mCategorytList.size());
                                                        MessageUtil.showToast(instance, R.string.Removed, true);
                                                    } else {
                                                        MessageUtil.showToast(instance, result.err_msg, true);
                                                    }
                                                }

                                                @Override
                                                public void onFailure(Call<EmptyResult> call, Throwable t) {
                                                    dlg_progress.hide();
                                                    MessageUtil.showToast(instance, t.getMessage(), true);
                                                }
                                            });
                                }
                            })
                            .setNegativeButton(R.string.Cancel, null)
                            .show();
                }
            });
        }
    }

    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        final View view = LayoutInflater.from(instance).inflate(R.layout.cell_product_cateogory, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onLoadMore() {
        Log.e("iSemester", "onLoadMore");
        if (hasMoreData) {
            mCategorytList.add(null);
            mRecyclerAdapter.notifyItemInserted(mCategorytList.size() - 1);

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