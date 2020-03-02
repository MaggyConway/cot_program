<?
IncludeModuleLangFile(__FILE__);
if (class_exists("aelita_test")) return;
Class aelita_test extends CModule
{
	var $MODULE_ID = "aelita.test";
	
	var $MODULE_VERSION; 
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME; 
	var $MODULE_DESCRIPTION;
	var $PARTNER_NAME;
	var $PARTNER_URI;
	
	var $MODULE_CSS;

	var $MODULE_GROUP_RIGHTS = "Y";
	
	var $errors;
	
	
	function __construct()
	{
		$arModuleVersion = array();
		include(dirname(__FILE__)."/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = GetMessage("AELITA_TEST_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("AELITA_TEST_MODULE_DESC");
		$this->PARTNER_NAME = GetMessage("AELITA_TEST_PARTNER_MODULE_NAME");
		$this->PARTNER_URI = GetMessage("AELITA_TEST_PARTNER_URI");
	}
	
	function DoInstall()
	{
		$this->InstallDB();
		$this->InstallEvents();
		$this->InstallFiles();
		$this->InstallEventType();
		RegisterModule($this->MODULE_ID);
		
		$val = COption::GetOptionString($this->MODULE_ID, "hide_picture_and_description_in_window","Y");
		if($val=="Y")
			COption::SetOptionString($this->MODULE_ID,"hide_picture_and_description_in_window","Y");
			
		$val = COption::GetOptionString($this->MODULE_ID, "aelita_test_sadvanced_mode","Y");
		if($val=="Y")
			COption::SetOptionString($this->MODULE_ID,"aelita_test_sadvanced_mode","Y");
	}
	
	function DoUninstall()
	{
		global $APPLICATION;
		if (!check_bitrix_sessid())
			return false;
			
		$step = IntVal($_REQUEST['step']);
		if($step < 2)
		{
			$APPLICATION->IncludeAdminFile(GetMessage("AELITA_TEST_C_MODULE_INSTALL"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/{$this->MODULE_ID}/install/uninst1.php");
		}elseif($step == 2){
            if (!$_REQUEST['save_tables']=="Y")
                $this->UnInstallDB();
			$this->UnInstallFiles();
			$this->UnInstallEvents();
			$this->UnInstallEventsType();
			UnRegisterModule($this->MODULE_ID);
		}
	}
	
	function InstallEvents()
	{
		return true;
	}

	function UnInstallEvents()
	{
		return true;
	}
	
	function InstallEventType()
	{
		global $DB;
		$sIn = "'AELITA_TEST_WIN_TEST'";
		$rs = $DB->Query("SELECT count(*) C FROM b_event_type WHERE EVENT_NAME IN (".$sIn.") ", false, "File: ".__FILE__."<br>Line: ".__LINE__);
		$ar = $rs->Fetch();
		if($ar["C"] <= 0)
		{
			include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/{$this->MODULE_ID}/install/events/set_events.php");
		}
		return true;
		return true;
	}

	function UnInstallEventsType()
	{
		global $DB;
		include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/{$this->MODULE_ID}/install/events/del_events.php");
		return true;
	}
	
	function InstallDB()
	{
		global $DB, $DBType, $APPLICATION;
		$this->errors = false;

		if(!$DB->Query("SELECT 'x' FROM b_aelita_test_group", true))
		{	
			$this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/{$this->MODULE_ID}/install/db/".$DBType."/install.sql");
		}

		if($this->errors !== false)
		{
			$APPLICATION->ThrowException(implode("", $this->errors));
			return false;
		}

		return true;
	}
	
	function UnInstallDB()
	{
		global $DB, $DBType;
		$DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/{$this->MODULE_ID}/install/db/".$DBType."/uninstall.sql");
	}
		
	function InstallFiles()
	{
		if($_ENV["COMPUTERNAME"]!='BX')
		{
			CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/{$this->MODULE_ID}/install/admin/", $_SERVER['DOCUMENT_ROOT']."/bitrix/admin/");
			CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/{$this->MODULE_ID}/install/components/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components/", true, true);
			CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/{$this->MODULE_ID}/install/images/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/images/{$this->MODULE_ID}/", true, true);
			CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/{$this->MODULE_ID}/install/js/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/js/{$this->MODULE_ID}/", true, true);
			CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/{$this->MODULE_ID}/install/themes/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/themes/", true, true);
		}
		return true;
	}
	
	function UnInstallFiles()
	{
		if($_ENV["COMPUTERNAME"]!='BX')
		{
			DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/{$this->MODULE_ID}/install/admin/",$_SERVER['DOCUMENT_ROOT']."/bitrix/admin/");
			DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/{$this->MODULE_ID}/install/themes/.default/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/themes/.default/");
			DeleteDirFilesEx("/bitrix/images/{$this->MODULE_ID}/");
			DeleteDirFilesEx("/bitrix/js/{$this->MODULE_ID}/");
			DeleteDirFilesEx("/bitrix/themes/.default/icons/{$this->MODULE_ID}/");
			DeleteDirFilesEx("/bitrix/themes/.default/start_menu/{$this->MODULE_ID}/");
		}
		return true;
	}
	
	function GetModuleRightList()
	{
		$arr = array(
			"reference_id" => array("D","R","W"),
			"reference" => array(
					GetMessage("AELITA_TEST_FORM_DENIED"),
					GetMessage("AELITA_TEST_FORM_OPENED"),
					GetMessage("AELITA_TEST_FORM_FULL")
					),
				);
		return $arr;
	}
	
};

?>
