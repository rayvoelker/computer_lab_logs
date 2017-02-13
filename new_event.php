<pre>
<?php

/*
	This script expects a url formated like the one in the example below
	https://131.238.109.70/?
		ip=131.238.1.100
		&username=username
		&computername=DEC-00
		&event=logon

sample SQL statement

INSERT
INTO `lablogs`.`raw`
(
`id`,
`ip`,
`username`,
`computername`,
`event`,
`timestamp`,
`duration`
)

VALUES
(
NULL,
INET_ATON('131.238.109.70'),
'rvoelker1',
'DEC-99',
'3',
now(),
''
);


*/

/*
connect to the database
*/
$user = 'lablogs';
$pass = 'PUT_PASSWORD_HERE';

$dsn = "mysql:"
        . "host=localhost;"
        . "dbname=lablogs;"
        . "charset=utf8;";
try {
	$connection = new PDO($dsn, $user, $pass);
}

catch ( PDOException $e ) {
	echo "problem connecting to database...\n";
	error_log('PDO Exception: '.$e->getMessage());
	exit(1);
}

//defined CONSTANTS for events (tiny int) in our database
define("STARTUP",	1);
define("SHUTDOWN",	2);
define("LOGON",		3);
define("LOGOFF",	4);

/*
most likely don't want to use the client reported IP, instead grab
what the server reports as the client's IP address
*/

//$ip = htmlspecialchars ($_GET["ip"]);
$ip = $_SERVER['REMOTE_ADDR'];

$computername = substr( htmlspecialchars ($_GET["computername"]), 0, 7);
$username = substr( htmlspecialchars ($_GET["username"]), 0, 128 );

$reported_event = htmlspecialchars ($_GET["event"]);
if ($reported_event == "startup") {
	$event = 1;
}

elseif ($reported_event == "shutdown") {
	$event = 2;
}

elseif ($reported_event == "logon") {
	$event = 3;
}

elseif ($reported_event == "logoff") {
	$event = 4;
}

else {
	$event = 0;
}




//"%v %h %l %u %t \"%r\" %>s %b" vhost_common


/*

`id`,
`ip`,
`username`,
`computername`,
`event`,
`timestamp`,
`duration`

*/

	$sql =	"INSERT INTO lablogs.raw VALUES (NULL, INET_ATON('" . $ip . "'), '" . $username  . "', '" . $computername . "', '" . $event . "', NOW(), 0)";

	//echo "\n";
	//echo $sql;
	//echo "\n";


	$connection->query($sql);


/*
	echo $ip;
	echo "\t";
	echo $computername;
	echo "\t";
	echo $username;
	echo "\t";
	echo $event;
	echo "\t";
	echo date(DATE_RFC2822);
*/


?>
</pre>
