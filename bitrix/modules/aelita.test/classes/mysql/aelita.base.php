<?
/**
* Aelita Base
* 
* @author Danilin Alexander I. <Danilin2010@yandex.ru>
* @version 1.0
**/
IncludeModuleLangFile(__FILE__);
abstract class AelitaBase
{
	function _GetFields($Filter)
	{
		global $DB;
		$result="";
		if (!is_numeric($Filter))
			$result.="'";
		$result.=$DB->ForSql($Filter);
		if (!is_numeric($Filter))
			$result.="'";
		return $result;
	}
	
	function _JoinVerification(&$join,$SetJoin)
	{
		if (Is_array($SetJoin))
		{
			foreach($SetJoin as $Value)
				if (!in_array($Value,$join))
					$join[]=$Value;
		}else{
			if (!in_array($SetJoin,$join))
				$join[]=$SetJoin;
		}
	}
	
	function _GetWhere(&$arFields,&$join,$arFilter)
	{
		$operator=array(">","<","=","><",">=","<=","<>","like");
		$txtFilter="";
		if(is_array($arFilter))
		{
			if (array_key_exists("LOGIC", $arFilter))
			{
				if($arFilter["LOGIC"] == "OR")
					$Logic = "OR";
				else
					$Logic = "AND";
				unset($arFilter["LOGIC"]);
			}else{
				$Logic = "AND";
			}
			foreach ($arFilter as $KeyFilter=>$Filter)
			{
				$tempKeyFilter=$KeyFilter;
				$tempOperator="=";
				
				
				$substr=substr($tempKeyFilter, 0, 4);
				if (in_array($substr,$operator))
				{
					$tempOperator=$substr;
					$tempKeyFilter=substr($tempKeyFilter, 4);
				}else{
					$substr=substr($tempKeyFilter, 0, 2);
					if (in_array($substr,$operator))
					{
						$tempOperator=$substr;
						$tempKeyFilter=substr($tempKeyFilter, 2);
					}else{
						$substr=substr($tempKeyFilter, 0, 1);
						if (in_array($substr,$operator))
						{
							$tempOperator=$substr;
							$tempKeyFilter=substr($tempKeyFilter, 1);
						}
					}
				}

				if ($arFields[$tempKeyFilter])
				{
					if ($arFields[$tempKeyFilter]["JOIN"])
							$this->_JoinVerification($join,$arFields[$tempKeyFilter]["JOIN"]);
					If ($txtFilter <> ""){$txtFilter.= " ".$Logic." ";};
					if (is_array($Filter))
					{
						$temp="";
						$tempLogic = " OR ";
						if (array_key_exists("LOGIC", $Filter))
						{
							if($Filter["LOGIC"] == "OR")
								$tempLogic = " OR ";
							else
								$tempLogic = " AND ";
							unset($arFilter["LOGIC"]);
						}
						foreach ($Filter as $Value)
						{
							If ($temp <> ""){$temp.= $tempLogic;};
							//if(strlen($arFields[$tempKeyFilter]["AS"])>0)
							//	$temp.=$arFields[$tempKeyFilter]["AS"];
							//else
								$temp.=$arFields[$tempKeyFilter]["FIELD"];
							$temp.=' '.$tempOperator.' ';
							$temp.=$this->_GetFields($Value);
						}
						If ($temp <> ""){$txtFilter.=" (".$temp.")";};
					}else{
						if ($Filter===false)
						{
							//if(strlen($arFields[$tempKeyFilter]["AS"])>0)
							//	$txtFilter.=$arFields[$tempKeyFilter]["AS"];
							//else
								$txtFilter.=$arFields[$tempKeyFilter]["FIELD"];
							if ($tempOperator=="<>")
								$txtFilter.=" IS NOT NULL";
							else
								$txtFilter.=" IS NULL";
						}else{
							//if(strlen($arFields[$tempKeyFilter]["AS"])>0)
							//	$txtFilter.=$arFields[$tempKeyFilter]["AS"];
							//else
								$txtFilter.=$arFields[$tempKeyFilter]["FIELD"];
							$txtFilter.=' '.$tempOperator.' ';
							$txtFilter.=$this->_GetFields($Filter);
						}
					}
				}
				elseif(is_array($Filter) and array_key_exists("LOGIC", $Filter))
				{
					If ($txtFilter <> ""){$txtFilter.= " ".$Logic." ";};
					$txtFilter.=" (".$this->_GetWhere($arFields,$join,$Filter).")";
				}
			}
		}
		return $txtFilter;
	}
	
	function _GetCount(&$strTable, &$arFields, &$arOrder, &$arFilter, &$arGroupBy, &$arNavStartParams, &$arSelectFields)
	{
		$join=array();
		global $DB;
		$txtSQL="";
		$txtSQL.="SELECT ";
		If ($arFields["COUNT"])
		{
			$NameColumn.=$arFields["COUNT"]["FIELD"];
			if ($NameColumn<>"")
				$txtSQL.=$NameColumn;
			else
				$txtSQL.="*";
		}else{
			$txtSQL.=" COUNT(*) AS COUNT";
		}
		$txtSQL.= " FROM ".$strTable;
		
		if (count($arFilter)>0)
		{
			$txtWhere=$this->_GetWhere($arFields,$join,$arFilter);
			if (strlen($txtWhere)>0)
				$txtSQL.= " WHERE (".$txtWhere.")";
		}
		if (count ($join)>0)
			$txtSQL.=" ".implode(" ".$join);
		
		$txtSQL.=";";
		
		$res_cnt = $DB->Query($txtSQL);
		$Count=$res_cnt->GetNext();
		return $Count["COUNT"];
	}
	
