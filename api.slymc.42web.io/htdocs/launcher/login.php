<?php

include_once "../prependFile.php";

if(count(array_diff_assoc(['authToken', 'data'], array_keys($_POST)))>0) sendError("invalid_request");

