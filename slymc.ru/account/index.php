<?php
const PAGE_TITLE = "Личный кабинет";
const HEADER_TYPE = "accountPage";

require_once "headerMain.php";

/*if(!($user = User::getByToken($_COOKIE["candy"]))||!$user->getToken()||!$user->getToken()->isValid("web")||!$user->checkSign()||$user->getRole()>2) {
	header('Location: ../');
	die;
}*/
if (IS_USER_LOGGED){
	echo "Юзер";
}else{
	header('Location: ../');
	die;
}

?>
<style>

.skinRect {
    align-items: center;
    width: 308px;
    height: 486px;
    left: 63px;
    top: 354px;
    background: inherit;
    border: 2px solid #7a7a7a;
    box-sizing: border-box;
    border-radius: 44px;
}

.playerProfileRect {
    align-items: center;
    width: 800px;
    height: 486px;
    left: 63px;
    top: 354px;
    background: inherit;
    border: 2px solid #7a7a7a;
    box-sizing: border-box;
    border-radius: 44px;
    margin-left: 50px;
}

.playerProfileText {
    margin-top: 30px;
    margin-left: 40px;
    font-family: Comfortaa, serif;
    font-style: normal;
    font-weight: normal;
    font-size: 30px;
}

.playerProfileInput {
    margin-left: 10px;
    height: 20px;
    border: 2px solid #7a7a7a;
    color: inherit;
    border-radius: 50px;
    font-family: Comfortaa, serif;
    background-color: inherit;
}

.skinText {
    text-align: center;
    font-family: Comfortaa, serif;
    font-weight: normal;
    font-size: 30px;
    margin: 15px auto;
}

</style>
<div style="background-color: rgba(0, 0, 0, 0.65); width: 1400px; margin-left: auto; margin-right: auto; padding-top: 60px; padding-bottom: 200px;">
<div style="margin-top: 110px">
    <h1 class="h1"> Личный кабинет</h1>
    <h2 class="h2">Здесь вы можете редактировать информацию о игроке</h2>
</div>
<table style="margin-left: auto; margin-right: auto; width: fit-content;">
    <td>
    <div class="skinRect">
        <h4 class="skinText">Ваш скин и плащ:</h4>
        <div class="line" style="height: 0;"></div>
        <div id="skin">
        <canvas id="canvas" style="display: none" width="64" height="64"></canvas>
        </div>
    </div>
    </td>
    <td>
        <div class="playerProfileRect">
            <div class="playerProfileText">Ваш никнейм:<span><?php echo$GLOBALS['USER']->getDisplayUsername()?></span>
            <a href="" class="playerProfileText">Изменить</a></div> 
            <div class="playerProfileText">Лицензия на HD-скин и плащ:<span> <?php echo ($GLOBALS['USER']->isLicense() ? 'Есть' : 'Нет') ?></span></div>
            <div class="playerProfileText">Ваши привилегии: Classic (истекает через 21 день) <a href="" class="playerProfileInput">Продлить</a></div>
            <div class="playerProfileText">Ваши рефералы: 5 (активных 2)</div>
        </div>
    </td>
</table>

<script>
function preventScroll(e){
    e.preventDefault();
    e.stopPropagation();

    return false;
}


document.getElementById("skin").addEventListener("wheel", preventScroll);
</script>
<script src="three.js"></script>
</div>
<script>
	<?php
//include "three.min.js";
/*$a = json_decode(file_get_contents('Coverage.json'));
$sText = $a[1]->text;
$sOut = "";
foreach ($a[1]->ranges as $iPos => $oR) {
	$sOut .= substr($sText, $oR->start, ($oR->end-$oR->start))." \n";
}
file_put_contents("three.min.js", $sOut);*/
include "skin.js";
?>
</script>







