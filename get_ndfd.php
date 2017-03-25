<?php

/* http://sourceforge.net/projects/nusoap/ */
/*http://phpstarter.net/2009/02/parse-weather-forecast-data-from-the-ndfd-in-php/ */
//require('../includes/nusoap/nusoap.php');
//require('/var/www/nusoap/lib/nusoap.php');
require('/metero/software/tomcat/apache-tomcat-6.0.36/webapps/ROOT/nusoap/lib/nusoap.php');
$loc_name=array('SPS','PSCO');
$point_lat=array(33.13295, 37.74);
$point_lon=array(-103.85787,-105.88);

for ($i=0; $i <count($point_lat); ++$i) {
 
$parameters = array('product'	=> 'time-series',
                               //     'startTime' => '2014-03-07T06:00:00-06:00' ,
                               //    'endTime'=> '2014-03-08T18:00:00-06:00',
			       //		'latitude'  => 33.13295,
				//	'longitude'	=> -103.85787,
			       		'latitude'  => $point_lat[$i],
					'longitude'	=> $point_lon[$i],
					'weatherParameters' => array(
     'maxt' => false,          'mint' => false,          'temp' => false,          'dew' => false,
     'appt' => false,           'pop12' => false,         'qpf' => false,           'snow' => false,	
     'sky' => true,           'rh' => false,            'wspd' => false,          'wdir' => false,	
     'wx' => false,            'icons' => false,         'waveh' => false,         'incw34' => false,	
     'incw50' => false,        'incw64' => false,        'cumw34' => false,        'cumw50' => false,	
     'cumw64' => false,        'wgust' => false,         'conhazo' => false,       'ptornado' => false,	
     'phail' => false,         'ptstmwinds' => false,    'pxtornado' => false,     'pxhail' => false,	
     'pxtstmwinds' => false,   'ptotsvrtstm' => false,   'pxtotsvrtstm' => false,  'tmpabv14d' => false,	
     'tmpblw14d' => false,     'tmpabv30d' => false,     'tmpblw30d' => false,     'tmpabv90d' => false,	
     'tmpblw90d' => false,     'prcpabv14d' => false,    'prcpblw14d' => false,    'prcpabv30d' => false,	
     'prcpblw30d' => false,    'prcpabv90d' => false,    'prcpblw90d' => false,    'precipa_r' => false,	
     'sky_r' => false,         'td_r' => false,          'temp_r' => false,        'wdir_r' => false,	
     'wwa' => false,           'wspd_r' => false)
					);

try
{
	/* create the nuSOAP object */
	$c = new nusoap_client('http://www.weather.gov/forecasts/xml/DWMLgen/wsdl/ndfdXML.wsdl', 'wsdl');
        /*
	$c = new nusoap_client('http://www.weather.gov/forecasts/xml/DWMLgen/wsdl/ndfdXML.wsdl', 'wsdl', array('proxy_host'=> "proxy",
                                                                                                       'proxy_port' => 80,
                                                                                                       'proxy_login' => "bglr02",
                                                                                                       'proxy_password' => "ucu8Flogh"));
	*/
	/* make the request */
        print"making request";
	$result = $c->call('NDFDgen', $parameters);
}
catch (Exception $ex)
{
	/* nuSOAP throws an exception is there was a problem fetching the data */
	echo 'failed';
}

/*header('Content-type: text/xml');*/
$forecast='/metero/software/tomcat/apache-tomcat-6.0.36/webapps/ROOT/nusoap/'.$loc_name[$i].'_ndfd.xml';
$fcstfile=fopen($forecast,'w');
fwrite($fcstfile,$result);
fclose($fcstfile);
//$command = "python3 /var/www/nusoap/ndfd_parse.py -t $loc_name[$i]";
//exec($command);
//echo $result;
}

/* ?> */
