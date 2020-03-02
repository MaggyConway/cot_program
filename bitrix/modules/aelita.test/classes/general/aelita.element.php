<?
/**
* Aelita Element
* 
* @author Danilin Alexander I. <Danilin2010@yandex.ru>
* @version 1.0
**/
IncludeModuleLangFile(__FILE__);
abstract class AelitaElement extends AelitaBase
{
	var $TABLE_NAME;
	var $MODULE_ID="aelita.test";
	
	function Delete($ID) {
		$ID = intval($ID);
		if ($ID <= 0)
			return false;
		if($this->BeforeDelete($ID))
			$this->_Delete($ID,"ID",$this->TABLE_NAME);
		else
			return false;
		return true;
	}
	
	function GroupDelete($PropCode, $Prop)
	{
		if($PropCode=="ID")
		{
			return $this->Delete($Prop);
		}else{
			$this->_Delete($Prop,$PropCode,$this->TABLE_NAME);
		}
		return true;
	}
	
	public function Add(&$arFields){
		if(!$this->CheckFields($arFields, 'ADD'))
			return false;
		//print_r($this->TABLE_NAME);
		unset($arFields["ID"]);
		$ID = $this->_Add($this->TABLE_NAME, $arFields);
		return $ID;
	}
	
	function Update($ID, $arFields){
		$ID = IntVal($ID);
		if ($ID <= 0)
			return false;
		$arFields["ID"]=$ID;
		if(!$this->CheckFields($arFields, 'UPDATE'))
			return false;
		unset($arFields["ID"]);
		return $this->_Update($this->TABLE_NAME, $arFields,"ID",$ID);
	}
	
	function GroupUpdate($PropCode, $Prop, $arFields)
	{
		if($PropCode=="ID")
			$arFields["ID"]=$Prop;
		if(!$this->CheckFields($arFields, 'UPDATE'))
			return false;
		unset($arFields["ID"]);
		return $this->_Update($this->TABLE_NAME,$arFields,$PropCode,$Prop);
	}
	
	function GetByID($ID) {
		$ID = IntVal($ID);
		if ($ID <= 0)
			return false;
		$arFilter=array("ID"=>$ID);
		$arOrder=array("ID"=>"ASC");
		$arNavStartParams=array("nPageSize"=>1);
		return $this->GetList($arOrder,$arFilter,false,$arNavStartParams);
	}
	
	function GetList($arOrder = array(), $arFilter = array(), $arGroupBy = false, $arNavStartParams = false, $arSelectFields = array()) {
		global $DB, $USER;
		$NAME="MAIN";
		$table=$this->TABLE_NAME." ".$NAME;
		$this->GetFields($arFields,$NAME);
		if (count($arSelectFields)<=0)
			$arSelectFields = array_keys($arFields);
		$this->GetAfterFields($arFields,$NAME);
		if (count($arOrder)<=0)
			$arOrder=array("ID"=>"ASC");
		return $this->_GetCDBResult($table, $arFields, $arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelectFields);
	}
	
