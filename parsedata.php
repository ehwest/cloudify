<?

        $dbname = "cloudify";
        $dbhostname="db02.transactionalweb.net";
        $dblogin = "cloudify";
        $dbpassword="cloudify";

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
	$nowtime = time();
	$filecontent = base64_encode( file_get_contents($_FILES['userfile']['tmp_name']) );

	$q1 = "SELECT * from rawdatapolled; ";
	$dbr1 = mysql_query($q1);
	print $q1;
	while($row1=mysql_fetch_object($dbr1)){

		$filecontents = base64_decode($row1->rawcontent);
		//print $filecontents;
		$parts = explode("\n",$filecontents);
		//print_r($parts);
		foreach($parts as $onepart){
			$lineparts = explode("\t",$onepart);
			print_r($lineparts);
			$t = time2epoch($lineparts[0]);
			print date("n/j/Y H:i:s",$t);
			print "\n";
		}
		print "\n\n";

	}

function time2epoch($s){
    //2011-01-13T15:42:08
	$parts = explode("T",$s);
	$dateparts = explode("-",$parts[0]);
	$timeparts = explode(":",$parts[1]);
	$t = mktime($timeparts[0],$timeparts[1],$timeparts[2],$dateparts[1],$dateparts[2],$dateparts[0]);
	return($t);

}
?>
