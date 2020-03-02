<?
IncludeModuleLangFile(__file__);
$module_id="aelita.test";
global $DBType;
CModule::AddAutoloadClasses(
	$module_id,
	array(
		"AelitaBase"				=>"classes/".$DBType."/aelita.base.php",
		"AelitaElement"			    =>"classes/general/aelita.element.php",
		
		"AelitaTestGroup"			=>"classes/element/aelita.test.group.php",
		"AelitaTestTest"			=>"classes/element/aelita.test.test.php",
		"AelitaTestQuestion"		=>"classes/element/aelita.test.question.php",
		"AelitaTestQuestionGroup"	=>"classes/element/aelita.test.question.group.php",
		"AelitaTestAnswer"			=>"classes/element/aelita.test.answer.php",
		"AelitaTestResult"			=>"classes/element/aelita.test.result.php",
		
		"AelitaTestGlasses"			=>"classes/element/aelita.test.glasses.php",
        "AelitaTestStep"			=>"classes/element/aelita.test.step.php",
		"AelitaTestQuestioning"		=>"classes/element/aelita.test.questioning.php",
		"AelitaTestProfile"			=>"classes/element/aelita.test.profile.php",
		
		"AelitaTestTools"			=>"classes/general/aelita.test.tools.php",
		"AelitaTestEditTool"			=>"classes/general/aelita.test.edit.tool.php",
		"AelitaTestEditToolEx"		=>"classes/general/aelita.test.edit.tool.ex.php",
		
		"AelitaTestAccessGroup"		=>"classes/element/aelita.test.access.group.php",
		"AelitaTestAccessTest"		=>"classes/element/aelita.test.access.test.php",
		"AelitaTestResponsible"		=>"classes/element/aelita.test.responsible.php",
	)
);
?>