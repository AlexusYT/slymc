<?php
if(!defined("DIALOG_CLOSE_BUTTON")) define("DIALOG_CLOSE_BUTTON", true);
?>

<div id="<?php echo "popup_".$GLOBALS["DIALOG_NAME"]?>" class="popupDialog">
	<div class="popupDialog-content">
		<?php
		if(DIALOG_CLOSE_BUTTON) {
			echo '<div id="close" style="height: 20px; width: 20px; background: red; cursor: pointer;margin-left: auto;padding-left: 5px;padding-bottom: 1px;padding-top: 2px;" onclick="';
			echo "document.getElementById('popup_".$GLOBALS["DIALOG_NAME"] . "').remove()";
			echo '"> X </div>';
		}
		?>
		<div id="<?php echo "popupContent_".$GLOBALS["DIALOG_NAME"]?>">
			<?php
				echo $GLOBALS["DIALOG_CONTENT"];
			?>
		</div>
	</div>
</div>