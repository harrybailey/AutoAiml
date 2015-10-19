<?php

session_start(); 

//header("Cache-control: private");

$AAfilename = 'index.php';

//http://www.pandorabots.com/pandora/pics/aimless/tutorial.htm


?>
<html>

<head>

	<meta name="description" content="An online automatic AIML creator. Designed to make creating AIML files as simple as possible." />
	<meta name="keywords" content="AIML,Auto Aiml,AutoAiml,Online,Creator,automatic." />

	<title>AutoAiml 2.0 - Free Online Aiml Creator</title>

</head>
<body>
<?php

//////////////////////////////////////
//
// AutoAiml rewritten
//
//////////////////////////////////////

?>
	<div style="border:solid 1px #000000; width:400px; padding:0px 10px 0px 10px; font-family:tahoma; font-size:14px;">
		<span>This is version 2.0 of AutoAiml. If you use it regularly, please consider a donation using PayPal:</span>

		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="4732031">
			<input type="image" src="https://www.paypal.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
			<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
		</form>

	</div>
	<div style="width:400px;">&nbsp;</div>
<?php

if($_REQUEST['srai'] === 'create srai')
{
	$pattern = strip_tags($_REQUEST['pattern']);
	$srai    = strip_tags($_REQUEST['sraitext']);
	$srai    = str_replace("*","</star>", $srai);
	$srai    = explode("<br />", preg_replace("@\n|\r@","",nl2br($srai)));
	$first   = 0; 

	foreach($srai as $value)
	{
		if(!$value){continue;}
		$value = str_replace("\n","",$value);
		$spechtml .= "<CATEGORY>\n\t<PATTERN>" . $value . "</PATTERN>\n	<TEMPLATE>\n		<SRAI>" . $pattern . "</SRAI>\n	</TEMPLATE>\n</CATEGORY>\n";
		$lines = $lines+6;
	}
}

if($_REQUEST['stage2now'] === 'show me the aiml for this')
{
	//remove ? ! £ $ % ^ & @ ~ # | from pattern and that

	$pattern = strip_tags(trim($_REQUEST['pattern']));
	$that    = strip_tags(trim($_REQUEST['that']));
	$randoms = $_REQUEST['randoms'];
	$spacer  = $_REQUEST['spacer'];

	$pattern = ereg_replace("[^A-Za-z0-9\* ]", "", $pattern);
	$that = ereg_replace("[^A-Za-z0-9\* ]", "", $that);

	$spechtml .= "<CATEGORY>\n	<PATTERN>" . $pattern . "</PATTERN>\n";
	$lines = 3;

	if($that)
	{
		$spechtml .= "<THAT>" . $that . "</THAT>\n";
		$lines = $lines+3;
	}

	if($randoms == 1)
	{
		$spechtml .= "<TEMPLATE>\n";

		if($_REQUEST['inc_pattern'] == 'yes')
		{
			$spechtml .= $pattern;
		}

		if($spacer == 'space')
		{
			$spechtml .= ' ';
		}
		elseif($spacer == 'dash')
		{
			$spechtml .= ' - ';
		}
		elseif($spacer == 'comma')
		{
			$spechtml .= ', ';
		}
		elseif($spacer == 'this')
		{
			$spechtml .= strip_tags($_REQUEST['thisspacer']);
		}

		$spechtml .= $_REQUEST['random0'] . "\n</TEMPLATE>\n";
		$lines = $line+3;
	}

	if($randoms > '1')
	{
		$spechtml .= "	<RANDOM>\n";
		$lines++;

		for($a = 0;$a < $randoms;$a++)
		{
			$spechtml .= '		<li>';

			if($_REQUEST['inc_pattern'] == 'yes')
			{
				$spechtml .= $pattern;
			}

			switch($spacer)
			{
				case 'space':
				{
					$spechtml .= ' ';
					break;
				}
				case 'dash':
				{
					$spechtml .= ' - ';
					break;
				}
				case 'comma':
				{
					$spechtml .= ', ';
					break;
				}
				case 'this':
				{
					$spechtml .= strip_tags($_REQUEST['thisspacer']);
					break;
				}
			}

			$spechtml .= strip_tags($_REQUEST['random' . $a]);
			$spechtml .= "</li>\n";
			$lines++;

		}

		$spechtml .= "	</RANDOM>\n";
		$lines++;
	}

	$spechtml .= '</CATEGORY>';
	$lines++;
}

if(($_REQUEST['stage2now'] === 'show me the aiml for this') || ($_REQUEST['srai'] === 'create srai'))
{
	$stage3 .= '<div style="border:solid 1px #000000;width:400px; padding:0px 10px 0px 10px; font-family:tahoma; font-size:14px;">';
	$stage3 .= '<u>complete form: (click to highlight (if javascript is on))</u>';
	$stage3 .= '<form method=post action="' . $AAfilename . '" name="autoaiml3" style="margin:0px 0px 5px 0px;">';
	$stage3 .= '<textarea rows="' . $lines . '" style="width:398px;" onclick="this.focus();this.select()" wrap="off">' . htmlspecialchars($spechtml) . '</textarea>';
	$stage3 .= '<div style="border: dashed 1px red; margin: 5px 0px 5px 0px; padding:5px; width:95%;">Your done. To start a new AIML file just submit the first form again or click <a href="' . $AAfilename . '">here</a>.</div>';
	$stage3 .= '<div style="border: dashed 1px red; margin: 10px 0px 10px 0px; padding:5px; width:95%;">Thank you for using AutoAiml. If you use it regularily please consider making a donation. Harry.		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="4732031" /><input type="image" src="https://www.paypal.com/en_GB/i/btn/btn_donate_LG.gif" border="0" style="vertical-align:middle;" name="submit" alt="PayPal - The safer, easier way to pay online." /><img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1" /></form></div>';
	$stage3 .= '</form>';
	$stage3 .= '</div>';
}

