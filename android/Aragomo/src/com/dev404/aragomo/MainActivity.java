package com.dev404.aragomo;

import android.support.v7.app.ActionBarActivity;
import android.content.Context;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.TextView;


public class MainActivity extends ActionBarActivity {
    double longitude;
    double latitude;
	TextView txtLat,txtLon;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        
        //Creating locationManager instance
        
        /*LocationManager locationManager = (LocationManager)
        getSystemService(Context.LOCATION_SERVICE);*/
        LocationManager lm = (LocationManager)getSystemService(Context.LOCATION_SERVICE); 
        Location location = lm.getLastKnownLocation(LocationManager.GPS_PROVIDER);

        //TODO: check if GPS is enabled or not
        // will crash if GPS is NOT enabled! 

        longitude = location.getLongitude();
        latitude = location.getLatitude();         

        lm.requestLocationUpdates(LocationManager.GPS_PROVIDER, 2000, 10, locationListener);
        

	    //loading webview
	    
	    WebView myWebView = (WebView) findViewById(R.id.webview);

	    myWebView.setWebViewClient(new Callback());  //HERE IS THE MAIN CHANGE
	    myWebView.loadUrl("http://www.google.com");
	    
        //refresh gps coordenates
	    txtLat = (TextView) findViewById(R.id.gps);
	    txtLat.setText("Latitude:" + latitude + ", Longitude:" + longitude);
        	    
	    
    }
    
    private class Callback extends WebViewClient{  //HERE IS THE MAIN CHANGE. 

        @Override
        public boolean shouldOverrideUrlLoading(WebView view, String url) {
            return (false);
        }

    }    
    
    private final LocationListener locationListener = new LocationListener() {
        public void onLocationChanged(Location location) {
            longitude = location.getLongitude();
            latitude = location.getLatitude();
        }

		@Override
		public void onProviderDisabled(String arg0) {
			// TODO Auto-generated method stub
			
		}

		@Override
		public void onProviderEnabled(String arg0) {
			// TODO Auto-generated method stub
			
		}

		@Override
		public void onStatusChanged(String arg0, int arg1, Bundle arg2) {
			// TODO Auto-generated method stub
			
		}
    };


    //@Override
    public void onLocationChanged(Location location) {
        /*double longitude = location.getLongitude();
        double latitude = location.getLatitude();*/
	    txtLat = (TextView) findViewById(R.id.gps);
	    txtLat.setText("Latitude:" + latitude + ", Longitude:" + longitude);
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
