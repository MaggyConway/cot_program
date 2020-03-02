
      if (window.Node && window.XMLSerializer)
      {
        Node.prototype.__defineGetter__('outerHTML', function() {
          return new XMLSerializer().serializeToString(this);
         });
       }
     
       function getDivHTML(div){
         alert(div.outerHTML);
       }

function JAelitaTestResultProperty(arParams)
{
	var _this = this;
	if (!arParams) return;
	this.intERROR = 0;
	this.PREFIX = arParams.PREFIX;
	this.PREFIX_TR = this.PREFIX+'ROW_';
	this.FORM_ID = arParams.FORM_ID;
	this.TABLE_PROP_ID = arParams.TABLE_PROP_ID;
	this.PROP_COUNT_ID = arParams.PROP_COUNT_ID;
	this.TEST_ID = arParams.TEST_ID;
	this.LANG = arParams.LANG;
	this.TITLE = arParams.TITLE;
	this.BUTTON_SAVE = arParams.BUTTON_SAVE;
	this.BUTTON_CLOSE = arParams.BUTTON_CLOSE;
	this.CELLS = [];
	this.CELL_IND = -1;
	this.CELL_CENT = [];
	this.OBJNAME = arParams.OBJ;
	
	this.ADD_TR = arParams.ADD_TR;
	this.ADD_SCRIPT = arParams.ADD_SCRIPT;
	
	this.SENDEVAL=arParams.SENDEVAL;

	BX.ready(BX.delegate(this.Init,this));
};

JAelitaTestResultProperty.prototype.Init = function()
{
	this.FORM_DATA = BX(this.FORM_ID);
	if (!this.FORM_DATA)
	{
		this.intERROR = -1;
		return;
	}
	this.PROP_TBL = BX(this.TABLE_PROP_ID);
	if (!this.PROP_TBL)
	{
		this.intERROR = -1;
		return;
	}
	this.PROP_COUNT = BX(this.PROP_COUNT_ID);
	if (!this.PROP_COUNT)
	{
		this.intERROR = -1;
		return;
	}
	
	var cltbody = BX.findChildren(this.PROP_TBL, {'tag': 'tbody'}, false);
	if (cltbody)
	{
		for (var k= 0; k < cltbody.length; k++)
		{
			var clRow = BX.findChildren(cltbody[k], {'tag': 'tr','attribute':{'class':'row_list'}}, false);
			if (clRow)
			{
				for (var j = 0; j < clRow.length; j++)
				{
					var clButtons = BX.findChildren(clRow[j], {'tag': 'input','attribute':{'type':'button','class':'celldetail'}}, true);
					if (clButtons)
					{
						for (var i = 0; i < clButtons.length; i++)
							BX.bind(clButtons[i], 'click', BX.proxy(function(e){this.ShowPropertyDialog(e);}, this));
					}
				}
			}
		}
	}
	BX.addCustomEvent(this.FORM_DATA, 'onAutoSaveRestore', BX.delegate(this.onAutoSaveRestore, this));
};



JAelitaTestResultProperty.prototype.GetPropInfo = function(ID)
{
	if (0 > this.intERROR)
		return;

	ID = this.PREFIX + ID;

	arResult = {
		'PROPINFO': this.FORM_DATA[ID+'_PROPINFO'].value
	};
	return arResult;
};


JAelitaTestResultProperty.prototype.SetPropInfo = function(ID,arProp,formsess)
{
	if (0 > this.intERROR)
		return;

	if (!formsess)
		return;
	if (BX.bitrix_sessid() != formsess)
		return;

	ID = this.PREFIX+ID;


	this.FORM_DATA[ID+'_PROPINFO'].value = arProp.PROPINFO;

	BX.fireEvent(this.FORM_DATA[ID+'_NAME'], 'change');
};


JAelitaTestResultProperty.prototype.ShowPropertyDialog = function (e)
{
	if(!e)
		e = window.event;
	if (0 > this.intERROR)
		return;
	var s = (BX.browser.IsIE() ? e.srcElement.id : e.target.id);

	if (!s)
		return;

	s = s.replace(this.PREFIX,'');
	s = s.replace('_RES','');
	var ID = s;


	var arResult = {
		'PARAMS': {
			'PREFIX': this.PREFIX,
			'ID': ID,
			'TEST_ID': this.TEST_ID,
			'TITLE': this.TITLE,
			'RECEIVER': this.OBJNAME
		},
		'PROP': this.GetPropInfo(ID),
		'sessid': BX.bitrix_sessid()
	};
	var idNone=arResult.PARAMS.PREFIX+arResult.PARAMS.ID+"_NONE";

	var temp=BX(idNone);
	
	var addAnswer = new BX.PopupWindow(
		idNone+'_p',                
		null, 
		{
			content: BX(idNone),
			closeIcon: {right: "20px", top: "10px" },
			titleBar: {content: BX.create("span", {html: '<b>'+this.TITLE+'</b>', 'props': {'className': 'access-title-bar'}})}, 
			zIndex: 0,
			offsetLeft: 0,
			offsetTop: 0,
			draggable: {restrict: false},
			idnone:idNone,
			temp:temp,
			events:
				{
					onPopupClose:function()
					{
						BX(this.params.idnone+'_WRAPPER').appendChild(this.params.temp.parentNode ? this.params.temp.parentNode.removeChild(this.params.temp) : this.params.temp);
						this.destroy();
					}
				},
			buttons:
			[
				new BX.PopupWindowButton({
					text: this.BUTTON_SAVE ,
					className: "popup-window-button-accept" ,
					events: {click: function(){
						this.popupWindow.close();
					}}
				}),
			]
		});

	addAnswer.show();

};

