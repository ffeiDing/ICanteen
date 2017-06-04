package com.netlab.vc.coursehelper;

import android.bluetooth.BluetoothAdapter;
import android.content.Intent;
import android.os.Bundle;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.app.AppCompatActivity;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;

import com.google.android.gms.appindexing.AppIndex;
import com.google.android.gms.common.api.GoogleApiClient;
import com.netlab.vc.coursehelper.util.jsonResults.Course;
import com.netlab.vc.coursehelper.util.jsonResults.SignInInfo;

import org.altbeacon.beacon.BeaconTransmitter;

/**
 * Created by dingfeifei on 2017/6/1.
 */

public class MenuActivity extends AppCompatActivity implements SwipeRefreshLayout.OnRefreshListener {
    private LinearLayout announcementList, testList, contentList, forumList, groupList;
    private TextView courseName, courseTeacher, courseDate, signUpedInfo, absenceInfo;//各种数据的Textview
    private Course course;//当前course的信息
    private Button signUp;//签到按钮
    private SwipeRefreshLayout refreshLayout;
    private ProgressBar progressBar;
    private ImageView courseImg;

    protected static final String TAG = "CourseActivity";//LOG用到的标记
    private String course_id;
    private String student_uuid;
    private SignInInfo signInInfo;
    private BluetoothAdapter.LeScanCallback mLeScanCallback;
    private BluetoothAdapter mBluetoothAdapter;
    private String deviceId;
    private String signinId;
    BeaconTransmitter beaconTransmitter;
    /**
     * ATTENTION: This was auto-generated to implement the App Indexing API.
     * See https://g.co/AppIndexing/AndroidStudio for more information.
     */
    private GoogleApiClient client;


    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        //requestWindowFeature(Window.FEATURE_CUSTOM_TITLE);
        setContentView(R.layout.activity_menu);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);//显示返回箭头
        Intent intent = getIntent();
        course_id = intent.getStringExtra("course_id");
        //Log.e("course_id", course_id);

        findViews();
        //getWindow().setFeatureInt(Window.FEATURE_CUSTOM_TITLE, R.layout.titlebar);
        setListeners();
        getData();
        // ATTENTION: This was auto-generated to implement the App Indexing API.
        // See https://g.co/AppIndexing/AndroidStudio for more information.
        client = new GoogleApiClient.Builder(this).addApi(AppIndex.API).build();
        //new RegisterTask().execute();
    }


    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // TODO Auto-generated method stub
        if (item.getItemId() == android.R.id.home) {
            finish();
            return true;
        }
        return super.onOptionsItemSelected(item);
    }

    private void findViews() {
        announcementList = (LinearLayout) findViewById(R.id.announcement_list);
        testList = (LinearLayout) findViewById(R.id.test_list);
        contentList = (LinearLayout) findViewById(R.id.content_list);
        forumList = (LinearLayout) findViewById(R.id.forum_list);
        refreshLayout = (SwipeRefreshLayout) findViewById(R.id.refreshLayout);
        progressBar = (ProgressBar) findViewById(R.id.load_progress);
    }

    private void setListeners() {
        announcementList.setOnClickListener(new LinearLayout.OnClickListener() {
            public void onClick(View v) {
                Intent intent = new Intent(MenuActivity.this, AnnouncementActivity.class);
                intent.putExtra("course_id",course_id);
                startActivity(intent);
            }
        });
        testList.setOnClickListener(new LinearLayout.OnClickListener() {
            public void onClick(View v) {
                Intent intent = new Intent(MenuActivity.this, TestListActivity.class);
                intent.putExtra("course_id", course_id);
                startActivity(intent);
            }
        });
        contentList.setOnClickListener(new LinearLayout.OnClickListener() {
            public void onClick(View v) {
                Intent intent = new Intent(MenuActivity.this, CourseContentActivity.class);
                intent.putExtra("course_id", course_id);
                startActivity(intent);
            }
        });
        forumList.setOnClickListener(new LinearLayout.OnClickListener() {
            public void onClick(View v) {

                Intent intent = new Intent(MenuActivity.this, ForumActivity.class);
                intent.putExtra("course_id", course_id);
                startActivity(intent);
            }
        });
        //refreshLayout.setOnRefreshListener(this);
    }

    private void refresh() {

        getData();
    }

    @Override
    public void onRefresh() {
        if (refreshLayout.isRefreshing()) {
//            ArrayList<NameValuePair> params = new ArrayList<>();
//            BasicNameValuePair valuesPair = new BasicNameValuePair("course_id", courseId);
//            params.add(valuesPair);
            refresh();
        }
    }
    public void getData() {
        return;
    }
}