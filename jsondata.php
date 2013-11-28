<?php
	ob_start();
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	header("text/plain");
	ob_end_flush();
        $dbname = "cloudify";
        $dbhostname="db02.transactionalweb.net";
        $dblogin = "cloudify";
        $dbpassword="cloudify";

	$uid=$_REQUEST['uid'];

        if( !($dbLink = mysql_connect($dbhostname, $dblogin, $dbpassword))) {
                 print("Failed phase1 to connect to server!\n");
                 print("Request Aborted!\n");
                 exit();
        }

        if( ! mysql_select_db($dbname, $dbLink) ) {
                 print("Failed phase2 to connect to database on server!<BR>\n");
                 print("Request Aborted!\n");
                 exit();
        }

	if($uid=="")   $q2 = "SELECT * from parsedata order by tod; ";
	if($uid > 0)   $q2 = "SELECT * from parsedata where uid='" . $uid . "' order by tod; ";
	//print "uid=$uid";
	//print "q2=$q2";
	$dbr2 = mysql_query($q2);
	$n2 = mysql_num_rows($dbr2);
	while($row2=mysql_fetch_object($dbr2)){
		if(trim($row2->tod) !="" || 
		   trim($row2->value) != ""){
			unset($record);
			$record['date'] = trim($row2->tod);
			$record['value'] = trim($row2->value);
			$allrecords[] = $record;
			$n++;
		}//if not null
	}

	if(is_array($allrecords)){
	  //$s = 'var json= [';
	  $s = '[';
	  $s .= "\n";
	  foreach($allrecords as $key=>$onerecord){
		//$s .= "\t";
		$s .= '{"date": ';
		$s .= '"' . date("Y-m-d",$onerecord['date']) . "T" . date("H:m:s",$onerecord['date']) . '"';
		$s .= ',	"sugar": ';
		$s .= '"' . $onerecord['value'] . '"';
		$s .= "}";
		if($key < ($n - 1)){
			$s .= ", \n";
			}else {
			$s .= " \n";
			}
	    }//foreach
		$s .= "\t]\n";
	        print $s;
	}//is array
?>
