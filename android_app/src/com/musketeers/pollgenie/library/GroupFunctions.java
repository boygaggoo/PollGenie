/**
 * Author: Tapiwa Maruni
 * URL: www.droid-maruni.rhcloud.com
 * */
package com.musketeers.pollgenie.library;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONException;
import org.json.JSONObject;

import android.content.Context;
import android.util.Log;

public class GroupFunctions {

	private JSONParser jsonParser;
	private UserFunctions userF;
	
	private static String allGroupsURL = "http://droid-maruni.rhcloud.com/api/all_groups/";
	private static String groupURL = "http://droid-maruni.rhcloud.com/api/group/";
	private static String createGroupURL = "http://droid-maruni.rhcloud.com/api/c_group/";
	private static String openGroupsURL = "http://droid-maruni.rhcloud.com/api/open_groups/";
	private static String joinGroupURL = "http://droid-maruni.rhcloud.com/api/j_group/";
	private static String leaveGroupURL = "http://droid-maruni.rhcloud.com/api/r_group/";
	private static String deleteGroupURL = "http://droid-maruni.rhcloud.com/api/d_group/";
	
	private static String pollURL = "http://droid-maruni.rhcloud.com/api/poll/";
	private static String submitPollURL = "http://droid-maruni.rhcloud.com/api/s_poll/";
	
	private static String all_groups_tag = "all_groups";
	private static String group_tag = "group";
	private static String create_group_tag = "create_group";
	private static String open_groups_tag = "join";
	private static String join_tag = "join";
	private static String delete_tag = "delete";
	
	// constructor
		public GroupFunctions(){
			jsonParser = new JSONParser();
			userF = new UserFunctions();
		}
		
		public JSONObject all_groups(Context context){
			User u = userF.getUser(context);
			List<NameValuePair> params = new ArrayList<NameValuePair>();
			params.add(new BasicNameValuePair("tag", all_groups_tag));
			params.add(new BasicNameValuePair("user_id", u.getId() ));
			JSONObject json = jsonParser.getJSONFromUrl(allGroupsURL, params);
			// return json
			Log.d("ALL_GROUPS-JSON", json.toString());
			return json;
		}
		
		public JSONObject group(Context context, String group_id){
			User u = userF.getUser(context);
			List<NameValuePair> params = new ArrayList<NameValuePair>();
			params.add(new BasicNameValuePair("tag", group_tag));
			params.add(new BasicNameValuePair("user_id", u.getId() ));
			params.add(new BasicNameValuePair("group_id", group_id ));
			JSONObject json = jsonParser.getJSONFromUrl(groupURL, params);
			// return json
			Log.d("GROUP-JSON", json.toString());
			return json;
		}
		
		public JSONObject open_groups(Context context){
			User u = userF.getUser(context);
			List<NameValuePair> params = new ArrayList<NameValuePair>();
			params.add(new BasicNameValuePair("tag", open_groups_tag));
			params.add(new BasicNameValuePair("user_id", u.getId() ));
			JSONObject json = jsonParser.getJSONFromUrl(openGroupsURL, params);
			// return json
			Log.d("OPEN-GROUP-JSON", json.toString());
			return json;
		}
		
		public JSONObject join_group(Context context, String group_id){
			User u = userF.getUser(context);
			List<NameValuePair> params = new ArrayList<NameValuePair>();
			params.add(new BasicNameValuePair("tag", open_groups_tag));
			params.add(new BasicNameValuePair("user_id", u.getId() ));
			params.add(new BasicNameValuePair("group_id", group_id ));
			JSONObject json = jsonParser.getJSONFromUrl(joinGroupURL, params);
			// return json
			Log.d("JOIN-GROUP-JSON", json.toString());
			return json;
		}
		
		public JSONObject create_group(Context context, String group_name){
			User u = userF.getUser(context);
			List<NameValuePair> params = new ArrayList<NameValuePair>();
			params.add(new BasicNameValuePair("tag", create_group_tag));
			params.add(new BasicNameValuePair("user_id", u.getId() ));
			params.add(new BasicNameValuePair("group_name", group_name ));
			JSONObject json = jsonParser.getJSONFromUrl(createGroupURL, params);
			// return json
			Log.d("CREATE-GROUP-JSON", json.toString());
			return json;
		}
		
		public boolean leave_group(Context context, String group_id){
			User u = userF.getUser(context);
			List<NameValuePair> params = new ArrayList<NameValuePair>();
			params.add(new BasicNameValuePair("tag", open_groups_tag));
			params.add(new BasicNameValuePair("user_id", u.getId() ));
			params.add(new BasicNameValuePair("group_id", group_id ));
			JSONObject json = jsonParser.getJSONFromUrl(leaveGroupURL, params);
			
			// return true if removed from group
			Log.d("LEAVE-GROUP-JSON", json.toString());
			boolean r = false;
			try {
				if( json.getString("success") != null ){
					String res = json.getString("success"); 
	    			if(Integer.parseInt(res) == 1){
	    				Log.d("REMOVE-SUCCESS", "Removed from group");
	    				r = true;
	    			} 
				}
			} catch (JSONException e) {
				e.printStackTrace();
			}
			return r;
		}
		
		public JSONObject poll(Context context, String group_id, String poll_id){
			User u = userF.getUser(context);
			List<NameValuePair> params = new ArrayList<NameValuePair>();
			params.add(new BasicNameValuePair("tag", group_tag));
			params.add(new BasicNameValuePair("user_id", u.getId() ));
			params.add(new BasicNameValuePair("group_id", group_id ));
			params.add(new BasicNameValuePair("poll_id", poll_id ));
			JSONObject json = jsonParser.getJSONFromUrl(pollURL, params);
			// return json
			Log.d("POLL-JSON", json.toString());
			return json;
		}
		
		public JSONObject submit_poll(Context context, String group_id, String poll_id, String ans){
			User u = userF.getUser(context);
			List<NameValuePair> params = new ArrayList<NameValuePair>();
			params.add(new BasicNameValuePair("tag", group_tag));
			params.add(new BasicNameValuePair("user_id", u.getId() ));
			params.add(new BasicNameValuePair("group_id", group_id ));
			params.add(new BasicNameValuePair("poll_id", poll_id ));
			params.add(new BasicNameValuePair("ans", ans ));
			JSONObject json = jsonParser.getJSONFromUrl(submitPollURL, params);
			// return json
			Log.d("SUBMIT-JSON", json.toString());
			return json;
		}
		
		
		
}
