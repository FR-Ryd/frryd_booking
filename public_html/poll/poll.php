<?php
$vote = $_REQUEST['vote'];

//get content of textfile
$filename = "poll_result.txt";
$content = file($filename);

//put content in array
$array = explode("||", $content[0]);
$yes = $array[0];
$no = $array[1];
$idk = $array[2];

if ($vote == 0) {
  $yes = $yes + 1;
}
if ($vote == 1) {
  $no = $no + 1;
}
if ($vote == 2) {
  $idk = $idk + 1;
}

//insert votes to txt file
$insertvote = $yes."||".$no."||".$idk;
$fp = fopen($filename,"w");
fputs($fp,$insertvote);
fclose($fp);
?>

<h3 class="pollheader">Results:</h3>
<table style="min-width:224px;font-size:0.9em;">
<tr>
<td>Yes:</td>
<td style="padding-right:15px">
<img src="images/poll.gif"  style="border:1px solid #444"
width='<?php echo(100*round($yes/($no+$yes+$idk),2)); ?>'
height='15'>
<?php echo(100*round($yes/($no+$yes+$idk),2)); ?>%
</td> 
</tr> 
<tr>
<td>No:</td>
<td style="padding-right:15px">
<img src="images/poll.gif"  style="border:1px solid #444"
width='<?php echo(100*round($no/($no+$yes+$idk),2)); ?>'
height='15'>
<?php echo(100*round($no/($no+$yes+$idk),2)); ?>%
</td>
</tr>
<tr>
<td>I'm not sure:</td>
<td style="padding-right:15px">
<img src="images/poll.gif" style="border:1px solid #444"
width='<?php echo(100*round($idk/($no+$yes+$idk),2)); ?>'
height='15'>
<?php echo(100*round($idk/($no+$yes+$idk),2)); ?>%
</td>
</tr>


</table>