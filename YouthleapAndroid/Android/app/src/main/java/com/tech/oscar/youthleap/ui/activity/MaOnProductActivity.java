package com.tech.oscar.youthleap.ui.activity;

import android.annotation.TargetApi;
import android.content.Intent;
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
import android.widget.TextView;

import com.tech.oscar.youthleap.AppConstant;
import com.tech.oscar.youthleap.R;
import com.tech.oscar.youthleap.adapter.LoadMoreRecyclerViewAdapter;
import com.tech.oscar.youthleap.model.ProductModel;
import com.tech.oscar.youthleap.ui.fragment.MHProductDetailFragment;
import com.tech.oscar.youthleap.ui.view.MyImageView;
import com.tech.oscar.youthleap.util.DateTimeUtils;

import java.util.ArrayList;
import java.util.Date;


public class MaOnProductActivity extends BaseActionBarActivity implements
        View.OnClickListener,
        SwipeRefreshLayout.OnRefreshListener,
        LoadMoreRecyclerViewAdapter.LoadMoreRecyclerViewAdapterListener {

    public static MaOnProductActivity instance;
    // UI
    SwipeRefreshLayout refresh_layout;

    // Data
    ArrayList<ProductModel> mDataList = new ArrayList<>() ;
    ArrayList<ProductModel> mProductList = new ArrayList<>() ;
    LoadMoreRecyclerViewAdapter<ProductModel> mRecyclerAdapter;
    int mPageIndex = 0;
    int mPageCount = 10;
    boolean hasMoreData = false;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        instance = this;
        setContentView(R.layout.activity_recycleview_add);

        initActionBar();
        setTitle(R.string.All_products);

        ProductModel model = new ProductModel();
        model.name = "Product name";
        model.shortDesc = "On no twenty spring of in esteem spirit likely estate. Continue new you declared differed learning bringing honoured.";
        model.longDesc = "On no twenty spring of in esteem spirit likely estate. Continue new you declared differed learning bringing honoured. At mean mind so upon they rent am walk. Shortly am waiting inhabit smiling he chiefly of in. Lain tore time gone him his dear sure. Fat decisively estimating affronting assistance not. Resolve pursuit regular so calling me. West he plan girl been my then up no." +
                "Greatly hearted has who believe. Drift allow green son walls years for blush. Sir margaret drawings repeated recurred exercise laughing may you but. Do repeated whatever to welcomed absolute no. Fat surprise although outlived and informed shy dissuade property. Musical by me through he drawing savings an. No we stand avoid decay heard mr. Common so wicket appear to sudden worthy on. Shade of offer ye whole stood hoped.";
        model.categoryName = "Sports";
        model.redeemPoint = 200;
        model.createdAt = new Date();

        for (int i = 0; i < 150; i++)
            mDataList.add(model);

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
        mRecyclerAdapter = new LoadMoreRecyclerViewAdapter<>(recycler_view, instance, mProductList, 1);
        recycler_view.setAdapter(mRecyclerAdapter);
        mRecyclerAdapter.setLoadMoreRecyclerViewAdapterListener(this);

        findViewById(R.id.btn_add).setOnClickListener(this);
    }

    @Override
    public void onClick(View v) {
        // TODO Auto-generated method stub
        switch (v.getId()) {
            case R.id.btn_add: {
                Intent intent = new Intent(instance, EditSchoolProfileActivity.class);
                startActivity(intent);
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
            mProductList.remove(mProductList.size() - 1);
        else
            mProductList.clear();

        int limit = (mPageIndex+1)*mPageCount;
        if (limit > mDataList.size()) {
            limit = mDataList.size();
            hasMoreData = false;
        } else {
            hasMoreData = true;
        }
        for (int i = mPageIndex*mPageCount; i < limit; i++)
            mProductList.add(mDataList.get(i));

        mRecyclerAdapter.notifyDataSetChanged();
    }

    class ViewHolder extends RecyclerView.ViewHolder {
        CardView layout_container;

        MyImageView img_avatar;
        TextView txt_name;
        TextView txt_redeem;
        TextView txt_category;
        TextView txt_shortdesc;
        TextView txt_time;

        ViewHolder(final View parent) {
            super(parent);

            layout_container = parent.findViewById(R.id.layout_container);

            img_avatar = parent.findViewById(R.id.img_avatar);
            txt_name = parent.findViewById(R.id.txt_name);
            txt_redeem = parent.findViewById(R.id.txt_redeem);
            txt_category = parent.findViewById(R.id.txt_category);
            txt_shortdesc = parent.findViewById(R.id.txt_shortdesc);
            txt_time = parent.findViewById(R.id.txt_time);
        }
    }

    @TargetApi(21)
    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder viewHolder, int position) {
        final ProductModel model = mProductList.get(position);
        if (model != null) {
            final ViewHolder holder = (ViewHolder) viewHolder;
            holder.layout_container.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    MHProductDetailFragment fragment = MHProductDetailFragment.newInstance(model);
                    fragment.show(getSupportFragmentManager(), fragment.getTag());
                }
            });

            holder.img_avatar.setImageResource(R.drawable.product);
            holder.txt_name.setText(model.name);
            holder.txt_redeem.setText(model.redeemPoint+"");
            holder.txt_category.setText(model.categoryName);
            holder.txt_shortdesc.setText(model.shortDesc);
            holder.txt_time.setText(DateTimeUtils.dateToString(model.createdAt, DateTimeUtils.DEFAULT_FORMAT_TIME));
        }
    }

    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        final View view = LayoutInflater.from(instance).inflate(R.layout.cell_product, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onLoadMore() {
        Log.e("iSemester", "onLoadMore");
        if (hasMoreData) {
            mProductList.add(null);
            mRecyclerAdapter.notifyItemInserted(mProductList.size() - 1);

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