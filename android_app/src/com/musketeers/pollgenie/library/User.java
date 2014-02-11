/**
 * Author: Tapiwa Maruni
 * URL: www.droid-maruni.rhcloud.com
 * */
package com.musketeers.pollgenie.library;

public class User {
	private String _id;
	private String _name;
	private String _email;
	private String _created;
	
	public String getId(){
		return _id;
	}
	
	public String getName(){
		return _name;
	}
	
	public String getEmail(){
		return _email;
	}
	
	public String getDateCreated(){
		return _created;
	}
	
	public void setId(String string){
		_id = string;
	}
	
	public void setName(String name){
		_name = name;
	}
	
	public void setEmail(String email){
		_email = email;
	}
	
	public void setDateCreated(String date){
		_created = date;
	}
	
	public String toString(){
		return "Name:"+_name + " email:"+_email + " id:"+_id + " created:"+_created;
	}
}