	function _GetCDBResult(&$strTable, &$arFields, &$arOrder, &$arFilter, &$arGroupBy, &$arNavStartParams, &$arSelectFields)
	{
		global $DB;
		$join=array();
		$OrderValue = array("ASC","DESC");
		$txtSQL="";
		$txtSQLSelect="";
		$txtSQLJoin="";
		$txtSQLWhere="";
		$txtSQLGroup="";
		$txtSQLOrder="";
		$txtSQLLimit="";
		$txtSQLSelect.="SELECT ";
		
		If (Count($arSelectFields) > 0)
		{
			$NameColumn = "";
			foreach ($arSelectFields as $Select)
			{
				if ($arFields[$Select])
				{
					if ($arFields[$Select]["JOIN"])
						$this->_JoinVerification($join,$arFields[$Select]["JOIN"]);
					if ($NameColumn <> "")
						$NameColumn.=", ";
					$NameColumn.=$arFields[$Select]["FIELD"];
					if ($arFields[$Select]["AS"])
						$NameColumn.=" AS ".$arFields[$Select]["AS"];
				}
			}
			if ($NameColumn<>"")
				$txtSQLSelect.=$NameColumn;
			else
				$txtSQLSelect.="*";
		}else{
			$txtSQLSelect.="*";
		}
		$txtSQLSelect.= " FROM ".$strTable;
		
		if (count($arFilter)>0)
		{
			$txtWhere=$this->_GetWhere($arFields,$join,$arFilter);
			if (strlen($txtWhere)>0)
				$txtSQLWhere.= " WHERE (".$txtWhere.")";
		}
		
		If (is_array($arOrder) && Count($arOrder)>0)
		{
			$txtOrder = " ORDER BY ";
			foreach ($arOrder as $KeyOrder=>$Order)
			{
				if ($arFields[$KeyOrder])
				{
					if ($arFields[$KeyOrder]["JOIN"])
						$this->_JoinVerification($join,$arFields[$KeyOrder]["JOIN"]);
					If ($txtOrder <> " ORDER BY ")
						$txtOrder.= ", ";
					if($Order==="RAND")
					{
						$txtOrder.=" RAND()";
					}else{
						$txtOrder.=$arFields[$KeyOrder]["FIELD"];
						if (in_array(strtoupper($Order),$OrderValue))
							$txtOrder.=" ".strtoupper($Order);
						else
							$txtOrder.=" ASC";
					}
				}
			}
			If ($txtOrder <> " ORDER BY ")
				$txtSQLOrder.=$txtOrder;
		}
		
		If (is_array($arGroupBy) && Count($arGroupBy)>0)
		{
			$txtGroup = " GROUP BY ";
			foreach ($arGroupBy as $GroupBy)
			{
				if ($arFields[$GroupBy])
				{
					if ($arFields[$GroupBy]["JOIN"])
						$this->_JoinVerification($join,$arFields[$GroupBy]["JOIN"]);
					If ($txtGroup <> " GROUP BY ")
						$txtGroup.= ", ";
					$txtGroup.=$arFields[$GroupBy]["FIELD"];
				}
			}
			If ($txtGroup <> " GROUP BY ")
				$txtSQLGroup.=$txtGroup;
		}
		
		if ((int)$arNavStartParams["nPageSize"]>0)
		{
			if ((int)$arNavStartParams["iNumPage"]>1)
				$txtSQLLimit.=" LIMIT ".(((int)$arNavStartParams["iNumPage"]-1)*(int)$arNavStartParams["nPageSize"]).", ".(int)$arNavStartParams["nPageSize"];
			else
				$txtSQLLimit.=" LIMIT ".(int)$arNavStartParams["nPageSize"];
		}elseif ((int)$arNavStartParams["nTopCount"]>0){
			$txtSQLLimit.=" LIMIT ".(int)$arNavStartParams["nTopCount"];
		}

		if (count ($join)>0)
			$txtSQLJoin.=" ".implode(" ",$join);
		
		$txtSQL.=$txtSQLSelect.$txtSQLJoin.$txtSQLWhere.$txtSQLGroup.$txtSQLOrder.$txtSQLLimit;
		$txtSQL.=";";
		$err_mess = "<br>Function: GetList<br>Line: ";
		$res = new CDBResult();
		$res =$DB->Query($txtSQL, false, $err_mess.__LINE__);
		//print_r($txtSQL);
		//print_r("<br />");
		return $res;
	}
	
	function _Delete ($ID,$TextID,$strTable)
	{
		global $DB;
		$strSql = "DELETE FROM ".$strTable." WHERE ".$TextID."=".$ID;
		$z = $DB->Query($strSql, false, "FILE: ".__FILE__."<br> LINE: ".__LINE__);
		return true;
	}
	
	function _Add ($strTable, &$arFields)
	{
		global $DB;
		$err_mess = "<br>Function: AelitaBase::Add<br>Line: ";
		$arInsert = $DB->PrepareInsert($strTable, $arFields);
		$strSql = "INSERT INTO ".$strTable." (".$arInsert[0].") VALUES (".$arInsert[1].")";
		$DB->Query($strSql, false, $err_mess.__LINE__);
		return intval($DB->LastID());
	}
	
	function _Update ($strTable,&$arFields,$iID,$ID)
	{
		global $DB;
		$err_mess = "<br>Function: AelitaBase::Update<br>Line: ";
		$strUpdate = $DB->PrepareUpdate($strTable, $arFields);
		$strSql = "UPDATE ".$strTable." SET ".$strUpdate." WHERE ".$iID."=".$ID;
		$DB->Query($strSql, false, $err_mess.__LINE__);
		//echo "<pre>";print_r($strSql);echo "</pre>";
		return true;
	}
}
?>