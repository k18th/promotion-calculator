<?php
$html=file_get_contents(__DIR__.'/calculator.html');
echo str_replace('</body>','<script src="guest.js?v=1"></script></body>',$html);

