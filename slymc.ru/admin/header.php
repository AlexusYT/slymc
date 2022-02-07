<?php
$GLOBALS["pageStartTime"] = microtime(true);
function onEnd(){

	echo "test";
	echo 'Page loaded in ', number_format((microtime(true) - $GLOBALS["pageStartTime"]), 6), ' seconds <br>';
}
register_shutdown_function('onEnd');
if (!defined('PAGE_TITLE')) {
	define('PAGE_TITLE', "Админ-панель");
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<title>
		<?php echo PAGE_TITLE?>
	</title>
<!--    <link rel="preload" href="styles/adminStyle.css" as="style">-->
<!--    <link rel="stylesheet" href="styles/adminStyle.css" media="print" onload="this.media='all'">-->
<!--    <noscript><link rel="stylesheet" href="styles/adminStyle.css"></noscript>-->
    <style><?php echo file_get_contents("styles/common.css") ?></style>
    <script><?php echo file_get_contents("js/common.js", true) ?></script>

</head>

