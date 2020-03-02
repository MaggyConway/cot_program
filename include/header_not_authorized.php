<header>
	<div class="header__top wrapper">
		<a href="/" class="logo">
			<div class="logo__image"></div>
			<div class="logo__title">Автономное учреждение Омской области дополнительного профессионального образования &laquo;Центр охраны труда&raquo;</div>
		</a>
		<div class="header__top--right">
			<ul class="header__menu">
				<li><a href="#tests">Тесты</a></li>
				<li><a href="#about">О компании</a></li>
				<li><a href="#courses">Курсы</a></li>
			</ul>
			<div class="small_menu_btn btn">Меню</div>
			<div class="small_menu">
				<ul>
					<li><a href="#tests">Тесты</a></li>
					<li><a href="#about">О компании</a></li>
					<li><a href="#courses">Курсы</a></li>
				</ul>
				<div class="small_menu_btn--close"></div>
			</div>
			<a href="#header__lk" class="header__btn btn">Личный кабинет</a>
		</div>
	</div>
	<div class="header__content">
		<div class="inner-wrap">
			<h1>ДИСТАНЦИОННОЕ ОБУЧЕНИЕ</h1>
			<p>Автономное учреждение Омской области дополнительного профессионального образования &laquo;Центр охраны труда&raquo; проводит дистанционное обучение по&nbsp;курсам</p>
		</div>
	</div>
	<div class="wrapper"><div id="header__lk">
		<h3>Вход в личный кабинет</h3>
		
		<?$APPLICATION->IncludeComponent("bitrix:system.auth.form", "main-login", Array(
			// "FORGOT_PASSWORD_URL" => "",	// Страница забытого пароля
			"PROFILE_URL" => "",	// Страница профиля
			// "REGISTER_URL" => "",	// Страница регистрации
			"SHOW_ERRORS" => "N",	// Показывать ошибки
		),
			false
		); ?>
	</div></div>
</header>