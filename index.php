<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ACGME Annual Update Error Screener</title>
<!-- 
<link href="../css/themes/HFEM.min.css" rel="stylesheet" type="text/css" />
<link href="../css/themes/jquery.mobile.icons.min.css" rel="stylesheet" type="text/css" />
<link href="http://code.jquery.com/mobile/1.4.5/jquery.mobile.structure-1.4.5.min.css" rel="stylesheet" type="text/css">
-->
<link href="../inc/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css" />
<link href="css/index.css" rel="stylesheet" type="text/css">
<script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="../inc/jquery-ui/jquery-ui.min.js"></script>
<!-- <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script> -->
<script src="js/index.js"></script>
</head>

<body>
<div id="modaldialog"></div>
<div id="page">
<div id="header">
<p>ACGME Annual Update Error Screener</p>
</div>
<div id="content">
    <div id="form_div">
        <form id="adsform" name="adsform" method="post">
            <p>
            <textarea rows="10" name="adstext" id="adstext" placeholder="Cut and Paste block of text from ADS" required></textarea>
            </p>
            <div id="progressbar"><div class="progress-label">Loading...</div></div>
            <p><label for="acyr">Applicable academic year:</label>
            <select name="acyr" id="acyr" data-mini="true" data-native-menu="false">
<?php 
date_default_timezone_set("America/Detroit");
for ($i=date('Y')-2; $i<date('Y')+1; $i++) {
	echo "\t<option value='$i'";
	if ($i==(date('Y')-1)) echo " selected";
	echo ">$i-".($i+1)."</option>\r\n";
}
?>
            </select>
              <p>&nbsp;</p>
            <input type="button" name="doit" id="doit" value="Check Data">
		    <input type="reset" name="clear" id="clear" value="Reset">
        </form>
    </div>
    <div id="results">
	  <h2>Results</h2>
      <div id="tabs">
      <ul>
      <li><a href="#pmid">PMID Check<span id="pmid_icon"></span></a></li>
      <li><a href="#license">State License Check<span id="license_icon"></span></a></li>
      <li><a href="#boards">Board Certification Check<span id="boards_icon"></span></a></li>
      </ul>
        <div id="pmid">
  <table border="0" cellpadding="0" cellspacing="0" id="pmid_table">
  <thead>
    <tr>
      <th scope="col" style="width: 8em">PMID</th>
      <th scope="col">Date Published</th>
      <th scope="col">Authors</th>
      <th scope="col">Title</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
	<p>Suspect PMIDs will be flagged <strong style="color:#F00">red</strong> or <strong style="color:#CBD000">yellow</strong>. All-white background indicates no errors were detected</p>
        </div>
        <div id="license">
        <table border="0" cellpadding="0" cellspacing="0" id="lic_table">
        <tbody>
        </tbody>
        </table>
        </div>
        <div id="boards">
          <table border="0" cellpadding="0" cellspacing="0" id="board_table">
          <thead>
            <tr>
              <th scope="col">Faculty</th>
              <th scope="col">Specialty</th>
              <th scope="col">Notes</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
	<p>Suspect board certifications will be flagged <strong style="color:#F00">red</strong> or <strong style="color:#CBD000">yellow</strong>. All-white background indicates no errors were detected</p>
        </div>
       </div>
    </div>
</div>
</div>
<footer id="footer">
<p>Please use at your own risk. Contact <a href="mailto:ngoyal1@hfhs.org">Nikhil Goyal</a> with questions or feedback</p>
</footer>
</body>
</html>