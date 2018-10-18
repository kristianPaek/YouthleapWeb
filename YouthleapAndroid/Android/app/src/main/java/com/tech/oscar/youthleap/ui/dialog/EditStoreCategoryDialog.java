package com.tech.oscar.youthleap.ui.dialog;

import android.app.Dialog;
import android.content.Context;
import android.os.Bundle;
import android.support.design.widget.TextInputLayout;
import android.text.TextUtils;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.Window;
import android.widget.Button;
import android.widget.ProgressBar;

import com.tech.oscar.youthleap.AppGlobals;
import com.tech.oscar.youthleap.R;
import com.tech.oscar.youthleap.model.base.BaseStoreCategory;
import com.tech.oscar.youthleap.restapi.Config;
import com.tech.oscar.youthleap.restapi.EmptyResult;
import com.tech.oscar.youthleap.restapi.store.StoreApi;
import com.tech.oscar.youthleap.util.MessageUtil;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class EditStoreCategoryDialog extends Dialog implements OnClickListener {
    public static EditStoreCategoryDialog instance;
    // UI
    TextInputLayout til_name;
    ProgressBar progress;

    // Data
    public static BaseStoreCategory mCategory;

    public EditStoreCategoryDialog(Context context) {
        super(context);
        // TODO Auto-generated constructor stub
        requestWindowFeature(Window.FEATURE_NO_TITLE);
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        // TODO Auto-generated method stub
        super.onCreate(savedInstanceState);

        instance = this;
        setContentView(R.layout.dlg_edit_store_category);

        til_name = findViewById(R.id.til_name);
        progress = findViewById(R.id.progress);
        Button btn_edit = findViewById(R.id.btn_edit);

        if (mCategory == null) {
            btn_edit.setText(R.string.Add);
            til_name.getEditText().setText("");
        } else {
            btn_edit.setText(R.string.Edit);
            til_name.getEditText().setText(mCategory.name);
        }

        btn_edit.setOnClickListener(this);
    }


    @Override
    public void onClick(View v) {
        // TODO Auto-generated method stub
        switch (v.getId()) {
            case R.id.btn_edit: {
                if (isValid()) {
                    String id = "";
                    if (mCategory != null)
                        id = mCategory.id + "";

                    progress.setVisibility(View.VISIBLE);
                    (Config.retrofit.create(StoreApi.class))
                            .saveCategory(id, til_name.getEditText().getText().toString().trim(), AppGlobals.userToken)
                            .enqueue(new Callback<EmptyResult>() {
                                @Override
                                public void onResponse(Call<EmptyResult> call, Response<EmptyResult> response) {
                                    progress.setVisibility(View.GONE);

                                    EmptyResult result = response.body();
                                    if (result != null && result.err_code == 0) {
                                        onBackPressed();
                                        MessageUtil.showToast(getContext(), R.string.Saved, true);
                                    } else {
                                        MessageUtil.showToast(getContext(), result.err_msg, true);
                                    }
                                }

                                @Override
                                public void onFailure(Call<EmptyResult> call, Throwable t) {
                                    progress.setVisibility(View.GONE);
                                    MessageUtil.showToast(getContext(), t.getMessage(), true);
                                }
                            });
                }
            }
            break;
        }
    }

    private boolean isValid() {
        String name = til_name.getEditText().getText().toString().trim();
        if (TextUtils.isEmpty(name)) {
            til_name.setError(getContext().getString(R.string.invalid_category_name_empty));
            til_name.requestFocus();
            return false;
        } else {
            til_name.setError(null);
        }
        return true;
    }
}
