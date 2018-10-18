package com.tech.oscar.youthleap.adapter;

import android.content.Context;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.tech.oscar.youthleap.R;
import com.tech.oscar.youthleap.ui.view.LoadingViewHolder;

import java.util.ArrayList;

/*
 * RecyclerView Adapter that allows to add a header view.
 * */
public class LoadMoreRecyclerViewNoHeaderAdapter<T> extends RecyclerView.Adapter<RecyclerView.ViewHolder> {
    private static final int TYPE_ITEM = 1;
    private static final int TYPE_LOADING_MORE = 2;

    // data
    private Context mContext;

    // loading more
    boolean isLoading;
    private int mVisibleThreshold = 5;
    private int lastVisibleItem, totalItemCount;

    ArrayList<T> mDataList;

    public LoadMoreRecyclerViewNoHeaderAdapter(RecyclerView recycler_view, Context context, ArrayList<T> data, int visibleThreshold) {
        mContext = context;
        mDataList = data;

        final LinearLayoutManager layoutMgr = (LinearLayoutManager) recycler_view.getLayoutManager();
        recycler_view.addOnScrollListener(new RecyclerView.OnScrollListener() {
            @Override
            public void onScrolled(RecyclerView recyclerView, int dx, int dy) {
                super.onScrolled(recyclerView, dx, dy);

                totalItemCount = layoutMgr.getItemCount();
                lastVisibleItem = layoutMgr.findLastVisibleItemPosition();
                if (!isLoading && totalItemCount <= (lastVisibleItem + mVisibleThreshold)) {
                    isLoading = true;
                    if (mLoadMoreRecyclerViewNoHeaderAdapterListener != null)
                        mLoadMoreRecyclerViewNoHeaderAdapterListener.onLoadMore();
                }
            }
        });
    }

    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        if (viewType == TYPE_ITEM) {
            if (mLoadMoreRecyclerViewNoHeaderAdapterListener != null)
                return mLoadMoreRecyclerViewNoHeaderAdapterListener.onCreateViewHolder(parent, viewType);
            return null;

        } else if (viewType == TYPE_LOADING_MORE) {
            View view = LayoutInflater.from(mContext).inflate(R.layout.recycler_loading_more, parent, false);
            return new LoadingViewHolder(view);
        }
        throw new RuntimeException("There is no type that matches the type " + viewType + " + make sure your using types correctly");
    }

    public void onBindViewHolder(RecyclerView.ViewHolder viewHolder, int position) {
        if (!isPostionLoadingMore(position)) {
            if (mLoadMoreRecyclerViewNoHeaderAdapterListener != null)
                mLoadMoreRecyclerViewNoHeaderAdapterListener.onBindViewHolder(viewHolder, position);
        }
    }

    @Override
    public int getItemViewType(int position) {
        if (isPostionLoadingMore(position)) {
            return TYPE_LOADING_MORE;
        }
        return TYPE_ITEM;
    }

    @Override
    public int getItemCount() {
        return mDataList == null ? 0 : getBasicItemCount();
    }

    public void setLoaded() {
        isLoading = false;
    }

    public int getBasicItemCount() {
        return mDataList.size();
    }

    private boolean isPostionLoadingMore(int position) {
        return mDataList.get(position) == null;
    }

    LoadMoreRecyclerViewNoHeaderAdapterListener mLoadMoreRecyclerViewNoHeaderAdapterListener;

    public void setLoadMoreRecyclerViewAdapterListener(LoadMoreRecyclerViewNoHeaderAdapterListener listener) {
        mLoadMoreRecyclerViewNoHeaderAdapterListener = listener;
    }

    public interface LoadMoreRecyclerViewNoHeaderAdapterListener {
        public void onBindViewHolder(RecyclerView.ViewHolder viewHolder, int position);
        public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType);
        public void onLoadMore();
    }
}
