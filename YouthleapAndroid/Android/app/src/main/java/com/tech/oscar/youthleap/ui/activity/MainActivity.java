package com.tech.oscar.youthleap.ui.activity;

import android.app.AlertDialog;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.IntentFilter;
import android.os.Bundle;
import android.os.Handler;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.TextView;

import com.tech.oscar.youthleap.AppConstant;
import com.tech.oscar.youthleap.AppGlobals;
import com.tech.oscar.youthleap.AppPreferences;
import com.tech.oscar.youthleap.R;
import com.tech.oscar.youthleap.model.UserModel;
import com.tech.oscar.youthleap.restapi.Config;
import com.tech.oscar.youthleap.restapi.user.GetProfileResult;
import com.tech.oscar.youthleap.restapi.user.UserApi;
import com.tech.oscar.youthleap.ui.fragment.MainEventFragment;
import com.tech.oscar.youthleap.ui.fragment.MainManageAccountsFragment;
import com.tech.oscar.youthleap.ui.fragment.MainMoodFragment;
import com.tech.oscar.youthleap.ui.fragment.MainOnlineStoreFragment;
import com.tech.oscar.youthleap.ui.fragment.MainVideoFragment;
import com.tech.oscar.youthleap.ui.fragment.MainWalletFragment;
import com.tech.oscar.youthleap.ui.view.MyAvatarImageView;
import com.tech.oscar.youthleap.util.CommonUtil;
import com.tech.oscar.youthleap.util.DeviceUtil;
import com.tech.oscar.youthleap.util.MessageUtil;

