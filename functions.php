<?php
//thinh-demo account
//$appkey = '0323b3c588dbee48367d0bf7f28a5860559b0d9c0d8902650d931392a280bc2a';
//$clientkey = '072e10a1b82cc9e616e2fe824922361666c13b50b396413b92fd7eb5fef13902';
//paxcreation account
//$appkey = '05a8973eee4674e39fea54130400c19062a9850f6ac50b38f37cf8dfe170e4af';
//$clientkey = '88a50b4160ed55cfc6be0e65bb542fa21e430dc72e8a21214040db21576438b5';     
//thinh-pax nifty
$appkey = 'd0771cf99cb1a2c1758541e3de752d03b6b7e9dc71ca185cef0817edbb36147a';
$clientkey = '9fef31f0790237ee3c114b7f3297190ae11a93fcf62aa99364fcc222bc7e89a7';     

//No change here-------------------
$fqdn = 'mb.api.cloud.nifty.com';

$format = "Y-m-d\TH:i:s.B\Z";
date_default_timezone_set('UTC');
$objDateTime = new DateTime();
$timestamp = $objDateTime->format($format);
//--------------------------------

function createSignature($method='GET', $fqdn, $apipath, $appkey, $clientkey, $timestamp, $params = null)
{
    
    $querystring = $method."\n".$fqdn."\n".$apipath."\n";
    $querystring = $querystring.'SignatureMethod=HmacSHA256&SignatureVersion=2&X-NCMB-Application-Key='.$appkey.
                '&X-NCMB-Timestamp='.$timestamp;
    if (isset($params))
    {
		$query = http_build_query(array('where'=>json_encode($params)));
		$querystring = $querystring.'&'.$query;//&where='.urlencode('{"hoten":"taianh"}'); 
	}
    $Sig = base64_encode(hash_hmac('sha256', $querystring, $clientkey, true));
    return $Sig;
}

function getNiftyObject($signature = '', $appkey = '', $url = '', $timestamp = '', $method = 'GET', $params = null,$token = '')
{   
    echo "-------------{$method} Object----------------------<br>";
    //create request header
    $headers = array(
                        'X-NCMB-Apps-Session-Token:'.$token,
                        'X-NCMB-Application-Key:'. $appkey,
                        'X-NCMB-Timestamp:'.$timestamp,
                        'X-NCMB-Signature:'.$signature,
                        'Content-Type:application/json;charset=UTF-8'
                    );
    $ch = curl_init();
    if ($method == 'LOGIN')
    {
        curl_setopt( $ch, CURLOPT_URL, $url.'?'.$params );
    }
    else if ($method == 'GET')
    {
        if (isset($params))
        {
            $query = http_build_query(array('where'=>json_encode($params)));
            curl_setopt( $ch, CURLOPT_URL, $url.'?'.$query );
        }
        else curl_setopt( $ch, CURLOPT_URL, $url);
    }
    else
    {
        curl_setopt( $ch, CURLOPT_URL, $url);
        if ($method == 'POST')
        {
            curl_setopt( $ch, CURLOPT_POST, true ); // Set request method to POST
            curl_setopt( $ch, CURLOPT_POSTFIELDS,json_encode($params));
        }
        else //if ($method == 'PUT')
        {
            //curl_setopt($ch, CURLOPT_PUT, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers ); 
    curl_setopt($ch, CURLINFO_HEADER_OUT, true );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $result = curl_exec( $ch );// Actually send the request!
    $info = curl_getinfo($ch);
     echo "<pre>";var_dump($info);
    if ( curl_errno( $ch ) )
    {
        echo 'Fatal error: ' . curl_error( $ch );
        return '{"error":-1}';
        //die;
    }
    curl_close( $ch ); // Close curl handle
    echo '<p>RESULT from NIFTY server:-----------------<br>';
    echo "<pre>";var_dump(json_decode($result,true));
    return json_decode($result,true);
}
function getGAPI($appkey = '', $url = '', $method = 'GET', $params = null,$token = '')
{   
    echo "-------------{$method} Object----------------------<br>";
    //create request header
    $headers = array(
                        //'X-NCMB-Apps-Session-Token:'.$token,
                        //'X-NCMB-Application-Key:'. $appkey,
                        'Content-Type:application/json;charset=UTF-8'
                    );
    $ch = curl_init();
    if ($method == 'LOGIN')
    {
        curl_setopt( $ch, CURLOPT_URL, $url.'?'.$params );
    }
    else if ($method == 'GET')
    {
        if (isset($params))
        {
            $query = http_build_query(array('where'=>json_encode($params)));
            curl_setopt( $ch, CURLOPT_URL, $url.'?'.$query );
        }
        else curl_setopt( $ch, CURLOPT_URL, $url);
    }
    else
    {
        curl_setopt( $ch, CURLOPT_URL, $url);
        if ($method == 'POST')
        {
            curl_setopt( $ch, CURLOPT_POST, true ); // Set request method to POST
            curl_setopt( $ch, CURLOPT_POSTFIELDS,json_encode($params));
        }
        else //if ($method == 'PUT')
        {
            //curl_setopt($ch, CURLOPT_PUT, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers ); 
    curl_setopt($ch, CURLINFO_HEADER_OUT, true );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $result = curl_exec( $ch );// Actually send the request!
    $info = curl_getinfo($ch);
     echo "<pre>";var_dump($info);
    if ( curl_errno( $ch ) )
    {
        echo 'Fatal error: ' . curl_error( $ch );
        return '{"error":-1}';
        //die;
    }
    curl_close( $ch ); // Close curl handle
    echo '<p>RESULT from NIFTY server:-----------------<br>';
    echo "<pre>";var_dump(json_decode($result,true));
    return json_decode($result,true);
}
?>

