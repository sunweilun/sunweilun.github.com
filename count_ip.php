<?php
echo "Hello World";
/**
* 统计IP及在线人数
* edit www.jbxue.com
* date 2013/7/3
*/
$file             = "count"; // 记数文件名称
$startno          = "1000";   // 起始数值
$tempfile         = "temp"; 
$t_now   = time();
$t_array = getdate($t_now);
$day     = $t_array['mday'];
$mon     = $t_array['mon'];
$year    = $t_array['year'];
if (file_exists("$file")) {
        $count_info=file("$file");
        $c_info = explode(",", $count_info[0]);
        $total_c=$c_info[0];
        $yesterday_c=$c_info[1];
        $today_c=$c_info[2];
        $lastday=$c_info[3];
} else {
        $total_c="$startno";
        $yesterday_c="0";
        $today_c="0";
        $lastday="0";
}

if ( !isset($HTTP_COOKIE_VARS["countcookie"]) || $HTTP_COOKIE_VARS["countcookie"] != $day) {
        $your_c=1;
        $lockfile=fopen("temp","a");
        flock($lockfile,3);
        putenv('TZ=JST-9');
 
        $t_array2 = getdate($t_now-24*3600);
        $day2=$t_array2['mday'];
        $mon2=$t_array2['mon'];
        $year2=$t_array2['year'];
        $today = "$year-$mon-$day";
        $yesterday = "$year2-$mon2-$day2";
        if ($today != $lastday) {
    
                     if ($yesterday != $lastday) $yesterday_c = "0";
                              else $yesterday_c = $today_c;
    
                $today_c = 0;
                $lastday = $today;
        }
        $total_c++;
        $today_c++;
        $total_c     = sprintf("%06d", $total_c);
        $today_c     = sprintf("%06d", $today_c);
        $yesterday_c = sprintf("%06d", $yesterday_c);
        setcookie("countcookie","$day",$t_now+43200);
        $fp=fopen("$file","w");
        fputs($fp, "$total_c,$yesterday_c,$today_c,$lastday");
        fclose($fp);
        fclose($lockfile);
}
if ( empty( $your_c ) ) $your_c = 1;
setcookie("yourcount",$your_c+1,$t_now+43200);
$your_c = sprintf("%06d", $your_c);
//////////////////////////开始统计在线
$filename="online";
$onlinetime=600; //同一IP在线时间，单位：秒
$online_id=file($filename);
$total_online=count($online_id);
$ip=getenv("REMOTE_ADDR");
$nowtime=time();
  for($i=0;$i<$total_online;$i++){
         $oldip=explode("||",$online_id[$i]);
         $hasonlinetime=$nowtime-$oldip[0];
  if($hasonlinetime<$onlinetime and $ip!=$oldip[1]) $nowonline[]=$online_id[$i];
                                  }
         $nowonline[]=$nowtime."||".$ip."||";
         $total_online=count($nowonline);
         $fp=fopen($filename,"w");
         rewind($fp);
         for($i=0;$i<$total_online;$i++){
         fputs($fp,$nowonline[$i]);
         fputs($fp,"n");
                                 }
  fclose($fp);
      if($total_online==0)$total_online=1;
                $total_online = sprintf("%06d", $total_online);
///////////////////////////////////////////////////////
echo "document.write("·总IP访问:".$total_c."");";
echo "document.write("<br>");";
echo "document.write("·昨日访问:".$yesterday_c."");";
echo "document.write("<br>");";
echo "document.write("今日IP:".$today_c."");";
echo "document.write("&nbsp;");";
echo "document.write("·您 访 问:".$your_c."");";
echo "document.write("<br>");";
echo "document.write("当前在线:".$total_online."");";
exit;
?>