import me.tangke.slidemenu.SlideMenu;
import me.tangke.slidemenu.SlideMenu.OnSlideStateChangeListener;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class MainActivity extends BaseActionBarActivity implements OnClickListener {

    private static final String TAG = MainActivity.class.getSimpleName() + "Actions";
    public static MainActivity instance = null;
    static final int MENU_COUNT = 7;

    // UI
    public SlideMenu mSlideMenu;
    View mSlideView;
    View menuView[] = new View[MENU_COUNT];
    View lineView[] = new View[MENU_COUNT-1];

    // Data
    public static UserModel mUser;
    Fragment mCurrentFragment;
    int mCurrentFragmentIndex = -1;
    int mFirstFragmentIndex = AppConstant.SW_FRAGMENT_MAIN_HOME;

    private final BroadcastReceiver mReceiver = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {
            String action = intent.getAction();
            doAction(action);
        }
    };

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        instance = this;
        setContentView(R.layout.activity_main);

        mSlideMenu = findViewById(R.id.slideMenu);
        mSlideView = getLayoutInflater().inflate(R.layout.slidemenu_main_left, mSlideMenu, true);
        mSlideMenu.setEdgeSlideEnable(true);

        mSlideMenu.setSlideMode(SlideMenu.MODE_SLIDE_WINDOW);
        mSlideMenu.setOnSlideStateChangeListener(new OnSlideStateChangeListener() {
            @Override
            public void onSlideStateChange(int slideState) {
                if (slideState == SlideMenu.STATE_OPEN_LEFT)
                    CommonUtil.hideKeyboard(instance, mSlideView);
            }

            @Override
            public void onSlideOffsetChange(float offsetPercent) {
            }
        });

        menuView[0] = findViewById(R.id.menu_manage_account);
        menuView[1] = findViewById(R.id.menu_wallet);
        menuView[2] = findViewById(R.id.menu_video);
        menuView[3] = findViewById(R.id.menu_store);
        menuView[4] = findViewById(R.id.menu_mood);
        menuView[5] = findViewById(R.id.menu_attendance);
        menuView[6] = findViewById(R.id.menu_logout);

        lineView[0] = findViewById(R.id.line_manage_account);
        lineView[1] = findViewById(R.id.line_wallet);
        lineView[2] = findViewById(R.id.line_video);
        lineView[3] = findViewById(R.id.line_store);
        lineView[4] = findViewById(R.id.line_mood);
        lineView[5] = findViewById(R.id.line_attendance);

        if (mUser.getUserType() == UserModel.TYPE_ADMIN) {
            mFirstFragmentIndex = AppConstant.SW_FRAGMENT_MAIN_HOME;
        } else if (mUser.getUserType() == UserModel.TYPE_SCHOOL) {
            mFirstFragmentIndex = AppConstant.SW_FRAGMENT_MAIN_HOME;
            menuView[2].setVisibility(View.GONE);
            lineView[2].setVisibility(View.GONE);
        } else if (mUser.getUserType() == UserModel.TYPE_TUTOR) {
            mFirstFragmentIndex = AppConstant.SW_FRAGMENT_MAIN_VIDEO;
            menuView[0].setVisibility(View.GONE);
            lineView[0].setVisibility(View.GONE);
            menuView[1].setVisibility(View.GONE);
            lineView[1].setVisibility(View.GONE);
            menuView[3].setVisibility(View.GONE);
            lineView[3].setVisibility(View.GONE);
            menuView[5].setVisibility(View.GONE);
            lineView[5].setVisibility(View.GONE);
        } else if (mUser.getUserType() == UserModel.TYPE_STUDENT) {
            mFirstFragmentIndex = AppConstant.SW_FRAGMENT_MAIN_WALLET;
            menuView[0].setVisibility(View.GONE);
            lineView[0].setVisibility(View.GONE);
            menuView[3].setVisibility(View.GONE);
            lineView[3].setVisibility(View.GONE);
        } else if (mUser.getUserType() == UserModel.TYPE_PARENT) {
            mFirstFragmentIndex = AppConstant.SW_FRAGMENT_MAIN_WALLET;
            menuView[0].setVisibility(View.GONE);
            lineView[0].setVisibility(View.GONE);
            menuView[2].setVisibility(View.GONE);
            lineView[2].setVisibility(View.GONE);
            menuView[3].setVisibility(View.GONE);
            lineView[3].setVisibility(View.GONE);
            menuView[5].setVisibility(View.GONE);
            lineView[5].setVisibility(View.GONE);
        }

        for (int i = 0; i < MENU_COUNT; i++)
            menuView[i].setOnClickListener(this);

        // set User info
        findViewById(R.id.layout_profile).setOnClickListener(this);
        showUserInfo();

        // show first fragment
        SwitchContent(mFirstFragmentIndex, null);

        // add broadcasting receiver
        IntentFilter filter = new IntentFilter();
        filter.addAction(AppConstant.ACTION_CHANGED_MY_PROFILE);
        filter.addAction(AppConstant.ACTION_ADDED_EVENT);
        filter.addAction(AppConstant.ACTION_CHANGED_INTESTING_EVENT);
        registerReceiver(mReceiver, filter);
    }

    @Override
    public void onClick(View view) {
        switch (view.getId()) {
            case R.id.layout_profile: {
                Intent intent = null;
                if (mUser.getUserType() == UserModel.TYPE_SCHOOL) {
                    intent = new Intent(instance, EditSchoolProfileActivity.class);
                    EditSchoolProfileActivity.mUser = mUser;
                }
                else if (mUser.getUserType() == UserModel.TYPE_TUTOR) {
                    intent = new Intent(instance, EditTutorProfileActivity.class);
                    EditTutorProfileActivity.mUser = mUser;
                }
                else if (mUser.getUserType() == UserModel.TYPE_STUDENT) {
                    intent = new Intent(instance, EditStudentProfileActivity.class);
                    EditStudentProfileActivity.mUser = mUser;
                }
                else if (mUser.getUserType() == UserModel.TYPE_PARENT) {
                    intent = new Intent(instance, EditParentProfileActivity.class);
                    EditParentProfileActivity.mUser = mUser;
                }

                if (intent != null) {
                    startActivity(intent);
                }
            }
            break;

            case R.id.menu_manage_account:
                selectMenu(0);
                break;
            case R.id.menu_wallet:
                selectMenu(1);
                break;
            case R.id.menu_video:
                selectMenu(2);
                break;
            case R.id.menu_store:
                selectMenu(3);
                break;
            case R.id.menu_mood:
                selectMenu(4);
                break;
            case R.id.menu_attendance:
                selectMenu(5);
                break;
            case R.id.menu_logout:
                selectMenu(6);
                break;
        }
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home: {
                if (DeviceUtil.isNetworkAvailable(instance)) {
                    if (mSlideMenu.isOpen())
                        mSlideMenu.close(true);
                    else
                        mSlideMenu.open(false, true);
                } else {
                    ErrorNetworkActivity.OpenMe();
                }
                return true;
            }

            default:
                return super.onOptionsItemSelected(item);
        }
    }

    @Override
    public void onBackPressed() {
        onBackButtonPressed();
    }

    boolean isBackAllowed = false;

    private void onBackButtonPressed() {
        if (mSlideMenu.isOpen()) {
            mSlideMenu.close(true);
            return;
        }
        if (!isBackAllowed) {
            MessageUtil.showToast(instance, R.string.msg_alert_on_back_pressed);
            isBackAllowed = true;

            new Handler().postDelayed(new Runnable() {
                @Override
                public void run() {
                    isBackAllowed = false;
                }
            }, AppConstant.DELAY_EXIT);

        } else {
            finish();
        }
    }

    @Override
    protected void onResume() {
        super.onResume();

        if (isErrorOccured)
            return;
    }

    @Override
    protected void onPause() {
        if (mSlideMenu.isOpen())
            mSlideMenu.close(true);

        super.onPause();
    }

    @Override
    protected void onDestroy() {
        unregisterReceiver(mReceiver);
        super.onDestroy();
    }

    public void SwitchContent(int fragment_index, Bundle bundle) {
        // update the main content by replacing fragments
        if (mCurrentFragmentIndex != fragment_index) {
            mCurrentFragmentIndex = fragment_index;
            if (mCurrentFragmentIndex == AppConstant.SW_FRAGMENT_MAIN_HOME) {
                mCurrentFragment = MainManageAccountsFragment.newInstance();
            } else if (mCurrentFragmentIndex == AppConstant.SW_FRAGMENT_MAIN_WALLET) {
                mCurrentFragment = MainWalletFragment.newInstance(mUser.subUser);
            } else if (mCurrentFragmentIndex == AppConstant.SW_FRAGMENT_MAIN_VIDEO) {
                mCurrentFragment = MainVideoFragment.newInstance();
            } else if (mCurrentFragmentIndex == AppConstant.SW_FRAGMENT_MAIN_ONLINE_STORE) {
                mCurrentFragment = MainOnlineStoreFragment.newInstance();
            } else if (mCurrentFragmentIndex == AppConstant.SW_FRAGMENT_MAIN_MOOD) {
                mCurrentFragment = MainMoodFragment.newInstance();
            } else if (mCurrentFragmentIndex == AppConstant.SW_FRAGMENT_MAIN_ATTENDENCE) {
                mCurrentFragment = MainEventFragment.newInstance();
            }

            if (mCurrentFragment != null) {
                try {
                    if (bundle != null)
                        mCurrentFragment.setArguments(bundle);
                    FragmentManager fragmentManager = this.getSupportFragmentManager();
                    fragmentManager.beginTransaction().replace(R.id.main_content, mCurrentFragment).commitAllowingStateLoss();

                } catch (Exception e) {
                    e.printStackTrace();
                }
            }
        }

        if (mSlideMenu.isOpen())
            mSlideMenu.close(true);
    }

    @SuppressWarnings("deprecation")
    public void selectMenu(int index) {
        if (!DeviceUtil.isNetworkAvailable(instance)) {
            ErrorNetworkActivity.OpenMe();
            return;
        }

        for (int i = 0; i < MENU_COUNT; i++) {
            menuView[i].setBackgroundColor(getResources().getColor(R.color.transparent));
        }

        menuView[index].setBackgroundResource(R.color.drawer_item_selected_background);

        switch (index) {
            case 0:
                SwitchContent(AppConstant.SW_FRAGMENT_MAIN_HOME, null);
                break;
            case 1:
                SwitchContent(AppConstant.SW_FRAGMENT_MAIN_WALLET, null);
                break;
            case 2:
                SwitchContent(AppConstant.SW_FRAGMENT_MAIN_VIDEO, null);
                break;
            case 3:
                SwitchContent(AppConstant.SW_FRAGMENT_MAIN_ONLINE_STORE, null);
                break;
            case 4:
                SwitchContent(AppConstant.SW_FRAGMENT_MAIN_MOOD, null);
                break;
            case 5:
                SwitchContent(AppConstant.SW_FRAGMENT_MAIN_ATTENDENCE, null);
                break;
            case 6:
                Logout();
                break;
            default:
                Log.e(TAG, "Unknown menu index");
        }
    }

    public void Logout() {
        new AlertDialog.Builder(instance)
                .setMessage(R.string.dialog_logout)
                .setPositiveButton(R.string.YES, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        dlg_progress.show();
                        new Handler().postDelayed(new Runnable() {
                            @Override
                            public void run() {
                                dlg_progress.hide();

                                // remove login information
                                AppPreferences.removeKey(AppPreferences.KEY.REMEMBER);
                                AppPreferences.removeKey(AppPreferences.KEY.SIGN_IN_USER_EMAIL);
                                AppPreferences.removeKey(AppPreferences.KEY.SIGN_IN_PASSWORD);

                                finish();
                                Intent intent = new Intent(instance, LoginActivity.class);
                                startActivity(intent);
                                overridePendingTransition(R.anim.in_right, R.anim.out_right);
                            }
                        }, AppConstant.DELAY_EXIT);
                    }
                })
                .setNegativeButton(R.string.NO, null)
                .show();
    }

    private void showUserInfo() {
        MyAvatarImageView img_avatar = findViewById(R.id.img_avatar);
        img_avatar.showImage(mUser.subUser.getImage());
        TextView txt_full_name = findViewById(R.id.txt_full_name);
        txt_full_name.setText(mUser.getFullName());

        TextView txt_email = findViewById(R.id.txt_email);
        txt_email.setText(mUser.user.email);

        TextView txt_school = findViewById(R.id.txt_school);
        TextView txt_type = findViewById(R.id.txt_type);
        txt_school.setVisibility(View.GONE);
        if (mUser.getUserType() == UserModel.TYPE_ADMIN) {
            txt_type.setText(R.string.user_type_admin);
        }
        else if (mUser.getUserType() == UserModel.TYPE_SCHOOL) {
            txt_type.setText(R.string.user_type_school);
        }
        else if (mUser.getUserType() == UserModel.TYPE_TUTOR) {
            txt_school.setVisibility(View.VISIBLE);
            txt_school.setText(mUser.getSchoolName());
            txt_type.setText(R.string.user_type_tutor);
        }
        else if (mUser.getUserType() == UserModel.TYPE_PARENT) {
            txt_school.setVisibility(View.VISIBLE);
            txt_school.setText(mUser.getSchoolName());
            txt_type.setText(R.string.user_type_parent);
        }
        else {
            txt_school.setVisibility(View.VISIBLE);
            txt_school.setText(mUser.getSchoolName());
            txt_type.setText(R.string.user_type_student);
        }
    }

    private void doAction(String action) {
        if (action.equals(AppConstant.ACTION_CHANGED_MY_PROFILE)) {
            (Config.retrofit.create(UserApi.class))
                    .getProfile(mUser.getUserId(), AppGlobals.userToken)
                    .enqueue(new Callback<GetProfileResult>() {
                        @Override
                        public void onResponse(Call<GetProfileResult> call, Response<GetProfileResult> response) {
                            GetProfileResult result = response.body();
                            if (result != null && result.err_code == 0) {
                                mUser.parse(result);
                                showUserInfo();
                            }
                        }
                        @Override
                        public void onFailure(Call<GetProfileResult> call, Throwable t) {}
                    });
        }
    }
}
