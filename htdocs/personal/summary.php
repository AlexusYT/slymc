<?php


?>
	<h1><?php echo CATEGORY_NAME?></h1>
    <div class="box">
        <h2 class="boxHeader">Аккаунтов</h2>
        <table class="boxTable">
            <tr class="boxTableRow"><td><p>всего зарегистрировано: </p></td><td>
                    <?php echo Utils::getDb()->getOne("SELECT COUNT(*) FROM players")?>
                </td></tr>
            <tr class="boxTableRow"><td><p>сейчас на сервере: </p></td><td>
		            <?php echo Utils::getDb()->getOne("SELECT COUNT(*) FROM playersOnServers WHERE playersOnServers.OnServer = 1")?>
                </td></tr>
            <tr class="boxTableRow"><td><p>с неподтвержденной почтой: </p></td><td>
	                <?php echo Utils::getDb()->getOne("SELECT COUNT(*) FROM players WHERE PlayerID NOT IN (1,2) AND players.MailVerified = 0")?>
                </td></tr>
            <tr class="boxTableRow"><td><p>с активом (30 дней): </p></td><td>
	                <?php echo Utils::getDb()->getOne("SELECT COUNT(*) FROM players WHERE PlayerID NOT IN (1,2) AND players.LastOnline BETWEEN DATE_SUB(UTC_TIMESTAMP, INTERVAL 30 DAY) AND UTC_TIMESTAMP")?>
                </td></tr>
            <tr class="boxTableRow boxTableRowImportant"><td><p>с ошибками подписи: </p></td><td>
	                <?php echo Utils::getDb()->getOne("SELECT COUNT(*) FROM players WHERE PlayerID NOT IN (1,2) AND players.Sign <> 
                        SHA2(CONCAT(players.Role, players.Username, players.DisplayUsername, players.Mail, players.MailVerified, players.Password, players.TwoFA, 
                            players.DonateMoney, players.Money, players.HWID, players.ForumStatus, \"|s|gj8:265JG9>;CK7Pgi0iv_F.,C>aj\"), 512)")?>
                </td></tr>
        </table>
    </div>
    <div class="box">
        <h2 class="boxHeader">Аккаунтов</h2>
        <table class="boxTable">
            <tr class="boxTableRow"><td><p>всего зарегистрировано: </p></td><td>0</td></tr>
            <tr class="boxTableRow"><td><p>сейчас на сервере: </p></td><td>0</td></tr>
            <tr class="boxTableRow"><td><p>с неподтвержденной почтой: </p></td><td>0</td></tr>
            <tr class="boxTableRow"><td><p>с активом (30 дней): </p></td><td>0</td></tr>
            <tr class="boxTableRow boxTableRowImportant"><td><p>с ошибками подписи: </p></td><td>0</td></tr>
        </table>
    </div>
<?php
