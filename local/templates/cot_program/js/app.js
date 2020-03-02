jQuery(document).ready(function($) {
	$('#courses .courses__item:nth-child(2)').hover(function() {
		$('#courses .courses__item:nth-child(1)').css('border-right', '1px solid #2D4FA6');
		$('#courses .courses__item:nth-child(3)').css('border-left', '1px solid #2D4FA6');
		//console.log('done');
	}, function() {
		$('#courses .courses__item:nth-child(1)').css('border-right', '1px solid #dedede');
		$('#courses .courses__item:nth-child(3)').css('border-left', '1px solid #dedede');
	});

	var myHash = location.hash; //получаем значение хеша
	location.hash = ''; //очищаем хеш
	if(myHash[1] != undefined) { //проверяем, есть ли в хеше какое-то значение

	if (myHash) {
		$('html, body').animate(
		{scrollTop: $(myHash).offset().top - 30}
		, 1000); //скроллим за полсекунды
		location.hash = myHash; //возвращаем хеш
		};
	}

});
//СКРОЛЛИНГ-МЕНЮ
$(function(){
	$('a[href^="#"]').on('click', function(event) {  
	    var src = $(this).attr("href"),
	    sectionPosition = $(src).offset().top - 30;
	    $('html, body').animate({scrollTop: sectionPosition}, 1000);
	    $('.small_menu').removeClass('opened');
	});
});
function smoothToBlock() {
	//event.preventDefault();
	var src = '#' + $(event.target).attr("href").split('#')[1];
	console.log(src);
	var sectionPos = $(src).offset().top - 30;
	$('html, body').animate({scrollTop: sectionPos}, 1000);
	$('.small_menu').removeClass('opened');
}


//МОБИЛЬНОЕ МЕНЮ
$('.small_menu_btn').click(function() {
	$('.small_menu').addClass('opened');
});
$('.small_menu_btn--close').click(function() {
	$('.small_menu').removeClass('opened');
});

// SUBMIT FORM FEEDBACK
$('.request__form').submit(function (e) {
	event.preventDefault();
	var $form = $(this);
	$.ajax({
		type: 'post',
		url: '/ajax/send.php',
		data: $form.serialize()
	}).done(function (event) {
		if (event == 1) {
			$('#modal-success-container').addClass('animate_modal');
			$('body').addClass('modal-success-active');
			$('.request__form')[0].reset();
		}
	}).fail(function () {
		console.log('fail');
	});
});



$('.modal_docs_btn').click(function () {
	event.preventDefault();
	var thisContent = $(this).parent().attr('data-content');
	$('#modal-docs-container').addClass('animate_modal');
	$('body').addClass('modal-docs-active');
	$('#modal-docs-container').find('.modal .content').html(' ').append(thisContent);
});

//Close function
$('.modal--background, .modal-close').click(function(){
	$('#modal-success-container').removeClass('animate_modal');
	$('#modal-docs-container').removeClass('animate_modal');
	$('body').removeClass('modal-success-active');
});

