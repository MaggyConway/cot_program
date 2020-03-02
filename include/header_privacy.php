<?
$arGroupAvalaibleStudents = array(5);
$arGroupAvalaibleAdmin = array(1);
$arGroups = CUser::GetUserGroup($USER->GetID());
$result_intersect_students = array_intersect($arGroupAvalaibleStudents, $arGroups);
$result_intersect_admin = array_intersect($arGroupAvalaibleAdmin, $arGroups);
global $USER;?>

<header class="students">
	<div class="header__top wrapper">
	<? //echo var_dump($result_intersect_admin); ?>
		<? if (!empty($result_intersect_admin) || !$USER->IsAuthorized() ) { ?>
			<a href="/" class="logo">
				<div class="logo__image"></div>
				<div class="logo__title">Автономное учреждение Омской области дополнительного профессионального образования &laquo;Центр охраны труда&raquo;</div>
			</a>
		<? }  elseif (!empty($result_intersect_students)) { ?>
			<a href="/tests/" class="logo">
				<div class="logo__image"></div>
				<div class="logo__title">Автономное учреждение Омской области дополнительного профессионального образования &laquo;Центр охраны труда&raquo;</div>
			</a>
		<? } ?>
		<div class="header__top--right">
			<? if (!empty($result_intersect_admin) || !empty($result_intersect_students)) { ?>
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
			<? } elseif (!$USER->IsAuthorized()) { ?>
				<ul class="header__menu">
					<li><a href="/#header__lk" onclick="smoothToBlock()">Тесты</a></li>
					<li><a href="/#header__lk" onclick="smoothToBlock()">Результаты</a></li>
				</ul>
				<div class="small_menu_btn btn">Меню</div>
				<div class="small_menu">
					<ul>
						<li><a href="/#header__lk" onclick="smoothToBlock()">Тесты</a></li>
						<li><a href="/#header__lk" onclick="smoothToBlock()">Результаты</a></li>
					</ul>
					<div class="small_menu_btn--close"></div>
				</div>
			<? } ?>
		</div>
	</div>
	<div class="header__content">
		<div class="inner-wrap">
			<h1>ДИСТАНЦИОННОЕ ОБУЧЕНИЕ</h1>
			<p>Автономное учреждение Омской области дополнительного профессионального образования &laquo;Центр охраны труда&raquo; проводит дистанционное обучение по&nbsp;курсам</p>
		</div>
	</div>
</header>