JAelitaTestResultProperty.prototype.SetCells = function(arCells,intIndex,arCenter)
{
	if (0 > this.intERROR)
		return;

	if (arCells)
		this.CELLS = BX.clone(arCells,true);
	for (var i = 0; i < this.CELLS.length; i++)
	{
		this.CELLS[i] = this.CELLS[i].replace(/PREFIX/ig, this.PREFIX);
	}
	if (intIndex)
		this.CELL_IND = intIndex;
	if (arCenter)
		this.CELL_CENT = BX.clone(arCenter,true);
};

JAelitaTestResultProperty.prototype.addPropRow = function()
{
	if (0 > this.intERROR)
		return;
	var i = 0;
	var id = parseInt(this.PROP_COUNT.value);

	var newRow = this.PROP_TBL.insertRow(this.PROP_TBL.rows.length);
	newRow.id = this.PREFIX_TR+'n'+id;
	newRow.setAttribute('class','row_list');
	for (i = 0; i < this.CELLS.length; i++)
	{
		var oCell = newRow.insertCell(-1);
		var typeHtml = this.CELLS[i];
		typeHtml = typeHtml.replace(/tmp_xxx/ig, 'n'+id);

		oCell.innerHTML = typeHtml;
		if(this.SENDEVAL=="Y")
		{
			scripts = oCell.getElementsByTagName("script");
			if(scripts.length>0)
			{
				for(j=0 ; j<scripts.length ; j++)
					eval (scripts[j].innerHTML);
			}
		}
		oCell.setAttribute('style','text-align:center');
	}
	for (i = 0; i < this.CELL_CENT.length; i++)
	{
		var needCell = newRow.cells[this.CELL_CENT[i]-1];
		if (needCell)
		{
			needCell.setAttribute('align','center');
		}
	}
	if (newRow.cells[this.CELL_IND])
	{
		var needCell = newRow.cells[this.CELL_IND];
		var clButtons = BX.findChildren(needCell, {'tag': 'input','attribute': { 'type':'button'}}, true);
		if (clButtons)
		{
			for (var i = 0; i < clButtons.length; i++)
				BX.bind(clButtons[i], 'click', BX.proxy(function(e){this.ShowPropertyDialog(e);}, this));
		}
	}

	
	if(this.ADD_TR)
	{
		var AnswerRow = this.PROP_TBL.insertRow(this.PROP_TBL.rows.length);
		AnswerRow.id = this.PREFIX_TR+'n'+id+'_ANSWER';
		var oCell = AnswerRow.insertCell(-1);
		var typeHtml = this.ADD_TR;
		typeHtml = typeHtml.replace(/table_xxx/ig, 'n'+id);
		typeHtml = typeHtml.replace(/PR_XXXX/ig, this.PREFIX);
		oCell.setAttribute('colspan',this.CELLS.length);
		oCell.innerHTML = typeHtml;
		if(this.SENDEVAL=="Y")
		{
			scripts = oCell.getElementsByTagName("script");
			if(scripts.length>0)
			{
				for(j=0 ; j<scripts.length ; j++)
					eval (scripts[j].innerHTML);
			}
		}
		if(this.ADD_SCRIPT)
		{

			var wrapper=document.getElementById(this.PREFIX+'n'+id+'_wrapper_script');
			var typeHtml = this.ADD_SCRIPT;
			typeHtml = typeHtml.replace(/table_xxx/ig, 'n'+id);
			typeHtml = typeHtml.replace(/PR_XXXX/ig, this.PREFIX);
			if(this.TEST_ID>0)
				typeHtml = typeHtml.replace(/'TEST_ID': 0/ig, "'TEST_ID': "+this.TEST_ID);
			wrapper.innerHTML = typeHtml;
			scripts = wrapper.getElementsByTagName("script");
			if(scripts.length>0)
			{
				for(j=0 ; j<scripts.length ; j++)
					eval (scripts[j].innerHTML);
			}
			var scr=document.getElementById(this.PREFIX+'n'+id+'_add_script');
			scr.setAttribute('onclick','');
			BX.bind(scr, 'click', BX.proxy(function(e){
				eval(this.PREFIX+'n'+id+'_obQuestionProps.addPropRow();');
				},this));
		}
	}
	
	

	setTimeout(function() {
		var r = BX.findChildren(newRow.parentNode, {tag: /^(input|select|textarea)$/i}, true);
		if (r && r.length > 0)
		{
			for (var i=0,l=r.length;i<l;i++)
			{
				if (r[i].form && r[i].form.BXAUTOSAVE)
					r[i].form.BXAUTOSAVE.RegisterInput(r[i]);
				else
					break;
			}
		}
	}, 10);

	this.PROP_COUNT.value = id + 1;
};
