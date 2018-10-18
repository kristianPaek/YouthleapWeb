package com.tech.oscar.youthleap.ui.fragment;

import android.annotation.SuppressLint;
import android.app.Dialog;
import android.content.DialogInterface;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Handler;
import android.os.Message;
import android.support.design.widget.BottomSheetDialogFragment;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import com.tech.oscar.youthleap.AppGlobals;
import com.tech.oscar.youthleap.R;
import com.tech.oscar.youthleap.model.base.BaseSubUser;
import com.tech.oscar.youthleap.restapi.Config;
import com.tech.oscar.youthleap.restapi.EmptyResult;
import com.tech.oscar.youthleap.restapi.user.UserApi;
import com.tech.oscar.youthleap.ui.activity.BaseActionBarActivity;
import com.tech.oscar.youthleap.util.Base64Util;
import com.tech.oscar.youthleap.util.ResourceUtil;

import java.io.File;
import java.util.Timer;
import java.util.TimerTask;

import android_serialport_api.AsyncFingerprint;
import android_serialport_api.SerialPortManager;
import okhttp3.MediaType;
import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;


public class FingerprintFragment extends BottomSheetDialogFragment {
    public static FingerprintFragment instance;
    // UI
    TextView txt_status;
    ImageView img_fingerprint;
    TextView txt_keychar;
    Button btn_ok;
    ProgressBar progress;

    // Data
    BaseActionBarActivity mActivity;

    private BaseSubUser mUser;

    private AsyncFingerprint vFingerprint;
    private boolean bIsCancel = false;
    private boolean bfpWork = false;
    private Timer startTimer;
    private TimerTask startTask;
    private Handler startHandler;

    private String fingerprintImageName = "fingerprint.png";
    private byte[] fingerprintData;


    public static FingerprintFragment newInstance(BaseActionBarActivity activity, BaseSubUser user) {
        FingerprintFragment fragment = new FingerprintFragment();
        fragment.mUser = user;
        fragment.mActivity = activity;

        return fragment;
    }

