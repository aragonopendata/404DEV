package com.dev404.aragomo;

import android.support.v7.app.ActionBarActivity;
import android.content.Context;
import android.location.LocationManager;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;


public class MainActivity extends ActionBarActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        
        //Creating locationManager instance
        
        LocationManager locationManager = (LocationManager)
        getSystemService(Context.LOCATION_SERVICE);
        
        //TODO: check if GPS is enabled or not
        
        
        
    }

    @Override
    public void onLocationChanged(Location location) {
	    txtLat = (TextView) findViewById(R.id.textview1);
	    txtLat.setText("Latitude:" + location.getLatitude() + ", Longitude:" + location.getLongitude());
	}
    
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();
        if (id == R.id.action_settings) {
            return true;
        }
        return super.onOptionsItemSelected(item);
    }
}
