<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>

<head>
<title>Homeplaneta - Recover Your Management E-mail</title>
<META NAME="Description" CONTENT="Recover your Homeplaneta Management E-mail">
<meta name="keywords" content="homes for rent, homes for sale, planet, list your own property">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="print.css" media="print">
<link rel="shortcut icon" href="/images/house.ico">
<link rel="icon" type="image/ico" href="/images/house.ico">
<script language="javascript" src="functions2.js"></script>
<script type=\"text/javascript\">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-20209090-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>

<body>

<div class="frame">
<a href="http://homeplaneta.com"><img src="/images/homeplanetaLarge.gif" alt="homeplaneta" vspace="6" border='0' /></a>

<div class="toptext">
<h3>Need to recover your Management E-mail(s)?</h3>
Did you lose your Management E-mail (or E-mails plural?)<br /><br />Simply plug in your e-mail address below -- the same one you used when you filled out your listing -- and we'll send an e-mail to
that account with links to the various listings under your control.
</div>

<div class='content'>
<br /><br />
<form name='RecoveryForm'>
<div class='formBoxLeft' style='margin-bottom: 10px;'>Enter your e-mail address:
<span class='small'>Give the e-mail address you used when you created the listing(s)</span></div>
<div class='formBoxRight'><input type='text' name='RecoveryEmailAddress' size='30' /></div>
<br />
<input type="button" type="submit" onclick="recoverEmail(); return false;" value="Submit">
<div id="resultmsg"></div>
</form>
</div>

</div>
<?php include 'footer.php'; ?>
</body>

</html>
