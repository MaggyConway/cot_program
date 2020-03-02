<section class="request">
	<div class="request__title">
		<h2>Остались вопросы?</h2>
		<p>
			 Оставьте заявку, и&nbsp;наш менеджер свяжется с&nbsp;Вами в&nbsp;ближайшее время
		</p>
	</div>
	<form class="request__form" action="/ajax/send.php" method="POST">
		<input type="text" name="name" placeholder="Ваше имя" required> 
		<input type="email" name="email" placeholder="Ваш Email" required> 
		<input type="hidden" name="event" value="FEEDBACK_FORM" />
		<button type="submit" id="animate_modal" class="btn">ОТПРАВИТЬ</button>
	</form>
</section>