package com.tech.oscar.youthleap.ui.activity;

import android.annotation.TargetApi;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.drawable.Drawable;
import android.os.Bundle;
import android.os.Handler;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.app.AlertDialog;
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
import com.tech.oscar.youthleap.model.StudentModel;
import com.tech.oscar.youthleap.model.UserModel;
import com.tech.oscar.youthleap.restapi.Config;
import com.tech.oscar.youthleap.restapi.EmptyResult;
import com.tech.oscar.youthleap.restapi.student.GetStudentResult;
import com.tech.oscar.youthleap.restapi.student.StudentApi;
import com.tech.oscar.youthleap.ui.fragment.MHStudentDetailFragment;
import com.tech.oscar.youthleap.ui.view.MyAvatarImageView;
import com.tech.oscar.youthleap.util.MessageUtil;

import java.util.ArrayList;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class MaMaStudentsActivity extends BaseActionBarActivity implements
        View.OnClickListener,
        SwipeRefreshLayout.OnRefreshListener,
        LoadMoreRecyclerViewAdapter.LoadMoreRecyclerViewAdapterListener {

    public static MaMaStudentsActivity instance;
    // UI
    SwipeRefreshLayout refresh_layout;

    // Data
    ArrayList<StudentModel> mStudentList = new ArrayList<>() ;
    LoadMoreRecyclerViewAdapter<StudentModel> mRecyclerAdapter;
    int mPageIndex = 0;
    int mPageCount = 10;
    boolean hasMoreData = false;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        instance = this;
        setContentView(R.layout.activity_recycleview_add);

        initActionBar();
        setTitle(R.string.All_students);

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
        mRecyclerAdapter = new LoadMoreRecyclerViewAdapter<>(recycler_view, instance, mStudentList, 1);
        recycler_view.setAdapter(mRecyclerAdapter);
        mRecyclerAdapter.setLoadMoreRecyclerViewAdapterListener(this);

        findViewById(R.id.btn_add).setOnClickListener(this);
    }

    @Override
    public void onClick(View v) {
        // TODO Auto-generated method stub
        switch (v.getId()) {
            case R.id.btn_add: {
                Intent intent = new Intent(instance, EditStudentProfileActivity.class);
                EditStudentProfileActivity.mUser = new UserModel();
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

        (Config.retrofit.create(StudentApi.class))
                .getStudents(-1, 0, mPageIndex, mPageCount, AppGlobals.userToken)
                .enqueue(new Callback<GetStudentResult>() {
                    @Override
                    public void onResponse(Call<GetStudentResult> call, Response<GetStudentResult> response) {
                        GetStudentResult result = response.body();

                        if (isLoadMore)
                            mRecyclerAdapter.setLoaded();
                        else
                            refresh_layout.setRefreshing(false);

                        if (result != null && result.err_code == 0) {
                            if (isLoadMore)
                                mStudentList.remove(mStudentList.size() - 1);
                            else
                                mStudentList.clear();

                            mStudentList.addAll(result.students);
                            mRecyclerAdapter.notifyDataSetChanged();

                            hasMoreData = result.students.size() >= mPageCount;
                        } else {
                            MessageUtil.showToast(instance, result.err_msg, true);
                        }
                    }

                    @Override
                    public void onFailure(Call<GetStudentResult> call, Throwable t) {
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

        MyAvatarImageView img_avatar;
        TextView txt_name;
        TextView txt_birthday;
        TextView txt_email;
        ImageView img_edit;
        ImageView img_lock;
        ImageView img_delete;

        ViewHolder(final View parent) {
            super(parent);

            layout_container = parent.findViewById(R.id.layout_container);

            img_avatar = parent.findViewById(R.id.img_avatar);
            txt_name = parent.findViewById(R.id.txt_name);
            txt_birthday = parent.findViewById(R.id.txt_birthday);
            txt_email = parent.findViewById(R.id.txt_email);
            img_edit = parent.findViewById(R.id.img_edit);
            img_lock = parent.findViewById(R.id.img_lock);
            img_delete = parent.findViewById(R.id.img_delete);
        }
    }

    @TargetApi(21)
    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder viewHolder, final int position) {
        final StudentModel model = mStudentList.get(position);
        if (model != null) {
            final ViewHolder holder = (ViewHolder) viewHolder;
            holder.layout_container.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    MHStudentDetailFragment fragment = MHStudentDetailFragment.newInstance(instance, model);
                    fragment.show(getSupportFragmentManager(), fragment.getTag());
                }
            });

            holder.img_avatar.showImage(model.student.getImage());
            holder.txt_name.setText(model.student.getFullName());
            Drawable d = getResources().getDrawable(model.student.gender == AppConstant.GENDER_MALE ? R.drawable.gender_male : R.drawable.gender_female);
            holder.txt_name.setCompoundDrawablesWithIntrinsicBounds(d,null, null,null);
            holder.txt_birthday.setText(model.student.getBirthdayStr());
            holder.txt_email.setText(model.student.email);

            holder.img_edit.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    Intent intent = new Intent(instance, EditStudentProfileActivity.class);
                    EditStudentProfileActivity.mUser = new UserModel();
                    EditStudentProfileActivity.mUser.subUser = model.student;
                    startActivity(intent);
                }
            });

            holder.img_lock.setImageResource(model.student.isActive > 0 ? R.drawable.ic_action_unlock : R.drawable.ic_action_lock);
            holder.img_lock.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    new AlertDialog.Builder(instance)
                            .setMessage(model.student.isActive > 0 ? R.string.dialog_deactive : R.string.dialog_active)
                            .setPositiveButton(R.string.OK, new DialogInterface.OnClickListener() {
                                @Override
                                public void onClick(DialogInterface dialog, int which) {
                                    dlg_progress.show();
                                    (Config.retrofit.create(StudentApi.class))
                                            .activeStudent(model.student.id, model.student.isActive > 0 ? 0 : 1, AppGlobals.userToken)
                                            .enqueue(new Callback<EmptyResult>() {
                                                @Override
                                                public void onResponse(Call<EmptyResult> call, Response<EmptyResult> response) {
                                                    dlg_progress.hide();
                                                    EmptyResult result = response.body();
                                                    if (result != null && result.err_code == 0) {
                                                        model.student.isActive = model.student.isActive > 0 ? 0 : 1;
                                                        mRecyclerAdapter.notifyDataSetChanged();

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

            holder.img_delete.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    new AlertDialog.Builder(instance)
                            .setMessage(R.string.dialog_remove)
                            .setPositiveButton(R.string.OK, new DialogInterface.OnClickListener() {
                                @Override
                                public void onClick(DialogInterface dialog, int which) {
                                    dlg_progress.show();
                                    (Config.retrofit.create(StudentApi.class))
                                            .removeStudent(model.student.id, AppGlobals.userToken)
                                            .enqueue(new Callback<EmptyResult>() {
                                                @Override
                                                public void onResponse(Call<EmptyResult> call, Response<EmptyResult> response) {
                                                    dlg_progress.hide();
                                                    EmptyResult result = response.body();
                                                    if (result != null && result.err_code == 0) {
                                                        mStudentList.remove(position);
                                                        mRecyclerAdapter.notifyItemRemoved(position);
                                                        mRecyclerAdapter.notifyItemRangeChanged(position, mStudentList.size());

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
        final View view = LayoutInflater.from(instance).inflate(R.layout.cell_mh_student, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onLoadMore() {
        Log.e("iSemester", "onLoadMore");
        if (hasMoreData) {
            mStudentList.add(null);
            mRecyclerAdapter.notifyItemInserted(mStudentList.size() - 1);

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