	function CheckImages(&$arFields,$strOperation,$Prop)
	{
		global $APPLICATION;
		if (array_key_exists($Prop, $arFields))
		{
			if(is_array($arFields[$Prop]))
			{
				$arFields[$Prop]["MODULE_ID"] = $this->MODULE_ID;
				$is_image = CFile::IsImage($arFields[$Prop]["name"], $arFields[$Prop]["type"]);
				if($is_image)
				{
					if($strOperation=="UPDATE" && $arFields["ID"]>0)
					{
						$arFilter=array("ID"=>$arFields["ID"]);$arOrder=array("ID"=>"ASC");$arNavStartParams=array("nPageSize"=>1);$arSelectFields=array("ID",$Prop);
						$arExtra=$this->GetList($arOrder,$arFilter,false,$arNavStartParams,$arSelectFields);
						if($arExtra=$arExtra->GetNext())
						{
							$arExtra[$Prop]=(int)$arExtra[$Prop];
							if($arExtra[$Prop]>0)
							{
								$arFields[$Prop]["old_file"]=$arExtra[$Prop];
								$arFields[$Prop]["del"]="Y";
							}
						}
					}
					$fid = CFile::SaveFile($arFields[$Prop],$this->MODULE_ID);
					if((int)$fid>0)
						$arFields[$Prop]=$fid;
					else
						unset($arFields[$Prop]);
				}elseif($arFields[$Prop]["del"]=="Y" && $strOperation=="UPDATE" && $arFields["ID"]>0){
					$arFilter=array("ID"=>$arFields["ID"]);$arOrder=array("ID"=>"ASC");$arNavStartParams=array("nPageSize"=>1);$arSelectFields=array("ID",$Prop);
					$arExtra=$this->GetList($arOrder,$arFilter,false,$arNavStartParams,$arSelectFields);
					if($arExtra=$arExtra->GetNext())
					{
						$arExtra[$Prop]=(int)$arExtra[$Prop];
						if($arExtra[$Prop]>0)
						{
							CFile::Delete($arExtra[$Prop]);
							$arFields[$Prop]="";
						}
					}
				}else{
					unset($arFields[$Prop]);
				}
			}else{
				$arFields[$Prop]=(int)$arFields[$Prop];
				if($arFields[$Prop]<=0)
				{
					unset($arFields[$Prop]);
				}elseif($strOperation=="UPDATE" && $arFields["ID"]>0){
					$arFilter=array("ID"=>$arFields["ID"]);$arOrder=array("ID"=>"ASC");$arNavStartParams=array("nPageSize"=>1);$arSelectFields=array("ID",$Prop);
					$arExtra=$this->GetList($arOrder,$arFilter,false,$arNavStartParams,$arSelectFields);
					if($arExtra=$arExtra->GetNext())
					{
						$arExtra[$Prop]=(int)$arExtra[$Prop];
						if($arExtra[$Prop]>0 && $arFields[$Prop]!=$arExtra[$Prop])
						{
							CFile::Delete($arExtra[$Prop]);
						}
					}
				}
			}
		}
		return true;
	}
	
	function DeletePicture($ID,$Prop)
	{
		$arFilter=array("ID"=>$ID);$arOrder=array("ID"=>"ASC");$arNavStartParams=array("nPageSize"=>1);$arSelectFields=array("ID",$Prop);
		$arExtra=$this->GetList($arOrder,$arFilter,false,$arNavStartParams,$arSelectFields);
		if($arExtra=$arExtra->GetNext())
		{
			$arExtra[$Prop]=(int)$arExtra[$Prop];
			if($arExtra[$Prop]>0)
				CFile::Delete($arExtra[$Prop]);
		}
		return true;
	}
	
	function CheckName(&$arFields,$strOperation,$Prop)
	{
		global $APPLICATION;
		if (array_key_exists($Prop, $arFields) || $strOperation == "ADD")
		{
			if (strlen($arFields[$Prop]) <= 0) {
				$APPLICATION->ThrowException(GetMessage("ERROR_ATG_NO_NAME"));
				return false;
			}
		}
		return true;
	}
	
	function CheckActive(&$arFields,$strOperation,$Prop)
	{
		if (array_key_exists($Prop, $arFields))
		{
			if ($arFields[$Prop]!="Y")
				$arFields[$Prop]="N";
		}elseif($strOperation=="ADD"){
			$arFields[$Prop]="Y";
		}
		return true;
	}
	
	function CheckNonActive(&$arFields,$strOperation,$Prop)
	{
		if (array_key_exists($Prop, $arFields))
		{
			if ($arFields[$Prop]!="Y")
				$arFields[$Prop]="N";
		}
		return true;
	}
	
	function CheckType(&$arFields,$strOperation,$Prop)
	{
		if ($arFields[$Prop]!="html")
			$arFields[$Prop]="text";
		return true;
	}
	
	function CheckSort(&$arFields,$strOperation,$Prop)
	{
		if (array_key_exists($Prop, $arFields) || $strOperation == "ADD")
		{
			$arFields[$Prop]=(int)$arFields[$Prop];
			if($arFields[$Prop]<=0)
				$arFields[$Prop]=500;
		}
		return true;
	}
	
	function CheckInt(&$arFields,$strOperation,$Prop)
	{
		if (array_key_exists($Prop, $arFields) || $strOperation == "ADD")
			$arFields[$Prop]=(int)$arFields[$Prop];
		return true;
	}
	
	function ConverBase(&$arFields,$strOperation,$Prop)
	{
		$arFields[$Prop]=base64_encode(serialize($arFields[$Prop]));
		return true;
	}
	
	abstract public function CheckFields(&$arFields, $strOperation = 'ADD');
	abstract public function GetFields(&$arFields,$NAME);
	abstract public function GetAfterFields(&$arFields,$NAME);
	abstract public function BeforeDelete($ID);
}


?>