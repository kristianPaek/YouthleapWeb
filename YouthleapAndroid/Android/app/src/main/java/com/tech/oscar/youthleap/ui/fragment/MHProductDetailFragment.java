package com.tech.oscar.youthleap.ui.fragment;

import android.annotation.SuppressLint;
import android.app.Dialog;
import android.support.design.widget.BottomSheetDialogFragment;
import android.view.View;
import android.widget.TextView;

import com.tech.oscar.youthleap.R;
import com.tech.oscar.youthleap.model.ProductModel;
import com.tech.oscar.youthleap.ui.view.MyImageView;
import com.tech.oscar.youthleap.util.DateTimeUtils;

public class MHProductDetailFragment extends BottomSheetDialogFragment {
    public static MHProductDetailFragment instance;
    // UI

    // Data
    ProductModel mModel;

    public static MHProductDetailFragment newInstance(ProductModel model) {
        MHProductDetailFragment fragment = new MHProductDetailFragment();
        fragment.mModel = model;

        return fragment;
    }

    @SuppressLint("RestrictedApi")
    @Override
    public void setupDialog(final Dialog dialog, int style) {
        super.setupDialog(dialog, style);

        View rootView = View.inflate(getContext(), R.layout.fragment_dlg_product, null);

        MyImageView img_avatar = rootView.findViewById(R.id.img_avatar);
        img_avatar.setImageResource(R.drawable.product);

        TextView txt_name = rootView.findViewById(R.id.txt_name);
        txt_name.setText(mModel.name);

        TextView txt_redeem = rootView.findViewById(R.id.txt_redeem);
        txt_redeem.setText(mModel.redeemPoint+"");

        TextView txt_category = rootView.findViewById(R.id.txt_category);
        txt_category.setText(mModel.categoryName);

        TextView txt_shortdesc = rootView.findViewById(R.id.txt_shortdesc);
        txt_shortdesc.setText(mModel.shortDesc);

        TextView txt_longdesc = rootView.findViewById(R.id.txt_longdesc);
        txt_longdesc.setText(mModel.longDesc);

        TextView txt_time = rootView.findViewById(R.id.txt_time);
        txt_time.setText(DateTimeUtils.dateToString(mModel.createdAt, DateTimeUtils.DEFAULT_FORMAT_TIME));

        dialog.setContentView(rootView);
    }
}