if($_REQUEST['stage1now'] == 'submit')
{
	// put the proper form together

	$stage2 .= '<div style="border:solid 1px #000000; width:400px; padding:0px 10px 0px 10px; font-family:tahoma; font-size:14px;">';
	$stage2 .= '<u>complete form:</u>';
	$stage2 .= '<form method=post action="' . $AAfilename . '" name="autoaiml2" style="margin:0px 0px 5px 0px;">';
	$stage2 .= '<input type="hidden" name="randoms" value="' . $_REQUEST['randoms'] . '">';
	$stage2 .= '<input type="hidden" name="codelocation" value="' . $_REQUEST['codelocation'] . '">';
	$stage2 .= '<p>Pattern:<br />';
	$stage2 .= '<input type="text" name="pattern" style="width:398px;">';

	$stage2 .= '<div style="border: dashed 1px #000; padding:5px; width:95%;">Include Pattern<a href="javascript:void(0)" title="include the pattern before each template answer?">?</a> <span style="font-size:10px;">[ yes ]<input type="radio" name="inc_pattern" value="yes">[ no ]<input type="radio" name="inc_pattern" value="no" checked></span></div>';

	$stage2 .= '<div style="border: dashed 1px #000; margin-top:10px; margin-bottom:5px; padding:5px; width:388px;">Spacer<a href="javascript:void(0)" title="include a spacer before the answer / between answer and template?">?</a> <span style="font-size:10px;"><input type="radio" name="spacer" value="space">[ space ]<input type="radio" name="spacer" value="dash">[ - ]<input type="radio" name="spacer" value="comma">[ , ]<input type="radio" name="spacer" value="this"><input type="text" name="thisspacer" value="" size="5" maxlength="50">(your own)<input type="radio" name="spacer" value="no" checked>[ no ]</span></div>';

	if($_REQUEST['inc_that'] == 'yes')
	{
		$stage2 .= '<p>That:<br /><input type="text" name="that" style="width:398px;"></p>';
	}

	if($_REQUEST['randoms'] == '1')
	{
		$stage2 .= 'template';
	}
	else
	{
		$randoms = 'yes';
	}

	for($a = 0;$a < $_REQUEST['randoms'];$a++)
	{
		$rows = '10';
		if($randoms == 'yes') 
		{
			$stage2 .= 'random ' . ($a+1) . ':<br />';
			$rows = '5';
		}
		$stage2 .= '<textarea name="random' . $a . '" rows="' . $rows . '" style="width:398px;"></textarea>';
	}

	$stage2 .= '<p><input type="submit" name="stage2now" value="show me the aiml for this"></p>';
	$stage2 .= '</form>';
	$stage2 .= '</div>';
}

if(($_REQUEST['codelocation'] == 'above') || (!$_REQUEST['codelocation']))
{
	print $stage2;
	print $stage3;

?>
	<div style="width:400px;">&nbsp;</div>
<?php

}

?>
	<div style="border: solid 1px #000000; width:400px; padding:0px 10px 0px 10px; font-family:tahoma; font-size:14px;">
	<u>create aiml string:</u>
	<form method="post" action="<?php echo $AAfilename; ?>" name="autoaiml" style="margin:0px 0px 5px 0px;">
		<p>RANDOMS:
		<select name="randoms" style="font-family:tahoma;font-size:14px;">
			<option value="1">NO</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
		</select>
		</p>
		<p>
		<div style="border: dashed 1px #000; padding:5px; width:95%;">Include THAT? <span style="font-size:10px;">[ yes ]<input type="radio" name="inc_that" value="yes">[ no ]<input type="radio" name="inc_that" value="no" checked></span></div>
		</p>
		<input type="submit" name="stage1now" value="submit"><span style="font-size:10px;"> Show code<input type="radio" name="codelocation" value="above" id="above" checked><label for="above">[ above ]</label><input type="radio" name="codelocation" value="below" id="below"><label for="below">[ below ]</label> these options</span>
	</form>
</div>
<?php

if($_REQUEST['codelocation'] == 'below')
{

?>
	<div style="margin:auto;width:400px;">&nbsp;</div>
<?php

	print $stage2;
	print $stage3;
}

?>
<div style="width:400px;padding:10px;font-family:tahoma;font-size:14px;">
	OR:
</div>
<div style="border: solid 1px #000000; width:400px; padding:0px 10px 0px 10px; font-family:tahoma; font-size:14px;">
	<form method="post" action="<?php echo $AAfilename; ?>" name="srai" style="margin:0px 0px 5px 0px;">
		<u>create sria:</u><br />
		<p>pattern:<br /><input type="text" name="pattern" style="width:398px;" /><br />srai(match to)(one per line please): <br /><textarea name="sraitext" rows="10" cols="46" style="width:398px;" wrap="off"></textarea></p>
		<input type="submit" name="srai" value="create srai">
	</form>
</div>

<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
<script type="text/javascript">_uacct = "UA-221234-4";urchinTracker();</script>

</body>
</html>