    @SuppressLint("RestrictedApi")
    @Override
    public void setupDialog(final Dialog dialog, int style) {
        super.setupDialog(dialog, style);

        View rootView = View.inflate(mActivity, R.layout.fragment_fingerprint, null);

        txt_status = rootView.findViewById(R.id.txt_status);
        img_fingerprint = rootView.findViewById(R.id.img_fingerprint);
        txt_keychar = rootView.findViewById(R.id.txt_keychar);
        btn_ok = rootView.findViewById(R.id.btn_ok);
        progress = rootView.findViewById(R.id.progress);

        btn_ok.setEnabled(false);
        btn_ok.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                RequestBody user_id = RequestBody.create(MediaType.parse("text/plain"), mUser.id+"");
                RequestBody token = RequestBody.create(MediaType.parse("text/plain"), AppGlobals.userToken);

                File file = new File(ResourceUtil.RES_DIRECTORY + fingerprintImageName);
                RequestBody reqFile = RequestBody.create(MediaType.parse("image/*"), file);
                MultipartBody.Part body = MultipartBody.Part.createFormData("finger_image", file.getName(), reqFile);

                RequestBody finger_data = RequestBody.create(MediaType.parse("text/plain"), Base64Util.fromByte(fingerprintData));

                progress.setVisibility(View.VISIBLE);
                (Config.retrofit.create(UserApi.class))
                        .saveFingerPrint(user_id, token, body, finger_data)
                        .enqueue(new Callback<EmptyResult>() {
                            @Override
                            public void onResponse(Call<EmptyResult> call, Response<EmptyResult> response) {
                                progress.setVisibility(View.GONE);
                            }

                            @Override
                            public void onFailure(Call<EmptyResult> call, Throwable t) {
                                progress.setVisibility(View.GONE);
                            }
                        });
            }
        });

        //Fingerprint
        vFingerprint = SerialPortManager.getInstance().getNewAsyncFingerprint();
        FPInit();
        FPProcess();

        dialog.setContentView(rootView);
    }

    private void FPInit() {
        vFingerprint.setOnGetImageListener(new AsyncFingerprint.OnGetImageListener() {
            @Override
            public void onGetImageSuccess() {
                if (AppGlobals.IsUpImage) {
                    vFingerprint.FP_UpImage();
                    txt_status.setText(getString(R.string.txt_fpdisplay));
                } else {
                    txt_status.setText(getString(R.string.txt_fpprocess));
                    vFingerprint.FP_GenChar(1);
                }
            }

            @Override
            public void onGetImageFail() {
                if (!bIsCancel) {
                    vFingerprint.FP_GetImage();
                } else {
                    Toast.makeText(mActivity, "Cancel OK", Toast.LENGTH_SHORT).show();
                }
            }
        });

        vFingerprint.setOnUpImageListener(new AsyncFingerprint.OnUpImageListener() {
            @Override
            public void onUpImageSuccess(byte[] data) {
                Bitmap image = BitmapFactory.decodeByteArray(data, 0, data.length);
                img_fingerprint.setImageBitmap(image);
                txt_status.setText(getString(R.string.txt_fpprocess));
                vFingerprint.FP_GenChar(1);

                ResourceUtil.SaveToBitmap(image, fingerprintImageName);
            }

            @Override
            public void onUpImageFail() {
                bfpWork = false;
                TimerStart();
            }
        });

        vFingerprint.setOnGenCharListener(new AsyncFingerprint.OnGenCharListener() {
            @Override
            public void onGenCharSuccess(int bufferId) {
                txt_status.setText(getString(R.string.txt_fpidentify));
                vFingerprint.FP_UpChar();
            }

            @Override
            public void onGenCharFail() {
                txt_status.setText(getString(R.string.txt_fpfail));
            }
        });

        vFingerprint.setOnUpCharListener(new AsyncFingerprint.OnUpCharListener() {

            @Override
            public void onUpCharSuccess(byte[] model) {
                fingerprintData = model;
                if (fingerprintData != null) {
                    try {
                        String str = "";
                        for (int i = 0; i < model.length; i++)
                            str += Byte.toString(model[i])+",";
                        txt_keychar.setText(str);
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                    btn_ok.setEnabled(true);
                }

//                    if (GlobalData.getInstance().userList.get(i).bytes1 != null) {
//                        System.arraycopy(GlobalData.getInstance().userList.get(i).bytes1, 0, tmp, 0, 256);
//                        if (FPMatch.getInstance().MatchTemplate(model, tmp) > 60) {
//                            AddPersonItem(GlobalData.getInstance().userList.get(i));
//                            txt_status.setText(getString(R.string.txt_fpmatchok));
//                            break;
//                        }
//                        System.arraycopy(GlobalData.getInstance().userList.get(i).bytes1, 256, tmp, 0, 256);
//                        if (FPMatch.getInstance().MatchTemplate(model, tmp) > 60) {
//                            AddPersonItem(GlobalData.getInstance().userList.get(i));
//                            txt_status.setText(getString(R.string.txt_fpmatchok));
//                            break;
//                        }
//                    }
//                    if (GlobalData.getInstance().userList.get(i).bytes2 != null) {
//                        System.arraycopy(GlobalData.getInstance().userList.get(i).bytes2, 0, tmp, 0, 256);
//                        if (FPMatch.getInstance().MatchTemplate(model, tmp) > 60) {
//                            AddPersonItem(GlobalData.getInstance().userList.get(i));
//                            txt_status.setText(getString(R.string.txt_fpmatchok));
//                            break;
//                        }
//                        System.arraycopy(GlobalData.getInstance().userList.get(i).bytes2, 256, tmp, 0, 256);
//                        if (FPMatch.getInstance().MatchTemplate(model, tmp) > 60) {
//                            AddPersonItem(GlobalData.getInstance().userList.get(i));
//                            txt_status.setText(getString(R.string.txt_fpmatchok));
//                            break;
//                        }
//                    }
//                }

                bfpWork = false;
                TimerStart();
            }

            @Override
            public void onUpCharFail() {
                txt_status.setText(getString(R.string.txt_fpmatchfail) + ":-1");
                bfpWork = false;
                TimerStart();
            }
        });
    }

    private void FPProcess() {
        if (!bfpWork) {
            try {
                Thread.currentThread();
                Thread.sleep(500);
            } catch (InterruptedException e) {
                e.printStackTrace();
            }
            txt_status.setText(getString(R.string.txt_fpplace));
            vFingerprint.FP_GetImage();
            bfpWork = true;
        }
    }

    public void TimerStart() {
        if (bIsCancel)
            return;
        if (startTimer == null) {
            startTimer = new Timer();
            startHandler = new Handler() {
                @Override
                public void handleMessage(Message msg) {
                    super.handleMessage(msg);

                    TimeStop();
                    FPProcess();
                }
            };
            startTask = new TimerTask() {
                @Override
                public void run() {
                    Message message = new Message();
                    message.what = 1;
                    startHandler.sendMessage(message);
                }
            };
            startTimer.schedule(startTask, 1000, 1000);
        }
    }

    public void TimeStop() {
        if (startTimer != null) {
            startTimer.cancel();
            startTimer = null;
            startTask.cancel();
            startTask = null;
        }
    }

    @Override
    public void onDismiss(DialogInterface dialog) {
        if (SerialPortManager.getInstance().isOpen()) {
            bIsCancel = true;
            SerialPortManager.getInstance().closeSerialPort();
        }
        super.onDismiss(dialog);
    }
}