<?php
// check for board certification validity
date_default_timezone_set("America/Detroit");

function err($code, $text) {
	global $rVal;
	$rVal['error']['code']=$code;
	$rVal['error']['text']=$text;
	echo json_encode($rVal);
	exit($code);
}

function board_check($type, $init, $recert) {
	// process board dates - return 0 if okay, 1 if hard fail, 2 if soft fail
	$rVal=array("code" => 0, "error" => "");
	$thisyear=date("Y");
	switch ($type) {
		case "R":
			if (($recert+0)===0) {$rVal['code']=1; $rVal['error']="No recert year listed";}
			else if (($thisyear - $recert) > 10) {$rVal['code']=1; $rVal['error']="Recert possibly expired"; }
			else {$rVal['code']=2; $rVal['error']="Should this be 'M' (participating in MOC)?";}
			return $rVal;
			break;
			
		case "O":
			if (($thisyear - $init) > 10) {$rVal['code']=1; $rVal['error']="Original cert possibly expired. Use 'N' if time-unlimited certification"; }
			return $rVal;
			break;
			
		case "L":
			$rVal['code']=1; $rVal['error']="Certification lapsed"; 
			return $rVal;
			break;

		case "N":
			if ($init > 2000) {$rVal['code']=1; $rVal['error']="Confirm time-unlimited certification"; }
			return $rVal;
			break;
			
		case "M":
			return $rVal;
			break;
			
		default:
			$rVal['code']=1; $rVal['error']="Processing Error"; 
			return $rVal;
			break;		
	}
}

$rVal=array(
	"error" => array (
		"code" => 0, 
		"text" => ""
	),
	"results" => array ()
);

if ((!isset($_POST['boardblock'])) || (strlen($_POST['boardblock'])<10)) err(1, "Missing board text block");

// this one splits up the table into one row per faculty
$split=preg_split('/([\w-]+ \w+),.+\s+\([^\)]+\)\D*\d\s+(\D*)(\d{4})\s+([ROLNM])(\s+-?\s*(?:\d{4})?)(?:\s*\d{1,2}){5}\s*/', $_POST['boardblock'], 0, PREG_SPLIT_DELIM_CAPTURE);
if (count($split)===1) err(2, "Preg_split failed");
//print_r($split); 

$j=0;
for ($i=1; isset($split[$i]); $i+=6) {
	$match[$j]['name']=$split[$i];
	$match[$j]['specialty']=trim(preg_replace("/\s/", " ", $split[$i+1]));
	$match[$j]['init_cert']=$split[$i+2];
	$match[$j]['type']=$split[$i+3];
	$match[$j]['recert']=trim($split[$i+4]);
	$match[$j]['remainder']=trim($split[$i+5]); 	// the remainder of the text on this row may have secondary board info

	// look in remainder text for second board
	$matches=array();
	$ret=preg_match('/(\D+)(\d{4})\s+([ROLNM])(\s+-?\s*(?:\d{4})?)/',$split[$i+5],$matches);
	if ($ret===1) {
		$match[$j]['sec_specialty']=trim(preg_replace("/\s/", " ", $matches[1]));
		$match[$j]['sec_init_cert']=$matches[2];
		$match[$j]['sec_type']=$matches[3];
		$match[$j]['sec_recert']=$matches[4];
	}
	$j++;
}

// print_r($match); exit;
	
$j=0;
foreach($match as $i => $m) {
	// look through cert type - R, O, L, N, M. First 3 are problematic. N or M is cool
	$rVal['results'][$j]['name']=$m['name'];
	$rVal['results'][$j]['specialty']=$m['specialty'];
	$r=board_check($m['type'], $m['init_cert'], $m['recert']);
	$rVal['results'][$j]['issues']=$r['code'];
	if ($r['code']!==0) $rVal['results'][$j]['descr']=$r['error'];

	if (isset($m['sec_type'])) {
		// has a secondary board info - validate
		$j++;
		$rVal['results'][$j]['name']=$m['name'];
		$rVal['results'][$j]['specialty']=$m['sec_specialty'];
		$r=board_check($m['sec_type'], $m['sec_init_cert'], $m['sec_recert']);
		$rVal['results'][$j]['issues']=$r['code'];
		if ($r['code']!==0) $rVal['results'][$j]['descr']=$r['error'];
	}
	$j++;
}

header('Content-Type: application/json');
echo json_encode($rVal);
exit(0);
?>