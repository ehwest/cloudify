<?
$querystring = $_SERVER["QUERY_STRING"];

$daten = addslashes(trim($_REQUEST['TWIdata']));
$imsi  = addslashes(trim($_REQUEST['IMSI']));
$mfr   = addslashes(trim($_REQUEST['MFR']));
$poll  = addslashes(trim($_REQUEST['POLL']));
$mdl   = addslashes(trim($_REQUEST['MDL']));
$rev   = addslashes(trim($_REQUEST['REV']));
$msn   = addslashes(trim($_REQUEST['MSN']));
$dmq   = addslashes(trim($_REQUEST['DMQ']));
$dma   = addslashes(trim($_REQUEST['DMA']));
$dmf   = addslashes(trim($_REQUEST['DMF']));
$dmp   = addslashes(trim($_REQUEST['DMP']));
$ver   = addslashes(trim($_REQUEST['VER']));
$sn   = addslashes(trim($_REQUEST['SN']));
$records   = addslashes(trim($_REQUEST['RECORDS']));
$datestyle = addslashes(trim($_REQUEST['DATESTYLE']));
$time= addslashes(trim($_REQUEST['TIME']));
$set = addslashes(trim($_REQUEST['SET']));

$ip=$_SERVER["REMOTE_ADDR"];
unset($_POST);

$info  = "IMSI=";
$info .= $imsi . "|";
$info .= "MFR=" . $mfr . "|";
$info .= "MDL=" . $mdl . "|";
$info .= "REV=" . $rev . "|";
$info .= "MSN=" . $msn . "|";
$info .= "POLL=" . $poll . "|";
$info .= "TWIdata=" . $daten . "|";
$info .= "DMQ=" . $dmq . "|";
$info .= "DMA=" . $dma . "|";
$info .= "DMF=" . $dmf . "|";
$info .= "DMP=" . $dmp . "|";
$info .= "VER=" . $ver . "|";
$info .= "SN=" . $sn . "|";
$info .= "VER=" . $ver . "|";
$info .= "DATESTYLE=" . $datestyle . "|";
$info .= "TIME=" . $time . "|";
$info .= "SET=" . $set . "|";
$info .= "RECORDS=" . $records. "";
//first write the log
$fd = fopen("log.txt","a+");
fwrite($fd,"\nContact: " . date("n/j/Y H:i:s A") . " -------from $ip---------------" . $info . "\r\n");
fclose($fd);

//now build a response
print "\ndHost Cloud says you are at $ip and at " . date("n/j/Y H:i A") . " and you sent this: \n" . $info . "\n";

print "<content>DM?\r\n</content>";
print "<control>AT+CIMI\r\n</control>";
?>
