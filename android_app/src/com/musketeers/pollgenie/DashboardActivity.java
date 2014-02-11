/**
 * Author: Tapiwa Maruni
 * URL: www.droid-maruni.rhcloud.com
 * */
package com.musketeers.pollgenie;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.ListActivity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.ListAdapter;
import android.widget.ListView;
import android.widget.SimpleAdapter;
import android.widget.TextView;

import com.musketeers.pollgenie.library.GroupFunctions;
import com.musketeers.pollgenie.library.MenuManager;
import com.musketeers.pollgenie.library.UserFunctions;
import com.musketeers.pollme.R;

public class DashboardActivity extends ListActivity {
	UserFunctions userFunctions;
	GroupFunctions groupFunc;
	JSONObject groups;
	ProgressDialog pDialog;
	MenuManager menu;
	
	Button btnLogout;
	Button btnJoin;
	ListView groupListView;
	
	private static String KEY_SUCCESS = "success";
	private static String KEY_ERROR = "error";
	private static String KEY_ERROR_MSG = "error_msg";
	private static String KEY_GROUPS = "groups";
	
	
	// List to handle all groups 
	List g; 
	
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        
        /**
         * Dashboard Screen for the application
         * */        
        // Check login status in database
        userFunctions = new UserFunctions();
        g = new ArrayList<HashMap<String, String>>();
        
        if(userFunctions.isUserLoggedIn(getApplicationContext())){
        	
        	Log.d("LOGGED-IN", "User is logged in trying setting logout button listeners");
        	// Build Layout here
        	
        	Log.d("GRAB-LAYOUT", "Grabbing dashboard layout");
        	setContentView(R.layout.dashboard);
        	Log.d("LAYOUT-GRABBED", "dashboard layout");
        	
        	
        	// Grab groups from online and display
        	Log.d("LOADING GROUPS", "Running Async Task");
        	new LoadGroups().execute();
            
            // get listview
            ListView lv = getListView();
            
            /**
             * Listview item click listener
             * GroupListActivity will be lauched by passing group id
             * */
            lv.setOnItemClickListener(new android.widget.AdapterView.OnItemClickListener() {
                public void onItemClick(AdapterView<?> arg0, View view, int arg2,
                        long arg3) {
                    // on selecting a single group
                    // GroupActivity will be launched to show polls inside the group
                    Intent i = new Intent(getApplicationContext(), GroupActivity.class);
                     
                    // send group id to grouplist activity to get list of polls under that group
                    String group_id = ( (TextView) view.findViewById(R.id.group_id)).getText().toString();
                    i.putExtra("group_id", group_id);
                    Log.d("INTENT-GROUP-ID", "Intent extra for group_id set to "+group_id);
                     
                    startActivity(i);
                }
            });     
            
        }else{
        	Log.e("LOGIN-FAIL", "User not logged in.");
        	
        	// user is not logged in show login screen
        	Intent login = new Intent(getApplicationContext(), LoginActivity.class);
        	login.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        	startActivity(login);
        	// Closing dashboard screen
        	finish();
        }
    }
    
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.main_menu, menu);
        return true;
    }
    
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
    	menu = new MenuManager();
    	
        // Handle item selection
        switch (item.getItemId()) {
            case R.id.logout:
                logout( );
            case R.id.join:
            	join_group( );
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }
    }
    
    private void logout(){
    	userFunctions.logoutUser(getApplicationContext());
		Intent login = new Intent(getApplicationContext(), LoginActivity.class);
    	login.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
    	startActivity(login);
    	// Closing dashboard screen
    	finish();
    }
    
    private void join_group() {
    	Intent join = new Intent(getApplicationContext(), JoinActivity.class);
    	join.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
    	startActivity(join);
    	// Closing dashboard screen
    	finish();
    }
    
    /**
     * Background Async Task to Load all Albums by making http request
     * */
    class LoadGroups extends AsyncTask<String, String, String> {
 
        /**
         * Before starting background thread Show Progress Dialog
         * */
        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            pDialog = new ProgressDialog(DashboardActivity.this);
            pDialog.setMessage("Listing Groups ...");
            pDialog.setIndeterminate(false);
            pDialog.setCancelable(false);
            pDialog.show();
        }
 
        /**
         * getting Groups JSON
         * */
        protected String doInBackground(String... args) {
        	getGroups();
            return null;
        }
 
        /**
         * After completing background task Dismiss the progress dialog
         * **/
        protected void onPostExecute(String file_url) {
            // dismiss the dialog after getting all albums
            pDialog.dismiss();
            // updating UI from Background Thread
            runOnUiThread(new Runnable() {
                public void run() {
                    /**
                     * Updating parsed JSON data into ListView
                     * */
                	Log.d("UPDATING-LV", "Updating list-view components");
                	ListAdapter adapter = new SimpleAdapter(
                            DashboardActivity.this, g,
                            R.layout.new_group_view, new String[] { "id",
                                    "name", "owner" }, new int[] {
                                    R.id.group_id, R.id.group_name, R.id.group_owner });
                     
                    // updating listview
                    setListAdapter(adapter);
                }
            });
        }
    }
    
 // Query API to get groups and return the size
    private int getGroups(){
    	
    	groupFunc = new GroupFunctions();
    	groups = groupFunc.all_groups(getApplicationContext());
    	
    	try{
    		// Check if we have a successful request
    		if( groups.getString(KEY_SUCCESS) != null ){
    			Log.d("GROUP SUCCESS", "API returned success, trying to parse.");
    			
    			String res = groups.getString(KEY_SUCCESS); 
    			if(Integer.parseInt(res) == 1){
    				// reset the groups array and re-populate 
    				g.clear();
    				
    				// grab the JSON groups
    				JSONArray json_groups = groups.getJSONArray("groups");
    				Log.d("GROUPS-ARRAY", json_groups.toString() );
    				
    				if(json_groups.length() > 0){
    					// We have some groups so populate layout
    					for (int i = 0; i < json_groups.length(); i++) {
    					    JSONObject row = json_groups.getJSONObject(i);
    					    // add to group object, then add to the list
    					    HashMap<String, String> map = new HashMap<String, String>();
    					    map.put("id", row.getString("id"));
    					    map.put("name", row.getString("name"));
    					    map.put("owner", row.getString("owner"));
    					    g.add(map);
    					    
    					    Log.d("GROUP-OBJECT-ADDITION", "Group "+map.get("name")+" added to list.");
    					}
    				} else {
    					Log.e("GROUPS-EMPTY", "No groups return/noticed.");
    					// No groups yet so leave blank or start new add group activity
    				}
    			} else {
    				Log.e("API-FAIL", "API failed to return success == 1.");
    				// This is if success isn't 1... assume no groups
    			}
    		} else {
    			Log.e("API-FAIL", "API failed to return success.");
    		}
    		
    	} catch (JSONException e) {
			e.printStackTrace();
		}
    	
    	return g.size();
    }
    
}