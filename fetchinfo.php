<?php
//https://www.enuygun.com/otobus-bileti/async-result/antalya-otogari-ankara-otogari/?gidis=27.12.2021

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://www.enuygun.com/otobus-bileti/async-result/'.$_GET['qs']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = 'Authority: www.enuygun.com';
$headers[] = 'Sec-Ch-Ua: \" Not A;Brand\";v=\"99\", \"Chromium\";v=\"96\", \"Google Chrome\";v=\"96\"';
$headers[] = 'Accept: application/json, text/plain, */*';
$headers[] = 'X-Requested-With: XMLHttpRequest';
$headers[] = 'Sec-Ch-Ua-Mobile: ?0';
$headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36';
$headers[] = 'Sec-Ch-Ua-Platform: \"macOS\"';
$headers[] = 'Sec-Fetch-Site: same-origin';
$headers[] = 'Sec-Fetch-Mode: cors';
$headers[] = 'Sec-Fetch-Dest: empty';
$headers[] = 'Referer: https://www.enuygun.com/';
$headers[] = 'Accept-Language: en-US,en;q=0.9,tr;q=0.8,vi;q=0.7,ru;q=0.6';
$headers[] = 'Cookie: SERVERID-SAG=rdwww10; _gcl_au=1.1.268313750.1640549112; _gid=GA1.2.1340541615.1640549112; cookieAlert=true; device_view=full; CCI=9edd2b45-1216-480b-8a6c-93e85262a35d; _dn_sid=8469a573-06c2-4129-bdf0-42c56ccc0ddc; bus_affiliate=source%3Ddirect%26medium%3Ddirect%26referrer%3Dhttps%253A%252F%252Fwww.enuygun.com%252Fotobus-bileti%252Fankara-otogari-izmir-otogari%252F%253Fgidis%253D27.12.2021; is_session_has_migrated=1; userIsLoggedIn=0; __gads=ID=1af4431df1b02f54:T=1640549253:S=ALNI_Mbo7EZibHE2G64Yp6OHFjz8DzKBJg; _ga=GA1.2.1085014187.1640549112; _ga_VNWQY32CGH=GS1.1.1640549252.1.1.1640549294.0; _gat_UA-6268301-1=1; cto_bundle=YEmt4l9TeGFRTm9rU2dhNURtMVFmJTJCVXJJeXlsTGtGZU9SY2l1VWlGOFJ6Z0VWSHI5aWloOHltTk40VG5wTHIyTHlUY0RlVHJkREh2Y3REWmdaTHJTT0c3ZWdZbUlrdERCU2RadDk0aHMzUDltbHVYaVVRUmpPQXVlOEszdDhuV2JPMmdPJTJCamxyWTd1JTJCaGtpaDFHa2k4UWklMkYweHlpbGp2dERDNUN2MzhKVCUyQkFYZEkyRUxqZlRVNFglMkZIVmIxdzdhQXgzQk8';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
echo $result;
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

?>