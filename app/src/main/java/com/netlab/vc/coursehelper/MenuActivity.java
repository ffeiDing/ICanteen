package com.netlab.vc.coursehelper;

import android.content.Intent;
import android.os.Bundle;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.app.AppCompatActivity;
import android.view.MenuItem;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.ProgressBar;

/**
 * Created by dingfeifei on 2017/6/1.
 */

public class MenuActivity extends AppCompatActivity{
    private LinearLayout displayList, testList, contentList, forumList;
    private SwipeRefreshLayout refreshLayout;
    private ProgressBar progressBar;



    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_menu);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);//显示返回箭头
        findViews();
        setListeners();
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
        displayList = (LinearLayout) findViewById(R.id.announcement_list);
        testList = (LinearLayout) findViewById(R.id.test_list);
        contentList = (LinearLayout) findViewById(R.id.content_list);
        forumList = (LinearLayout) findViewById(R.id.forum_list);
        refreshLayout = (SwipeRefreshLayout) findViewById(R.id.refreshLayout);
        progressBar = (ProgressBar) findViewById(R.id.load_progress);
    }

    private void setListeners() {
        displayList.setOnClickListener(new LinearLayout.OnClickListener() {
            public void onClick(View v) {
                Intent intent = new Intent(MenuActivity.this, AnnouncementActivity.class);
                startActivity(intent);
            }
        });
        testList.setOnClickListener(new LinearLayout.OnClickListener() {
            public void onClick(View v) {
                Intent intent = new Intent(MenuActivity.this, TestListActivity.class);
                startActivity(intent);
            }
        });
        contentList.setOnClickListener(new LinearLayout.OnClickListener() {
            public void onClick(View v) {
                Intent intent = new Intent(MenuActivity.this, CourseContentActivity.class);
                startActivity(intent);
            }
        });
        forumList.setOnClickListener(new LinearLayout.OnClickListener() {
            public void onClick(View v) {

                Intent intent = new Intent(MenuActivity.this, ForumActivity.class);
                startActivity(intent);
            }
        });
    }


}