<?php
/*
Author: Mikel Carozzi
E-mail: m.carozzi@gmail.com
License: http://www.gnu.org/licenses/gpl-3.0-standalone.html
*/

set_time_limit(0);

$username = 'username';
$password = 'password';

$url="http://link-to-livelink.com:8080/otdsws/login"; 
$postinfo = "otds_username=".$username."&otds_password=".$password;

$cookie_file_path = "cookie.txt";

$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, false);
curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
curl_setopt($ch, CURLOPT_COOKIE, "cookiename=0");
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
curl_exec($ch);

$html = curl_exec($ch);
curl_close($ch);

$idCC = 'id';

/*
I figureout tree ways to explore and explote the livelink xml API, the only one inconvinient has the 
deep of the subfolders when i use the followaliases argument, if the instance has many subfolders can be a
pain in the ass. 

*/

// uncomment only one url_xml

/*
Use this for extract all content from livelink, the content=dcada argument add the base64 of all files includes in the livelink

$url_xml = "http://cs.colbunsa.cl/livelink/livelink.exe?func=ll&objId=" .$idCC. "&objAction=xmlexport&versioninfo=1&scope=sub&versioninfo=all&followaliases&content=cdata";

*/

/*
Use this  for extract only a directory structure

$url_xml = "http://cs.colbunsa.cl/livelink/livelink.exe?func=ll&objId=" .$idCC. "&objAction=xmlexport&versioninfo=1&scope=sub&versioninfo=all&schema&followaliases";
*/

sleep(2);

// $fp create the new xml file with all data contained in the cURL request.

$fp = fopen ("newXml.xml", 'w+');

$ch = curl_init();

curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
curl_setopt($ch, CURLOPT_URL, $url_xml);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 50000);
curl_setopt($ch, CURLOPT_FILE, $fp); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array('otcsticket' => 'ticket'));

curl_exec($ch); 
curl_close($ch);
fclose($fp);

?>
