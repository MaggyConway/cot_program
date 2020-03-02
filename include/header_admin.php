<header style="margin-bottom: -110px;">
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
			<form action="<?=$arResult["AUTH_URL"]?>">
				<?foreach ($arResult["GET"] as $key => $value):?>
				<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
				<?endforeach?>
				<input type="hidden" name="logout" value="yes" />
				<button type="submit" name="logout_butt" class="header__btn btn logout">Выход</button>
			</form>
		</div>
	</div>
	<div class="header__content">
		<div class="inner-wrap">
			<h1>ДИСТАНЦИОННОЕ ОБУЧЕНИЕ</h1>
			<p>Автономное учреждение Омской области дополнительного профессионального образования &laquo;Центр охраны труда&raquo; проводит дистанционное обучение по&nbsp;курсам</p>
		</div>
	</div>
</header>