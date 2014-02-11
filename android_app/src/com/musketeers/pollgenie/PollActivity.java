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

import android.content.DialogInterface;
import android.app.AlertDialog;
import android.app.ListActivity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ListAdapter;
import android.widget.ListView;
import android.widget.SimpleAdapter;
import android.widget.TextView;

import com.musketeers.pollgenie.library.AlertDialogManager;
import com.musketeers.pollgenie.library.GroupFunctions;
import com.musketeers.pollgenie.library.UserFunctions;
import com.musketeers.pollme.R;

public class PollActivity extends ListActivity {
	UserFunctions userFunctions;
	GroupFunctions groupFunc;
	JSONObject poll;
	JSONObject submit;
	ProgressDialog pDialog;
	AlertDialog.Builder alert;
	
	Button btnLogout;
	Button btnBack;
	ListView choiceListView;
	TextView quesV;
	
	private static String KEY_SUCCESS = "success";
	private static String KEY_ERROR = "error";
	private static String KEY_ERROR_MSG = "error_msg";
	private static String KEY_GROUPS = "groups";
	
	
	// List to handle all polls 
	List g; 
	String poll_id="", group_id="", question="", ans="";
	
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        
        /**
         * Dashboard Screen for the application
         * */        
        // Check login status in database
        userFunctions = new UserFunctions();
        g = new ArrayList<HashMap<String, String>>();
        
        // Get group id
        Intent i = getIntent();
        poll_id = i.getStringExtra("poll_id");
        group_id = i.getStringExtra("group_id");
        question = i.getStringExtra("question");
        Log.d("POLL-GROUP-ID", "Poll AND group ID set from intent: "+poll_id+"-"+group_id);
        Log.d("POLL-QUES-FROM-INTENT", "Question from intent: "+question);
        
        
        if(userFunctions.isUserLoggedIn(getApplicationContext())){
        	
        	Log.d("LOGGED-IN", "User is logged in");
        	// Build Layout here
        	
        	Log.d("GRAB-POLL-LAYOUT", "Grabbing group layout");
        	setContentView(R.layout.poll);
        	Log.d("LAYOUT-GRABBED", "group layout");
        	
        	
        	// Grab groups from online and display
        	Log.d("LOADING POLL CHOICES", "Running Async Task -  Trying to get the poll from online");
        	new LoadChoices().execute();
            
            // get listview
            ListView lv = getListView();
            
            /**
             * Listview item click listener
             * Poll submission the back to group
             * */
            lv.setOnItemClickListener(new android.widget.AdapterView.OnItemClickListener() {
                public void onItemClick(AdapterView<?> arg0, View view, int arg2,
                        long arg3) {
                    
                	// submit poll here
                	ans = ( (TextView) view.findViewById(R.id.poll_id)).getText().toString();
                	if( submit_poll() ){
                		Log.d("SUCCESS-SUBMIT", "Displaying dialog and openning group activity");
                		alert = new AlertDialog.Builder(PollActivity.this);
                        alert.setTitle("Submitted Poll");
                        alert.setMessage("Sucecessfully submit your response");
                        AlertDialog a = alert.create();
                        a.show();
                	} else {
                		Log.e("FAILED-SUBMIT", "Displaying dialog and going back to group activity");
                		alert = new AlertDialog.Builder(PollActivity.this);
                        alert.setTitle("Failed Poll");
                        alert.setMessage("Did not submit your response!");
                        AlertDialog a = alert.create();
                        a.show();
                	}
                	
                    // GroupActivity will be launched after answer submitted
                    Intent i = new Intent(getApplicationContext(), GroupActivity.class);
                    
                    // Once submitted, go back to group
                    i.putExtra("group_id", group_id);
                    Log.d("INTENT-GROUP-ID", "Intent extra for group_id set to "+group_id);
                    
                    Log.d("POLL_ACTIVITY-START", "Start poll");
                    startActivity(i);
                }
            }); 
            
        	// Set Question
        	quesV = (TextView) findViewById(R.id.pollQuesV);
            Log.d("VIEW-QUESTION-SET-TEXT", "Setting question: "+question);
            quesV.setText(question);
            Log.d("SET-TEXT-COMPLETE", (String) quesV.getText());
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
        inflater.inflate(R.menu.poll_menu, menu);
        return true;
    }
    
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle item selection
        switch (item.getItemId()) {
            case R.id.logout:
                logout();
            case R.id.groups:
                all_groups();
                return true;
            case R.id.group:
                group();
                return true;
             case R.id.result:
                poll_result();
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
    	Log.i("ACTIVITY-OPEN", "Logout-> Login Activity");
    	// Closing dashboard screen
    	finish();
    }
    
    private void poll_result(){
    	// create the URL corresponding to the touched Button's query
    	
        String urlString = getString(R.string.resultURL) + poll_id;

        // create an Intent to launch a web browser    
        // Intent webIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(urlString));                  
        // startActivity(webIntent); // execute the Intent
        
        Uri url = Uri.parse(urlString);
        Intent i = new Intent(Intent.ACTION_VIEW);
        i.setData(url);
        startActivity(i);
    }
    
