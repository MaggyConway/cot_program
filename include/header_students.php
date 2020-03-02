<?$arGroupAvalaibleAdmin = array(1);
$arGroups = CUser::GetUserGroup($USER->GetID());
$result_intersect_admin = array_intersect($arGroupAvalaibleAdmin, $arGroups);?>

<header class="students">
	<div class="header__top wrapper">
	<? //echo var_dump($result_intersect_admin); ?>
		<? if (!empty($result_intersect_admin)) { ?>
			<a href="/" class="logo">
				<div class="logo__image"></div>
				<div class="logo__title">Автономное учреждение Омской области дополнительного профессионального образования &laquo;Центр охраны труда&raquo;</div>
			</a>
		<? }  elseif (empty($result_intersect_admin)) { ?>
			<a href="/tests/" class="logo">
				<div class="logo__image"></div>
				<div class="logo__title">Автономное учреждение Омской области дополнительного профессионального образования &laquo;Центр охраны труда&raquo;</div>
			</a>
		<? } ?>
		<div class="header__top--right">
			<ul class="header__menu">
				<li><a href="/tests/#tests_page" onclick="smoothToBlock()">Тесты</a></li>
				<li><a href="/tests/#results" onclick="smoothToBlock()">Результаты</a></li>
			</ul>
			<div class="small_menu_btn btn">Меню</div>
			<div class="small_menu">
				<ul>
					<li><a href="/tests/#tests_page" onclick="smoothToBlock()">Тесты</a></li>
					<li><a href="/tests/#results" onclick="smoothToBlock()">Результаты</a></li>
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