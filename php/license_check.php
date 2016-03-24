<?php
// used by ADS checker to validate date of medical license
function err($code, $text) {
	global $rVal;
	$rVal['error']['code']=$code;
	$rVal['error']['text']=$text;
	echo json_encode($rVal);
	exit($code);
}

$rVal=array(
	"error" => array (
		"code" => 0, 
		"text" => ""
	),
	"months_to_exp" => 0,
	"expired" => 0,
	"month" => '',
	"year" => 0
);

date_default_timezone_set("America/Detroit");
if ((!isset($_POST['licblock'])) || (strlen($_POST['licblock'])<10)) err(1, "Missing license block");
$matches=array();
$ret=preg_match("/(\d{1,2})\/(\d{4})/", $_POST['licblock'], $matches);
if ($ret!==1) err(2, "No match for date pattern m[m]/yyyy");

$now=new DateTime();
try {
	$expiration=new DateTime($matches[2]."-".$matches[1]."-01");
} catch (Exception $e){
	err(3, "Error converting to DateTime");
}

if ($expiration===false) err(3, "Error converting to DateTime");
$interval=$now->diff($expiration);
if ($interval===false) err(3, "Error doing date math");

if ($interval->format('%R')==="-") {$rVal['expired']=1;}
else {
	$mnths=($interval->format('%a')*12)/365;
	$rVal['months_to_exp']=round($mnths);
}

$rVal["year"]=$matches[2];
$rVal["month"]=$expiration->format('F');

header('Content-Type: application/json');
echo json_encode($rVal);
exit(0);
?>