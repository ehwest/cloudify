<?
	$contents = file_get_contents("rawfile.txt");
	print $contents;

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

	$q1 = "INSERT into rawdatapolled set ";
	$q1 .= " rawcontent='" . base64_encode($contents)  . "', ";
	$q1 .= " tod='" . $nowtime . "', ";
	$q1 .= " keyused='" . $nowtime . "'; ";
	$dbr1 = mysql_query($q1);
?>
