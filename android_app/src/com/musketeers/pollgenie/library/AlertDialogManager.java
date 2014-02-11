package com.musketeers.pollgenie.library;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
 
import com.musketeers.pollme.R;
 
public class AlertDialogManager {
    /**
     * Function to display simple Alert Dialog
     * @param context - application context
     * @param title - alert dialog title
     * @param message - alert message
     * @param status - success/failure (used to set icon)
     *               - pass null if you don't want icon
     * */
    public void showAlertDialog(Context context, String title, String message, Boolean status) {
    	AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(context);
 
        // Setting Dialog Title
    	alertDialogBuilder.setTitle(title);
 
        // Setting Dialog Message
    	alertDialogBuilder.setMessage(message);
 
        //if(status != null)
            // Setting alert dialog icon
        	// alertDialogBuilder.setIcon((status) ? R.drawable.success : R.drawable.fail);
 
        alertDialogBuilder.setCancelable(false);
        
        // Setting OK Button
        alertDialogBuilder.setPositiveButton("Ok",new DialogInterface.OnClickListener() {
			public void onClick(DialogInterface dialog,int id) {
				// if this button is clicked, close current activity
			}
		  });
 
        AlertDialog alertDialog = alertDialogBuilder.create();
        
        // Showing Alert Message
        alertDialog.show();
    }
}