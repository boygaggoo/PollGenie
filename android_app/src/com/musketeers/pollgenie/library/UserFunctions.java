/**
 * Author: Tapiwa Maruni
 * URL: www.droid-maruni.rhcloud.com
 * */
package com.musketeers.pollgenie.library;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONObject;

import android.content.Context;
import android.util.Log;

public class UserFunctions extends User {
	
	private JSONParser jsonParser;
	
	// run locally
	//private static String loginURL = "http://192.168.20.8/droid/api/login";
	//private static String registerURL = "http://192.168.20.8/droid/api/register";
	
	// run online
	private static String loginURL = "http://droid-maruni.rhcloud.com/api/login/";
	private static String registerURL = "http://droid-maruni.rhcloud.com/api/register";
	
	private static String login_tag = "login";
	private static String register_tag = "register";
	
	// constructor
	public UserFunctions(){
		jsonParser = new JSONParser();
	}
	
	/**
	 * function make Login Request
	 * @param email
	 * @param password
	 * */
	public JSONObject loginUser(String email, String password){
		// Building Parameters
		Log.d("LOGIN-REQUEST", "Sending request to Server");
		
		List<NameValuePair> params = new ArrayList<NameValuePair>();
		params.add(new BasicNameValuePair("tag", login_tag));
		params.add(new BasicNameValuePair("email", email));
		params.add(new BasicNameValuePair("password", password));
		JSONObject json = jsonParser.getJSONFromUrl(loginURL, params);
		// return json
		Log.d("LOGIN-RESPONSE", json.toString());
		return json;
	}
	
	/**
	 * function make Login Request
	 * @param name
	 * @param email
	 * @param password
	 * */
	public JSONObject registerUser(String name, String email, String password){
		// Building Parameters
		Log.d("REGISTER-REQUEST", "Sending request to Server");
		List<NameValuePair> params = new ArrayList<NameValuePair>();
		params.add(new BasicNameValuePair("tag", register_tag));
		params.add(new BasicNameValuePair("name", name));
		params.add(new BasicNameValuePair("email", email));
		params.add(new BasicNameValuePair("password", password));
		
		// getting JSON Object
		JSONObject json = jsonParser.getJSONFromUrl(registerURL, params);
		// return json
		Log.d("REGISTER-RESPONSE", json.toString());
		return json;
	}
	
	public User getUser(Context context){
		User u = new User();
		HashMap<String, String> temp;
		
		// populate user from database
		if( isUserLoggedIn(context) ){
			DatabaseHandler db = new DatabaseHandler(context);
			temp = db.getUserDetails();
			
			// add items to User object
			u.setName(temp.get("name"));
			u.setEmail(temp.get("email"));
			u.setId(temp.get("uid"));
			u.setDateCreated(temp.get("created_at"));
		}
		Log.d("USER", u.toString());
		return u;
	}
	
	/**
	 * Function get Login status
	 * */
	public boolean isUserLoggedIn(Context context){
		DatabaseHandler db = new DatabaseHandler(context);
		int count = db.getRowCount();
		if(count > 0){
			// user logged in
			return true;
		}
		return false;
	}
	
	/**
	 * Function to logout user
	 * Reset Database
	 * */
	public boolean logoutUser(Context context){
		DatabaseHandler db = new DatabaseHandler(context);
		db.resetTables();
		return true;
	}
	
}
