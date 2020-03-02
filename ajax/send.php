<?
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


if(isset($_POST)  && !empty($_POST)){

	$event = $_POST['event'];
	if($event == 'FEEDBACK_FORM'){

		$USER_NAME= $_POST['name'];
		$USER_EMAIL= $_POST['email'];
		$fields = array(
			'USER_NAME' => $USER_NAME,
			'USER_EMAIL' => $USER_EMAIL,
		);
	}
	if($event){
		CEvent::Send($event, 's1', $fields , 'N', '','' );
	}
	echo true;
} else {
	LocalRedirect("404.php", " 404 Страница не найдена");
}?>