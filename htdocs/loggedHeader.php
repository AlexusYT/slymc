<div style="position: fixed;top: 0px;">
<div class="headerNew" style="position: fixed;justify-content: center;width: 100%;padding-top: 15px;padding-bottom: 10px;">
    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="72" height="100" viewBox="-11.595 -13.004 331.19 526.008" style="margin-top: -15px;">
        <defs>
            <style>
                .logo1 {
                    fill: #23301c;
                    fill-rule: evenodd;
                    filter: url(#filter);
                }
            </style>
            <clipPath id="clip-path">
                <rect width="308" height="500"/>
            </clipPath>
            <filter id="filter" x="0.063" y="3.875" width="302.719" height="493.656" filterUnits="userSpaceOnUse">
                <feImage preserveAspectRatio="none" x="0.0625" y="3.875" width="302.719" height="493.656" result="image" xlink:href="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB3aWR0aD0iMzAyLjcxOSIgaGVpZ2h0PSI0OTMuNjU2IiB2aWV3Qm94PSIwIDAgMzAyLjcxOSA0OTMuNjU2Ij4KICA8ZGVmcz4KICAgIDxzdHlsZT4KICAgICAgLmNscy0xIHsKICAgICAgICBmaWxsOiB1cmwoI2xpbmVhci1ncmFkaWVudCk7CiAgICAgIH0KICAgIDwvc3R5bGU+CiAgICA8bGluZWFyR3JhZGllbnQgaWQ9ImxpbmVhci1ncmFkaWVudCIgeDE9IjE1MS4zNTkiIHkxPSI0OTMuNjU2IiB4Mj0iMTUxLjM1OSIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiPgogICAgICA8c3RvcCBvZmZzZXQ9IjAiIHN0b3AtY29sb3I9IiNjNjM3OWMiLz4KICAgICAgPHN0b3Agb2Zmc2V0PSIxIiBzdG9wLWNvbG9yPSIjM2M2NWFhIi8+CiAgICA8L2xpbmVhckdyYWRpZW50PgogIDwvZGVmcz4KICA8cmVjdCBjbGFzcz0iY2xzLTEiIHdpZHRoPSIzMDIuNzE5IiBoZWlnaHQ9IjQ5My42NTYiLz4KPC9zdmc+Cg=="/>
                <feComposite result="composite" operator="in" in2="SourceGraphic"/>
                <feBlend result="blend" in2="SourceGraphic"/>
            </filter>
        </defs>
        <g clip-path="url(#clip-path)">
            <path id="Arrow_06_-_Shapes4FREE_1" data-name="Arrow 06 - Shapes4FREE 1" class="logo1" d="M302.769,244.491L151.421,497.527,0.072,244.491H71.959L151.525,3.884,230.9,244.491h71.874ZM151.522,3.873h0.007l0,0.011Z"/>
        </g>
    </svg>
    <div style="display: flex;">
        <p class="headerText">SlyMC</p>
        <div class="header headerRect">
            <a class="line headerButtons" href="/play"> Начать играть</a>
            <div class="line"></div>
            <a class="line headerButtons" href="/servers">Список серверов</a>
            <div class="line"></div>
            <a class="line headerButtons" href="/forum.php">Форум проекта</a>
        </div>
        <div class="header" onmouseleave="hidePanel()" style="margin-left: 50px;">
            <div id="avatar" class="headerAvatar" onmouseenter="showPanel()">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="55" viewBox="0 0 820 873">
                    <defs>
                        <style>
                            .cls-1, .cls-2 {
                                fill: none;
                                stroke: #000;
                                stroke-width: 40px;
                            }

                            .cls-1 {
                                fill-rule: evenodd;
                            }
                        </style>
                    </defs>
                    <path id="Эллипс_1" data-name="Эллипс 1" class="cls-1" d="M410,89.309c138.018,0,249.9,111.886,249.9,249.9S548.02,589.118,410,589.118,160.1,477.232,160.1,339.213,271.983,89.309,410,89.309Z"/>
                    <circle id="Эллипс_1-2" data-name="Эллипс 1" class="cls-2" cx="410" cy="999.065" r="398.844"/>
                </svg>

            </div>
            <div id="actionPanel" class="disabled" style="position: absolute; margin-left: -25px;">

                <a class="headerAction " onclick="actionHeaderOnClick('login')">Войти</a>
                <div class="line" style="height: 0; width: 135px; margin-left: 27px;"></div>
                <a class="headerAction" onclick="actionHeaderOnClick('register')">Регистрация</a>

            </div>
            <div id="headerRectLogin" class="headerRectLogin disabled">
                <form action="login.php" method="post">

                    <table>
                        <tr><td><input type="text" class="headerRectInput" name="login" placeholder="Логин или E-Mail"></td></tr>
                        <tr><td><input type="password" class="headerRectInput" name="password" placeholder="Пароль"></td></tr>
                        <tr>
                            <td><input class="headerRectInput" onclick="actionButtonBack('login')" type="reset" value="Назад">
                            <input class="headerRectInput" type="submit" value="Войти"></td>

                        </tr>
                    </table>

                </form>
            </div>
            <div id="headerRectReg" class="headerRectLogin disabled" style="margin-top: 50px;">
                <form action="register.php" method="post">

                    <table>
                        <tr><td><input type="text" class="headerRectInput" name="email" placeholder="E-Mail"></td></tr>
                        <tr><td><input type="text" class="headerRectInput" name="login" placeholder="Логин (ник)"></td></tr>
                        <tr><td><input type="password" class="headerRectInput" name="password" placeholder="Пароль"></td></tr>
                        <tr><td><input type="password" class="headerRectInput" name="passwordR" placeholder="Повторите пароль"></td></tr>
                        <tr>
                            <td><input class="headerRectInput" onclick="actionButtonBack('register')" type="reset" value="Назад">
                                <input class="headerRectInput" type="submit" value="Регистрация"></td>
                        </tr>
                    </table>

                </form>
            </div>
        </div>
    </div>
</div>
</div>