/**
 * Author: Tapiwa Maruni
 * URL: www.droid-maruni.rhcloud.com
 * */
package com.musketeers.pollgenie.library;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.UnsupportedEncodingException;
import java.util.List;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONException;
import org.json.JSONObject;

import android.util.Log;

public class JSONParser {

	static InputStream is = null;
	static JSONObject jObj = null;
	static String json = "";

	// constructor
	public JSONParser() {

	}

	public JSONObject getJSONFromUrl(String url, List<NameValuePair> params) {

		// Making HTTP request
		try {
			Log.d("HTTP-REQUEST", "Sending request to "+ url);
			String pairs = "";
			for(NameValuePair x: params){
				pairs += x.toString() + "  ";
			}
			Log.d("HTTP-REQUEST-PARMS", pairs);
			
			// defaultHttpClient
			DefaultHttpClient httpClient = new DefaultHttpClient();
			HttpPost httpPost = new HttpPost(url);
			
			Log.d("HTTP-POST-ENCODE", "Trying to encode parameters");
			httpPost.setEntity(new UrlEncodedFormEntity(params));

			Log.d("HTTP-POST-EXECUTE", "passed encoding parameters. Executing");
			HttpResponse httpResponse = httpClient.execute(httpPost);
			
			Log.d("HTTP-POST-EXECUTE-RESPONSE", "passed execution. getting content");
			HttpEntity httpEntity = httpResponse.getEntity();
			is = httpEntity.getContent();
			
			Log.d("HTTP-RESPONSE-TO-STREAM", "set response as input stream");

		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
			Log.e("JSON-ERROR", "Unsupported Encoding Exception");
		} catch (ClientProtocolException e) {
			e.printStackTrace();
			Log.e("JSON-ERROR", "Client Protocol Exception");
		} catch (IOException e) {
			e.printStackTrace();
			Log.e("JSON-ERROR", "IO Exception");
		} catch (Exception e){
			Log.e("JSON-ERROR", "Some other Exception found");
		}

		try {
			BufferedReader reader = new BufferedReader(new InputStreamReader(
					is, "iso-8859-1"), 8);
			StringBuilder sb = new StringBuilder();
			String line = null;
			while ((line = reader.readLine()) != null) {
				sb.append(line + "\n");
			}
			is.close();
			json = sb.toString();
			Log.d("HTTP-RAW-RESPONSE", json);
		} catch (Exception e) {
			Log.e("Buffer Error", "Error converting result " + e.toString());
		}

		// try parse the string to a JSON object
		try {
			jObj = new JSONObject(json);			
		} catch (JSONException e) {
			Log.e("JSON Parser", "Error parsing data " + e.toString());
		}

		// return JSON String
		return jObj;
	}
}
