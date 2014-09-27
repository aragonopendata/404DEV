package com.dev404.aragomo;

import java.text.DecimalFormat;

import android.support.v7.app.ActionBarActivity;
import android.content.Context;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Button;
import android.widget.SeekBar;
import android.widget.TextView;
import android.widget.Toast;


public class MainActivity extends ActionBarActivity {
    double longitude;
    double latitude;
	TextView txtLat,txtLon;
	String serverUrl = "http://155.210.71.103/Aragomoapp/map.php";
	String sendingUrl = serverUrl;
	SeekBar km_bar;
	boolean debug = true;
	WebView myWebView;
	//?lat=42.034553&lng=0.125476&distance=5000
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

        if (debug)
        {
        	latitude = 41.65;
        	longitude = -0.883333;
        }
        else
        {
        	longitude = location.getLongitude();
        	latitude = location.getLatitude();
        }

        lm.requestLocationUpdates(LocationManager.GPS_PROVIDER, 2000, 10, locationListener);
        
        //updating URL
        sendingUrl += "?lat=" + latitude +"&lng" + longitude;
        
        //finding seekbar item
        
        km_bar = (SeekBar) findViewById(R.id.volume_bar);
        
        //updating URL
        
        sendingUrl += "&dist="+getValue();
        
	    //loading webview
	    
	    myWebView = (WebView) findViewById(R.id.webview);

	    WebSettings webSettings = myWebView.getSettings();
	    webSettings.setJavaScriptEnabled(true);
	    webSettings.setBuiltInZoomControls(true);
	    
	    myWebView.setWebViewClient(new Callback());  //HERE IS THE MAIN CHANGE

	    
        //refresh gps coordenates
	    String filteredGPS; 
	    filteredGPS = "(" + (new DecimalFormat("##.##").format(latitude)) + "," + (new DecimalFormat("##.##").format(longitude)) + ")";
	    txtLat = (TextView) findViewById(R.id.gps);
	    //txtLat.setText("Latitude:" + latitude + ", Longitude:" + longitude);
	    txtLat.setText(filteredGPS);
        	    
        final Button loadMapButton = (Button) findViewById(R.id.loadMapButton);
        loadMapButton.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {
                // Perform action on click
            	sendingUrl = serverUrl;
                sendingUrl += "?lat=" + latitude +"&lng" + longitude;

                sendingUrl += "&dist="+getValue();   
                
                if (debug)
                {
                    sendingUrl += "&debug";
                }
        	    myWebView.loadUrl(sendingUrl);
                
            	Toast.makeText(getApplicationContext(), sendingUrl, Toast.LENGTH_SHORT).show();
            }
        });

	    
	    
    }
    
    private class Callback extends WebViewClient{  //HERE IS THE MAIN CHANGE. 

        @Override
        public boolean shouldOverrideUrlLoading(WebView view, String url) {
            return (false);
        }

    }    
    
    private final LocationListener locationListener = new LocationListener() {
        public void onLocationChanged(Location location) {
            if (debug)
            {
            	latitude = 41.65;
            	longitude = -0.883333;
            }
            else
            {
            	longitude = location.getLongitude();
            	latitude = location.getLatitude();
            }
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
   /*
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
    }*/
    
    private int getValue() {
        int value=1;
        value = km_bar.getProgress() + 1;
        return value;
    }    
    
}
