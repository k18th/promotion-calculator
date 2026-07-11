<?php
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
$html=file_get_contents(__DIR__.'/calculator.html');
$version=(string)filemtime(__DIR__.'/guest.js');
echo str_replace('</body>','<script src="guest.js?v='.$version.'"></script></body>',$html);
