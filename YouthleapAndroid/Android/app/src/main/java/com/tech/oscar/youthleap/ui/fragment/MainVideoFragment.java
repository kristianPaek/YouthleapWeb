package com.tech.oscar.youthleap.ui.fragment;

import android.annotation.TargetApi;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.support.annotation.NonNull;
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

import com.google.android.exoplayer2.ui.PlayerView;
import com.tech.oscar.youthleap.AppConstant;
import com.tech.oscar.youthleap.R;
import com.tech.oscar.youthleap.adapter.LoadMoreRecyclerViewAdapter;
import com.tech.oscar.youthleap.model.VideoModel;
import com.tech.oscar.youthleap.ui.activity.BaseActionBarActivity;
import com.tech.oscar.youthleap.ui.activity.MainActivity;

import java.util.ArrayList;
import java.util.List;

import im.ene.toro.ToroPlayer;
import im.ene.toro.ToroUtil;
import im.ene.toro.exoplayer.ExoPlayerViewHelper;
import im.ene.toro.media.PlaybackInfo;
import im.ene.toro.widget.Container;

public class MainVideoFragment extends BaseFragment implements
        View.OnClickListener,
        SwipeRefreshLayout.OnRefreshListener,
        LoadMoreRecyclerViewAdapter.LoadMoreRecyclerViewAdapterListener {

    public static MainVideoFragment instance;
    // UI
    SwipeRefreshLayout refresh_layout;
    Container recycler_view;
    LinearLayoutManager recycler_layout_mgr;

    // Data
    BaseActionBarActivity mActivity;
    ArrayList<VideoModel> mDataList = new ArrayList<>() ;
    ArrayList<VideoModel> mVideoList = new ArrayList<>() ;
    LoadMoreRecyclerViewAdapter<VideoModel> mRecyclerAdapter;
    int mPageIndex = 0;
    int mPageCount = 10;
    boolean hasMoreData = false;

    public static MainVideoFragment newInstance() {
        MainVideoFragment fragment = new MainVideoFragment();

        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        instance = this;
        mActivity = MainActivity.instance;

        List<String> videoPaths = new ArrayList<>();
        videoPaths.add("https://api.parse.buddy.com/files/a75050d5-7432-42a2-8ef9-71210de8ca93/59c937bb1102f9b9c0166fb8477565db_0005.mp4");
        videoPaths.add("https://api.parse.buddy.com/files/a75050d5-7432-42a2-8ef9-71210de8ca93/304d11f6a35180164140ea485d8401d1_0004.mp4");
        videoPaths.add("https://api.parse.buddy.com/files/a75050d5-7432-42a2-8ef9-71210de8ca93/b816622b63050e52e020e22bf107908b_0003.mp4");
        videoPaths.add("https://api.parse.buddy.com/files/a75050d5-7432-42a2-8ef9-71210de8ca93/d3acfff2097281c596b91cd17c511f74_0002.mp4");
        videoPaths.add("https://api.parse.buddy.com/files/a75050d5-7432-42a2-8ef9-71210de8ca93/602175df07b903745189ad2cc1a3d2ff_0001.mp4");
        videoPaths.add("https://api.parse.buddy.com/files/a75050d5-7432-42a2-8ef9-71210de8ca93/f64c1fb53869fdb56eaf492d8ce2f90e_1507940118923-hysdc8.mp4");
        videoPaths.add("https://api.parse.buddy.com/files/a75050d5-7432-42a2-8ef9-71210de8ca93/33272b424c8b83494d102c5a2107a838_xxx.mp4");

        for (int i = 0; i < 150; i++) {
            VideoModel model = new VideoModel();
            model.videoPath = videoPaths.get(i % videoPaths.size());
            model.name = "English class A";
            model.desc = "Hello, English class A desc. English class A desc. English class A desc. English class A desc. English class A desc. English class A desc. English class A desc. ";
            model.className = "Class A";
            mDataList.add(model);
        }
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        mView = inflater.inflate(R.layout.fragment_main_video, null);

        Toolbar toolbar = mView.findViewById(R.id.toolbar);
        initActionBar(mActivity, toolbar);
        mActivity.setTitle(R.string.menu_tutorial_video);

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

        recycler_view = mView.findViewById(R.id.recycler_view);

        recycler_layout_mgr = new LinearLayoutManager(mActivity);
        recycler_view.setLayoutManager(recycler_layout_mgr);

        mRecyclerAdapter = new LoadMoreRecyclerViewAdapter<>(recycler_view, mActivity, mVideoList, 1);
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
            mVideoList.remove(mVideoList.size() - 1);
        else
            mVideoList.clear();

        int limit = (mPageIndex+1)*mPageCount;
        if (limit > mDataList.size()) {
            limit = mDataList.size();
            hasMoreData = false;
        } else {
            hasMoreData = true;
        }
        for (int i = mPageIndex*mPageCount; i < limit; i++)
            mVideoList.add(mDataList.get(i));

        mRecyclerAdapter.notifyDataSetChanged();
    }

    class ViewHolder extends RecyclerView.ViewHolder implements ToroPlayer {
        CardView layout_container;

        TextView txt_name;
        TextView txt_desc;
        PlayerView video_player;
        TextView txt_classname;

        // helper
        private ExoPlayerViewHelper helper;
        private Uri mediaUri;

        ViewHolder(final View parent) {
            super(parent);

            layout_container = parent.findViewById(R.id.layout_container);

            txt_name = parent.findViewById(R.id.txt_name);
            txt_desc = parent.findViewById(R.id.txt_desc);
            video_player = parent.findViewById(R.id.video_player);
            txt_classname = parent.findViewById(R.id.txt_classname);
        }

        public void bind(String path) {
            this.mediaUri = Uri.parse(path);;
        }

        @Override
        public void initialize(@NonNull Container container, @NonNull PlaybackInfo playbackInfo) {
            if (helper == null) {
                helper = new ExoPlayerViewHelper(this, mediaUri);
            }
            helper.initialize(container, playbackInfo);
        }

        @Override
        public void play() {
            if (helper != null)
                helper.play();
        }

        @Override
        public void pause() {
            if (helper != null)
                helper.pause();
        }

        @Override
        public boolean isPlaying() {
            return helper != null && helper.isPlaying();
        }

        @Override
        public void release() {
            if (helper != null) {
                helper.release();
                helper = null;
            }
        }

        @NonNull
        @Override
        public PlaybackInfo getCurrentPlaybackInfo() {
            return helper != null ? helper.getLatestPlaybackInfo() : new PlaybackInfo();
        }

        @NonNull
        @Override
        public View getPlayerView() {
            return video_player;
        }
        @Override
        public boolean wantsToPlay() {
            return ToroUtil.visibleAreaOffset(this, itemView.getParent()) >= 0.85;
        }
        @Override
        public int getPlayerOrder() {
            return getAdapterPosition();
        }
    }

    @TargetApi(21)
    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder viewHolder, int position) {
        final VideoModel model = mVideoList.get(position);
        if (model != null) {
            final ViewHolder holder = (ViewHolder) viewHolder;
            holder.layout_container.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                }
            });

            holder.txt_name.setText(model.name);
            holder.txt_desc.setText(model.desc);
            holder.bind(model.videoPath);
            holder.txt_classname.setText(model.className);
        }
    }

    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        final View view = LayoutInflater.from(mActivity).inflate(R.layout.cell_main_video, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onLoadMore() {
        Log.e("iSemester", "onLoadMore");
        if (hasMoreData) {
            mVideoList.add(null);
            mRecyclerAdapter.notifyItemInserted(mVideoList.size() - 1);

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