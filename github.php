<?php
$out = shell_exec("/usr/bin/git pull 2>&1");
var_dump($out);
