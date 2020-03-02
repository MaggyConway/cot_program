<?
$arGroupAvalaibleAdmin = array(1);
$arGroups = CUser::GetUserGroup($USER->GetID());
$result_intersect_admin = array_intersect($arGroupAvalaibleAdmin, $arGroups);
?>

<footer>
	<div class="footer__top">
		<div class="wrapper">
			<div class="footer__top__column">
				<? if (!empty($result_intersect_admin)) { ?>
					<a href="/" class="logo">
						<div class="logo__image"></div>
						<div class="logo__title">
							<p>ЦЕНТР ОХРАНЫ ТРУДА</p>
							<p>Автономное учреждение Омской области дополнительного профессионального образования &laquo;ЦЕНТР ОХРАНЫ ТРУДА&raquo;.</p>
						</div>
					</a>
				<? }  elseif (empty($result_intersect_admin)) { ?>
					<a href="/tests/" class="logo">
						<div class="logo__image"></div>
						<div class="logo__title">
							<p>ЦЕНТР ОХРАНЫ ТРУДА</p>
							<p>Автономное учреждение Омской области дополнительного профессионального образования &laquo;ЦЕНТР ОХРАНЫ ТРУДА&raquo;.</p>
						</div>
					</a>
				<? } ?>
				<div class="social">
					<a href="#" class="tw"></a>
					<a href="#" class="inst"></a>
					<a href="#" class="vk"></a>
					<a href="#" class="fb"></a>
					<a href="#" class="yt"></a>
				</div>
			</div>
			<div class="footer__top__column">
				<div class="list__title">Перечень документов</div>
				<ul>
					<li><a href="#">Перечень документов для обучения по&nbsp;охране труда</a></li>
					<li><a href="#">Перечень документов для обучения по&nbsp;пожарно-техническому минимуму</a></li>
					<li><a href="#">Перечень документов для обучения по&nbsp;экологической безопасности</a></li>
				</ul>
			</div>
			<!-- <div class="footer__top__column">
				<div class="btn">Личный кабинет</div>
				<div class="list__title">Курсы для обучения</div>
				<ul>
					<li><a href="#">Охрана труда</a></li>
					<li><a href="#">Пожарно-технический минимум </a></li>
					<li><a href="#">Экологическая безопасность</a></li>
				</ul>
			</div> -->
			<div class="footer__top__column">
				<div class="footer__top__column__contacts">
					<a href="tel:+73812511271">+7 (3812) 51-12-71</a>
					<a href="mailto:cotomsk-ob@mail.ru">cotomsk-ob@mail.ru</a>
				</div>
				<!-- <div class="list__title">Тесты</div>
				<ul>
					<li><a href="#">Охрана труда</a></li>
					<li><a href="#">Пожарно-технический минимум</a></li>
					<li><a href="#">Экологическая безопасность</a></li>
				</ul> -->
			</div>
		</div>
	</div>
	<div class="footer__bottom">
		<div class="wrapper">
			<a href="/privacy/">Политика конфиденциальности</a>
			<a href="#" class="asmart">Cайт создан в IT-company <span>ASMART</span></a>
		</div>
	</div>
</footer>