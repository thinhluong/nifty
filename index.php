<?php
//http://mb.cloud.nifty.com/doc/current/introduction/quickstart_android.html
//http://mb.cloud.nifty.com/doc/current/rest/push/installationGet.html
//http://mb.cloud.nifty.com/doc/current/push/basic_usage_android.html
//http://mb.cloud.nifty.com/doc/current/tutorial/push_setup_android.html
http://mb.cloud.nifty.com/doc/current/rest/common/query.html

require_once 'functions.php';
//$appkey = '0323b3c588dbee48367d0bf7f28a5860559b0d9c0d8902650d931392a280bc2a';
//$clientkey = '072e10a1b82cc9e616e2fe824922361666c13b50b396413b92fd7eb5fef13902';
//$devicetoken = 'APA91bEntbbw0WDzxCWMYN8OolZOauf_1rDYIbnrXgFGZd4Esg7xI9gtLyCKCf_RVL0IPWfyWJId8uVVvXwuzs7alH2w9O5r21i0mPqqwjKf17CaC-DXjsld0KP-cdV3Uu41yYvYDkgls2ZCWSfmUe6xcWbx1o9fiQ';
//$fqdn = 'mb.api.cloud.nifty.com';
////$apipath = '/2013-09-01/classes/TestClass';
////$objID = '/ktjh9y7o53zpoChL';
//$format = "Y-m-d\TH:i:s.B\Z";
////date_default_timezone_set('UTC');
//$objDateTime = new DateTime();
////$timestamp = $objDateTime->format(DateTime::ISO8601);
////$timestamp = date('c');
//$timestamp = $objDateTime->format($format);
////var_dump($timestamp);die;
$objs = array();

//$data = array('objectId'=>'NyogM5qBa7cQEtIa');
//$getdata = http_build_query(array('where'=>json_encode($data)));//query string for get
//$getdata = '';
$data = null;
$testpath = '/2013-09-01/classes/TestClass';
$url = 'https://'.$fqdn.$testpath;
$Sig = createSignature('GET', $fqdn, $testpath, $appkey, $clientkey, $timestamp, $data); //$getdata);
$objs = getNiftyObject($Sig, $appkey, $url, $timestamp,'GET',$data); //get method

$installationpath = '/2013-09-01/installations';
$url = 'https://'.$fqdn.$installationpath;
$Sig = createSignature('GET', $fqdn, $installationpath, $appkey, $clientkey, $timestamp, $data); 
$objs = getNiftyObject($Sig, $appkey, $url, $timestamp,'GET',$data); //get method

$pushpath = '/2013-09-01/push';
$url = 'https://'.$fqdn.$pushpath;
$Sig = createSignature('GET', $fqdn, $pushpath, $appkey, $clientkey, $timestamp, $data); 
$objs = getNiftyObject($Sig, $appkey, $url, $timestamp,'GET',$data); //get method

//-------------------PUSH MESSAGE
$device_setdata = array(
                    //'searchCondition'=> array('channels' => array('taideptrai')),
                    'target'=>array('android'),
                    'title' => 'thinh-pax',
                    'message' => 'Lets discover NIFTY',
                    'deliveryExpirationTime' => '3 day',
                    'immediateDeliveryFlag' =>true,
                    'dialog'=>true
                );
$Sig = createSignature('POST', $fqdn, $pushpath, $appkey, $clientkey, $timestamp);
getNiftyObject($Sig, $appkey, $url, $timestamp,'POST',$device_setdata);                