    private void all_groups() {
    	Intent groups = new Intent(getApplicationContext(), DashboardActivity.class);
    	groups.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
    	Log.i("ACTIVITY-OPEN", "Dashboard Activity");
    	startActivity(groups);
    	// Closing dashboard screen
    	finish();
    }
    
    private void group(){
    	Intent i = new Intent(getApplicationContext(), GroupActivity.class);
        
        // send group id to grouplist activity to get list of polls under that group
        i.putExtra("group_id", group_id);
        Log.d("INTENT-GROUP-ID", "Intent extra for group_id set to "+group_id);
        Log.i("ACTIVITY-OPEN", "Group Activity");
        startActivity(i);
    }
    
    /**
     * Background Async Task to Load all Albums by making http request
     * */
    class LoadChoices extends AsyncTask<String, String, String> {
 
        /**
         * Before starting background thread Show Progress Dialog
         * */
        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            pDialog = new ProgressDialog(PollActivity.this);
            pDialog.setMessage("Listing Poll Choices ...");
            pDialog.setIndeterminate(false);
            pDialog.setCancelable(false);
            pDialog.show();
        }
 
        /**
         * getting Groups JSON
         * */
        protected String doInBackground(String... args) {
        	Log.d("GET-POLL", "Attempting to get poll options");
        	getOptions();
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
                	ListAdapter adapter = new SimpleAdapter
                			(
	                            PollActivity.this, g,
	                            R.layout.new_poll_choice_view, new String[] { "id", "ans"}, 
	                            new int[] { R.id.poll_id, R.id.poll_choice}
                            );
                     
                    // updating listview
                    setListAdapter(adapter);
                }
            });
        }
    }
    
 // Query API to get groups and return the size
    private int getOptions(){
    	
    	groupFunc = new GroupFunctions();
    	poll = groupFunc.poll(getApplicationContext(), group_id, poll_id);
    	
    	Log.d("POLL-DATA", poll.toString() );
    	
    	try{
    		// Check if we have a successful request
    		if( poll.getString(KEY_SUCCESS) != null ){
    			Log.d("GROUP-POLL SUCCESS", "API returned success, trying to parse.");
    			
    			String res = poll.getString(KEY_SUCCESS); 
    			if(Integer.parseInt(res) == 1){
    				// reset the groups array and re-populate 
    				g.clear();
    				
    				// grab the JSON groups
    				JSONObject j_poll = poll.getJSONObject("poll");
    				JSONArray json_ans = j_poll.getJSONArray("answers");
    				Log.d("ANSWER-ARRAY", json_ans.toString() );
    				
    				// Set ques text
					//quesV.setText(j_poll.getString("ques"));
					
    				if(json_ans.length() > 0){
    					// We have some groups so populate layout
    					for (int i = 0; i < json_ans.length(); i++) {
    					    JSONObject row = json_ans.getJSONObject(i);
    					    // add to group object, then add to the list
    					    HashMap<String, String> map = new HashMap<String, String>();
    					    map.put("id", row.getString("id"));
    					    map.put("ans", row.getString("ans"));
    					    g.add(map);
    					    
    					    Log.d("POLL-CHOICE-OBJECT-ADDITION", "Choice: "+map.get("ans")+" added.");
    					}
    					
    					
    				} else {
    					Log.e("POLL-CHOICES-EMPTY", "No ans returned/noticed.");
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
    
    // Submit poll response
    private boolean submit_poll(){
    	submit = groupFunc.submit_poll(getApplicationContext(), group_id, poll_id, ans);
    	Log.d("POLL-SUBMIT-DATA", submit.toString() );
    	
    	try{
    		// Check if we have a successful request
    		if( submit.getString(KEY_SUCCESS) != null ){
    			Log.d("SUBMIT-POLL SUCCESS", "API returned success, trying to parse.");
    			
    			String res = submit.getString(KEY_SUCCESS); 
    			if(Integer.parseInt(res) == 1){
    				
    				Log.d("SUCCESS-ALERT", "Success has value of 1 - Alerting user" );
    				return true;
    				// alert.showAlertDialog(getApplicationContext(), "Success", "Poll submitted", true);
    				
    			} else {
    				Log.e("SUBMIT-FAIL", "failed to return submit success == 1.");
    				return false;
    				// alert.showAlertDialog(getApplicationContext(), "Failed", "Poll was not submitted", true);
    			}
    		} else {
    			Log.e("JSON-PARSE-SUBMIT-FAIL", "No success key found");
    			return false;
    		}
		} catch (JSONException e) {
			e.printStackTrace();
			Log.e("JSON-PARSE-SUBMIT-FAIL", "failed to parse response");
			return false;
		}
    }
    
}