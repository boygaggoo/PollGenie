package com.musketeers.pollgenie.library;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.util.Log;

import com.musketeers.pollgenie.DashboardActivity;
import com.musketeers.pollgenie.GroupActivity;
import com.musketeers.pollgenie.JoinActivity;
import com.musketeers.pollgenie.LoginActivity;

public class MenuManager extends Activity{

	UserFunctions userFunctions;
	
	public MenuManager(){
	}
	
	public void logout(Context context){
    	userFunctions.logoutUser(context);
		Intent login = new Intent(context, LoginActivity.class);
    	login.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
    	startActivity(login);
    	// Closing dashboard screen
    	finish();
    }
    
    public void join_group(Context context) {
    	Intent join = new Intent(context, JoinActivity.class);
    	join.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
    	startActivity(join);
    	// Closing dashboard screen
    	finish();
    }
    
    public void all_groups(Context context) {
    	Intent i = new Intent(context, DashboardActivity.class);
    	i.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
    	startActivity(i);
    	// Closing dashboard screen
    	finish();
    }
    
    public void group(String group_id) {
    	Intent i = new Intent(getApplicationContext(), GroupActivity.class);
    	i.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
    	i.putExtra("group_id", group_id);
        Log.d("INTENT-GROUP-ID", "Intent extra for group_id set to "+group_id);
    	startActivity(i);
    	// Closing dashboard screen
    	finish();
    }
    
    public void poll_result(String group_id, String poll_id) {
    }
    
}