die;
foreach ($objs as $key=>$arr)
{
    //-----------------------
    $ob = $arr[0]['objectId'];print($ob);
    $apipath = $apipath."/{$ob}";
    $url = 'https://'.$fqdn.$apipath;
    //$Sig = createSignature('GET', $fqdn, $apipath, $appkey, $clientkey, $timestamp);
    //getNiftyObject($Sig, $appkey, $url, $timestamp,'GET'); //get method
    $setdata = array('objectId' => "{$ob}",
                    'hoten'=>'ldthinh2002'
                    );
    //$Sig = createSignature('PUT', $fqdn, $apipath, $appkey, $clientkey, $timestamp);
    //getNiftyObject($Sig, $appkey, $url, $timestamp,'PUT',$setdata); //post method
    //$Sig = createSignature('DELETE', $fqdn, $apipath, $appkey, $clientkey, $timestamp);
    //getNiftyObject($Sig, $appkey, $url, $timestamp,'DELETE',$setdata); //post method
}
$apipath = '/2013-09-01/login';
$url = 'https://'.$fqdn.$apipath;
$data = array('password' => utf8_encode('abc123'),'userName'=>'teoanh');
$getdata = http_build_query($data);//query string for get
$Sig = createSignature('GET', $fqdn, $apipath, $appkey, $clientkey, $timestamp,$getdata);
$obj = getNiftyObject($Sig, $appkey, $url, $timestamp,'LOGIN',$getdata); //get method
//print_r($obj);
if (empty($obj)) die;//---------------------------------------
else
{
    $token = $obj['sessionToken'];
    //-----------------------User Update
    $id = $obj['objectId'];
    $apipath = '/2013-09-01/users'."/{$id}";
    $url = 'https://'.$fqdn.$apipath;
    //$data = array('objectId' => $id);
    $Sig = createSignature('GET', $fqdn, $apipath, $appkey, $clientkey, $timestamp);
    $obj = getNiftyObject($Sig, $appkey, $url, $timestamp,'GET',null,$token); //get method
    //print_r($obj);die;
    $setdata = array(
                        'userName' => $obj['userName'],
                        'authData' => $obj['authData'],
                        'mailAddress'=>'ldthinh2002@gmail.com',
                        'acl' => $obj['acl']
                    );
    $Sig = createSignature('PUT', $fqdn, $apipath, $appkey, $clientkey, $timestamp);
    getNiftyObject($Sig, $appkey, $url, $timestamp,'PUT',$setdata, $token); //post method
    //$Sig = createSignature('DELETE', $fqdn, $apipath, $appkey, $clientkey, $timestamp);
    //getNiftyObject($Sig, $appkey, $url, $timestamp,'DELETE',$setdata, $token); //post method
    
    //----------------File Update
    $acl = '';
    $filename = "myimg.jpg";
    $apipath = '/2013-09-01/files'."/mypic.jpg";
    $url = 'https://'.$fqdn.$apipath;
    $myfile = fopen($filename, "r") or die("Unable to open file!");
    $content = (fread($myfile,filesize($filename)));
    //echo $content;die;
    $Sig = createSignature('POST', $fqdn, $apipath, $appkey, $clientkey, $timestamp);
    queryfile($Sig, $appkey, $url, $timestamp,'POST_FILE',$content.';type=image/jpeg', $token); //post method
    fclose($myfile);
}
die;
$device_setdata = array(
                    'channels' => array('paxcreation'),
                    'deviceType'=>'android',
                    //'deviceToken'=> utf8_encode($devicetoken)
                );
$apipath = '/2013-09-01/installation';
$url = 'https://'.$fqdn.$apipath;
//$Sig = createSignature('POST', $fqdn, $apipath, $appkey, $clientkey, $timestamp);
//getNiftyObject($Sig, $appkey, $url, $timestamp,'POST',$device_setdata);

$getdata = http_build_query(array('where'=>json_encode($device_setdata)));//query string for get
$Sig = createSignature('GET', $fqdn, $apipath, $appkey, $clientkey, $timestamp,$getdata);
getNiftyObject($Sig, $appkey, $url, $timestamp,'GET',$device_setdata); //get method

//-------------------PUSH MESSAGE
$device_setdata = array(
                    //'searchCondition'=> array('channels' => array('taideptrai')),
                    'target'=>array('android'),
                    'title' => 'thinh-pax',
                    'message' => 'Lets discover NIFTY',
                    'deliveryExpirationTime' => '3 day',
                    'immediateDeliveryFlag' =>true,
                    'dialog'=>true
                );
$apipath = '/2013-09-01/push';
$url = 'https://'.$fqdn.$apipath;
$Sig = createSignature('POST', $fqdn, $apipath, $appkey, $clientkey, $timestamp);
getNiftyObject($Sig, $appkey, $url, $timestamp,'POST',$device_setdata);
//device token for GCM
//$ids = array('APA91bEntbbw0WDzxCWMYN8OolZOauf_1rDYIbnrXgFGZd4Esg7xI9gtLyCKCf_RVL0IPWfyWJId8uVVvXwuzs7alH2w9O5r21i0mPqqwjKf17CaC-DXjsld0KP-cdV3Uu41yYvYDkgls2ZCWSfmUe6xcWbx1o9fiQ');
//$data = array( 'message' => 'Hello Tai handsome!' );
//sendGoogleCloudMessage(  $data, $ids );
//sendNiftyMessage(  $data, $ids );



?>
