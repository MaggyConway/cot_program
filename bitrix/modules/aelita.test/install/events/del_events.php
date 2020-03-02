<?
$DB->Query("DELETE FROM b_event_type WHERE EVENT_NAME in (
	'AELITA_TEST_WIN_TEST'
	)");

$DB->Query("DELETE FROM b_event_message WHERE EVENT_NAME in (
	'AELITA_TEST_WIN_TEST'
	)");
?>