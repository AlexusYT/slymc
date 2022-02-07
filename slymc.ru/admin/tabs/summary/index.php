<style>
    .boxHeader{
        display: table;
        margin: -17px 0 -10px 5px;
    }

    .boxTableRowImportant{
        background: red;
    }
</style>
<div class="box">
    <h2 class="mainBack boxHeader">Аккаунтов</h2>
    <table style="margin-top: 8px;">
        <tr><td><p>всего зарегистрировано: </p></td><td>
                <?php echo Utils::getDb()->getOne("SELECT COUNT(*) FROM users")?>
            </td></tr>
        <tr><td><p>сейчас на сервере: </p></td><td>
                <?php echo Utils::getDb()->getOne("SELECT COUNT(*) FROM playersOnServers WHERE playersOnServers.onServer = 1")?>
            </td></tr>
        <tr><td><p>с неподтвержденной почтой: </p></td><td>
                <?php echo Utils::getDb()->getOne("SELECT COUNT(*) FROM users WHERE userID NOT IN (1,2) AND users.mailVerified = 0")?>
            </td></tr>
        <tr><td><p>с активом (30 дней): </p></td><td>
                <?php echo Utils::getDb()->getOne("SELECT COUNT(*) FROM users WHERE userID NOT IN (1,2) AND users.lastOnlineAt BETWEEN DATE_SUB(UTC_TIMESTAMP, INTERVAL 30 DAY) AND UTC_TIMESTAMP")?>
            </td></tr>
        <tr class="boxTableRowImportant"><td><p>с ошибками подписи: </p></td><td>
                <?php echo Utils::getDb()->getOne("SELECT COUNT(*) FROM users WHERE userID NOT IN (1,2) AND users.sign <>
                    SHA2(CONCAT(users.roleID, users.username, users.displayUsername, users.mail, users.mailVerified, users.password, users.twoFA,
                        users.donateMoney, users.money, users.HWID, users.forumStatus, \"|s|gj8:265JG9>;CK7Pgi0iv_F.,C>aj\"), 512)")?>
            </td></tr>
    </table>
</div>
<!--<div class="box">-->
<!--    <h2 class="mainBack boxHeader">Аккаунтов</h2>-->
<!--    <table style="margin-top: 8px;">-->
<!--        <tr><td><p>всего зарегистрировано: </p></td><td>0</td></tr>-->
<!--        <tr><td><p>сейчас на сервере: </p></td><td>0</td></tr>-->
<!--        <tr><td><p>с неподтвержденной почтой: </p></td><td>0</td></tr>-->
<!--        <tr><td><p>с активом (30 дней): </p></td><td>0</td></tr>-->
<!--        <tr class="boxTableRowImportant"><td><p>с ошибками подписи: </p></td><td>0</td></tr>-->
<!--    </table>-->
<!--</div>-->
