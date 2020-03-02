<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)die();?>

<?
$APPLICATION->IncludeFile(
    SITE_DIR . "/include/form_modal.php",
    Array(),
    Array(
        "MODE" => "html")
	);
?>
<?
$APPLICATION->IncludeFile(
    SITE_DIR . "/include/modal_docs.php",
    Array(),
    Array(
        "MODE" => "html")
	);
?>

<?
$arGroupAvalaibleAdmin = array(1);
$arGroups = CUser::GetUserGroup($USER->GetID());
$result_intersect_admin = array_intersect($arGroupAvalaibleAdmin, $arGroups);

global $USER;

if ($USER->IsAuthorized()) { ?>

	<?
		$APPLICATION->IncludeFile(
		    SITE_DIR . "/include/footer_students.php",
		    Array(),
		    Array(
		        "MODE" => "html")
		);
	?>

<? } elseif (!$USER->IsAuthorized()) { ?>

	<?
		$APPLICATION->IncludeFile(
		    SITE_DIR . "/include/footer_not_authorized.php",
		    Array(),
		    Array(
		        "MODE" => "html")
		);
	?>

<? } ?>

<script src="/local/templates/cot_program/js/jquery-3.4.1.min.js"></script>
<script src="/local/templates/cot_program/js/app.js"></script>
</body>
</html>