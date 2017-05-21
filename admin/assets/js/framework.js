//Plugins framework ///////////////////////////////////////////////////////////
$.fn.serializeObject = function(){
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
$.fn.wordComplete = function(params){
	if(params.minLength == undefined)
		params.minLength = 1;	
	if(params.delay == undefined)
		params.delay = 300;
	if(params.titleIconInfo == undefined)
		params.titleIconInfo = "Selecione uma entidade...";	
	if(params.titleIconOk == undefined)
		params.titleIconOk = "Entidade selecionada!";			
				
	var compId = $(this).attr('id');
	if(compId == undefined)
		return;
	
	var entitylabel = $(this).attr('entitylabel');
	var entityid = $(this).attr('entityid');
	var selected = false;
	var myComp = $(this);	
	var img = $("<img src='assets/autocomplete_info.png' title='" + params.titleIconInfo + "' />");
	var inputCod = $('<input type="hidden" id="' + $(this).attr('id') + '_id" name="' + $(this).attr('id') + '_id" value="0"/>');	
	if(entityid != '' && entityid != undefined && entitylabel != '' && entitylabel != undefined){
		img = $("<img src='assets/autocomplete_ok.png' title='" + params.titleIconOk + "' />");
		inputCod = $('<input type="hidden" id="' + $(this).attr('id') + '_id" name="' + $(this).attr('id') + '_id" value="' + entityid + '"/>');	
		$(this).val(entitylabel);
	}		
	
	$(this).parent().append(img);
	$(this).parent().append(inputCod);
	$(this).autocomplete({
		source: params.source,
		minLength: params.minLength,
		delay: params.delay,
		position: {  my: "center",
   at: "center",
   of: window  },
		select: function( event, ui ) {
			selected = true;
			$(inputCod).val(ui.item.id);
			$(img).attr("src", "assets/autocomplete_ok.png");
			$(img).attr("title", params.titleIconOk);
		}
	}).data("ui-autocomplete")._renderItem = function(ul, item){
		var matcher = new RegExp("("+$.ui.autocomplete.escapeRegex($(myComp).val())+")", "ig" );
		return $("<li>").append("<a>" + item.label.replace(matcher, "<strong>$1</strong>") + "</a>").appendTo(ul);
	};
	$(this).keyup(function(event){
		if(!event.shiftKey && event.keyCode != 16){ //shift-tab
			if(event.keyCode != 13 && event.keyCode != 35 && event.keyCode != 36 
				&& event.keyCode != 37 && event.keyCode != 38 && event.keyCode != 39 && event.keyCode != 40){ //enter, home/end e teclas direcionais
				if(!selected && $(this).val().length >= params.minLength){
					$(inputCod).val(0);
					$(img).attr("src", "assets/autocomplete_info.png");
					$(img).attr("title", params.titleIconInfo);
				}
			}
		}
		selected = false;		
	});			
};
$.fn.pickList = function(){	
	var selects = $(this).find('select');
	var selectLeft = selects[0];
	var selectRight = selects[1];
	var controls = $(this).find('.controls');
	var btAddAll = $('<input type="button" value=">>" style="width:45px; height:45px;" class="btn" title="Adicionar todos"/>')	
	var btAdd = $('<input type="button" value=">" style="width:45px; height:45px;" class="btn" title="Adicionar"/>');
	var btRemove = $('<input type="button" value="<" style="width:45px; height:45px;" class="btn" title="Remover"/>');
	var btRemoveAll = $('<input type="button" value="<<" style="width:45px; height:45px;" class="btn" title="Remover todos"/>');	
	$(this).closest('form').on('submit', this, function(){
		$(selectRight).find('option').prop('selected', true);			
	});	
	$(selectLeft).attr('multiple', 'multiple');
	$(selectRight).attr('multiple', 'multiple');
	$(selectLeft).multiSelect(selectRight, {trigger: btAdd, triggerAll: btAddAll});
	$(selectRight).multiSelect(selectLeft, {trigger: btRemove, triggerAll: btRemoveAll});
	controls.append(btAddAll).append(btAdd).append(btRemove).append(btRemoveAll);	
};
$.fn.dialogFind = function(params){
    var dialogId = $(this).attr('id');	
	$(this).dialog({
		autoOpen: false,
		width: 720,
		height: 450,
		modal: true		
	});	
	dialogs[dialogId] = {type:'find', partial:params.partial, testFunction:params.testFunction};
	dialogRefresh(dialogId);	
};
///////////////////////////////////////////////////////////////////////////////
//ManipulaÁ„o de dialogs, paginaÁ„o e relatÛrios //////////////////////////////
var dialogs = {};
function dialogRefresh(id){
	if(dialogs[id] != undefined){
		switch(dialogs[id].type){
			case 'find':
				$('#' + id + '_loader').dialog('close');
				$('#' + id + '_form').submit(function(){
					if(dialogs[id].testFunction()){
						$('#' + id + '_loader').dialog('open');
						$('#' + id).load('partials/' + dialogs[id].partial + '.php', $('#' + id + '_form').serializeObject(), function(){
							dialogRefresh(id);	
						});
					}
					return false;
				});	
				$('#' + id + '_submit').button({icons:{primary:'ui-icon-search'}});
				$('.buttonPag').hover(
					function(){ $(this).addClass('ui-state-hover'); }, 
					function(){ $(this).removeClass('ui-state-hover'); }
				);
				$('.tablelist tbody tr').each(function(){
					$(this).hover(
						function(){ $(this).addClass('hover'); }, 
						function(){ $(this).removeClass('hover'); }
					);
				});
				$('.datepicker').each(function(index){
					$(this).datepicker();
				});
				$('#' + id).append('<div id="' + id + '_loader" title="Carregando..."><div align="center"><img src="assets/loading.gif"/></div></div>');
				$('#' + id + '_loader').dialog({
					autoOpen: false,
					width: 120,
					height: 100,
					modal: true,
					closeOnEscape: false,
					dialogClass: 'no-close',
					hide: {effect: "fade"}
				});
			break;
		}
	}
}
function setPaginacaoAjax(dialogId, partial, page){
	$('#' + dialogId).load('partials/' + partial + '.php' + '?' + page, $('#' + dialogId + '_form').serializeObject(), function(){
		dialogRefresh(dialogId);
	}); //TODO	
}
function setPaginacao(link){
	$('#formPaginacao').attr('action', link);
	$('#formPaginacao').submit();
}
function setReport(key, value){
	$form = $('<form method="post"></form>');
	$form.append('<input type="hidden" name="' + key + '" value="' + value + '"/>');
	$('body').append($form);
	$form.submit();	
}
function setInfo(title, text, type){
	$.gritter.add({
		title: title,
		text: text,
		image: 'assets/css/gritter/growl_' + type + '.png'				
	});
}
///////////////////////////////////////////////////////////////////////////////
//M·scaras, formataÁ„o e validaÁ„o ////////////////////////////////////////////
function mask(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmask()",1)
}
function execmask(){
    v_obj.value=v_fun(v_obj.value)
}
function money(v){
	v=v.replace(/\D/g,"")
	v=v.replace(/[0-9]{12}/,"inv·lido")
	v=v.replace(/(\d{1})(\d{8})$/,"$1.$2")
	v=v.replace(/(\d{1})(\d{5})$/,"$1.$2")
	v=v.replace(/(\d{1})(\d{1,2})$/,"$1,$2")
	return v;
}
function format(o,m){
	i = o.value.length;
	saida = m.substring(0,1);	
	texto = m.substring(i);	
	if(texto.substring(0,1) != saida)
		o.value += texto.substring(0,1);	
}
function numeros(v){
    return v.replace(/\D/g,"")
}
function numerosNegativos(v){
	if(v.indexOf('-') === -1)
		return v.replace(/\D/g,"");
	else
		return "-" + v.replace(/\D/g,"");	
}
function data(v){
   	v=v.replace(/\D/g,"")                          
    v=v.replace(/^(\d{2})(\d)/,"$1/$2")                       
    v=v.replace(/(\d{2})(\d)/,"$1/$2")             
    return v
}
function valor(v){  
    v=v.replace(/\D/g,"") 
    v=v.replace(/[0-9]{12}/,"invè·lido") 
    v=v.replace(/(\d{1})(\d{8})$/,"$1.$2")
    v=v.replace(/(\d{1})(\d{5})$/,"$1.$2")
    v=v.replace(/(\d{1})(\d{1,2})$/,"$1,$2")
    return v;
}
function cpf(v){
	v=v.replace(/\D/g,"")                          
    v=v.replace(/^(\d{3})(\d)/,"$1.$2")             
    v=v.replace(/^(\d{3})\.(\d{3})(\d)/,"$1.$2.$3")      
    v=v.replace(/(\d{3})(\d)/,"$1-$2")             
    return v
}
function cnpj(v){
	v=v.replace(/\D/g,"")
	v=v.replace(/^(\d{12})(\d)/,"$1-$2")
	v=v.replace(/^(\d{8})(\d)/,"$1/$2")
	v=v.replace(/^(\d{5})(\d)/,"$1.$2")
	v=v.replace(/^(\d{2})(\d)/,"$1.$2")
	return v
}
function cep(v){
    v=v.replace(/\D/g,"")                          
    v=v.replace(/^(\d{2})(\d)/,"$1.$2")                       
    v=v.replace(/(\d{3})(\d)/,"$1-$2")             
    return v
}
function telefone(v){        
	v=v.replace(/\D/g,"")   
    v=v.replace(/^(\d)/,"($1")
    v=v.replace(/^\((\d\d)(\d)/g,"($1) $2") 
    v=v.replace(/(\d{5})(\d)/,"$1-$2")  
    return v
}
function telefoneonly(v){        
	v=v.replace(/\D/g,"")   
    //v=v.replace(/^(\d)/,"($1")
    //v=v.replace(/^\((\d\d)(\d)/g,"($1) $2") 
    v=v.replace(/(\d{4})(\d)/,"$1-$2")  
    return v
}
function checkCpf(cpf){   
	cpf = remove(cpf, ".");
	cpf = remove(cpf, "-");      
	if(cpf.length != 11 || cpf == "00000000000" || cpf == "11111111111" ||
		  cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" ||
		  cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" ||
		  cpf == "88888888888" || cpf == "99999999999"){
		  return false;   
	}
	soma = 0;
	for(i = 0; i < 9; i++)
		 soma += parseInt(cpf.charAt(i)) * (10 - i);
	resto = 11 - (soma % 11);
	if(resto == 10 || resto == 11)
		 resto = 0;
	if(resto != parseInt(cpf.charAt(9))){
		 return false;
	}
	soma = 0;
	for(i = 0; i < 10; i ++)
		 soma += parseInt(cpf.charAt(i)) * (11 - i);
	resto = 11 - (soma % 11);
	if(resto == 10 || resto == 11)
		 resto = 0;
	if(resto != parseInt(cpf.charAt(10))){
	 return false;
	}
	return true;
}
function checkCnpj(cnpj){
    var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais;
    digitos_iguais = 1;
    cnpj=cnpj.replace(/\D/g,"");    
	if (cnpj.length < 14 && cnpj.length < 15) {
		return false;				
	}
	for (i = 0; i < cnpj.length - 1; i++)
		if (cnpj.charAt(i) != cnpj.charAt(i + 1)){
				digitos_iguais = 0;
			break;
						
				}
	if (!digitos_iguais){
		tamanho = cnpj.length - 2
		numeros = cnpj.substring(0,tamanho);
		digitos = cnpj.substring(tamanho);
		soma = 0;
		pos = tamanho - 7;
		for (i = tamanho; i >= 1; i--){
				soma += numeros.charAt(tamanho - i) * pos--;
			if (pos < 2)
				pos = 9;
		}
		resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
		if (resultado != digitos.charAt(0))
				return false;
		tamanho = tamanho + 1;
		numeros = cnpj.substring(0,tamanho);
		soma = 0;
		pos = tamanho - 7;
		for (i = tamanho; i >= 1; i--){
				soma += numeros.charAt(tamanho - i) * pos--;
			if (pos < 2)
				pos = 9;
		}
		resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
		if (resultado != digitos.charAt(1))
				return false;
		return true;
		}
	else
		return false;
}
function checkData(data) {
	data = remove(data, "/");
	if(data.length != 8)
		return false;
        
    var dia=0, mes=0, ano=0;
    dia = parseInt(data.substr(0,2), 10);
    mes = parseInt(data.substr(2,2), 10);
    ano = parseInt(data.substr(4,4), 10);
    
	if (data =="")
		return false;
    if ((mes < 1) || (mes > 12)) {
		return false;           
    }   
    if ((dia < 1) || (dia > 31)){
		return false;
    }        
	if ((mes == 2) || (mes == 4) || (mes == 6) || (mes == 9) || (mes == 11)) {
		if (dia > 30) {
			return false;
		}
        if (mes == 2) {
            if (ano % 4 == 0) {
                if (dia > 29) {
                    return false;
                }
            }
            else {
                if (dia > 28) {
                    return false;
                }
            }
        }
    }   
	return true;    
} 
function checkMail(mail){
	var er = new RegExp(/^([A-Za-z0-9_\-\.]{3,})+@[A-Za-z0-9_\-\.]{2,}\.[A-Za-z0-9]{2,}(\.[A-Za-z0-9])?/);
	if(typeof(mail) == "string"){
		if(er.test(mail)){ 
			return true; 
		}
	}
}
function checkCNS(v){
	if(v.length != 15){
		return false;
	}
	else{
		if(v == "701200076607217"){
			return false;
		}
		else if(v.substring(0,1) == "1" || v.substring(0,1) == "2"){
			return checkCNS_final(v);
		}
		else if(v.substring(0,1) == "7" || v.substring(0,1) == "8" || v.substring(0,1) == "9"){
			return checkCNS_provisorio(v);
		}
		else{
			return false;
		}
	}
}
function checkCNS_final(vlrCNS){
	var soma = new Number;
    var resto = new Number;
	var dv = new Number;
    var pis = new String;
    var resultado = new String;
	pis = vlrCNS.substring(0,11);
	soma = (((Number(pis.substring(0,1))) * 15) +   
	        ((Number(pis.substring(1,2))) * 14) +
		    ((Number(pis.substring(2,3))) * 13) +
		    ((Number(pis.substring(3,4))) * 12) +
            ((Number(pis.substring(4,5))) * 11) +
            ((Number(pis.substring(5,6))) * 10) +
            ((Number(pis.substring(6,7))) * 9) +
            ((Number(pis.substring(7,8))) * 8) +
            ((Number(pis.substring(8,9))) * 7) +
            ((Number(pis.substring(9,10))) * 6) +
            ((Number(pis.substring(10,11))) * 5));
	resto = soma % 11;
    dv = 11 - resto;
	if (dv == 11) {
		dv = 0;
    }
	if (dv == 10) {
		soma = (((Number(pis.substring(0,1))) * 15) +   
	            ((Number(pis.substring(1,2))) * 14) +
		    	((Number(pis.substring(2,3))) * 13) +
		    	((Number(pis.substring(3,4))) * 12) +
            	((Number(pis.substring(4,5))) * 11) +
            	((Number(pis.substring(5,6))) * 10) +
            	((Number(pis.substring(6,7))) * 9) +
            	((Number(pis.substring(7,8))) * 8) +
            	((Number(pis.substring(8,9))) * 7) +
            	((Number(pis.substring(9,10))) * 6) +
            	((Number(pis.substring(10,11))) * 5) + 2);
		resto = soma % 11;
        dv = 11 - resto;
        resultado = pis + "001" + String(dv);
	} else { 
		resultado = pis + "000" + String(dv);
	}
	if (vlrCNS != resultado) {
		return false;
    } else {
		return true;
	}
}
function checkCNS_provisorio(value){
    var pis;
    var resto;
    var dv;
    var soma;
    var resultado;
    var result = 0;
    pis = value;
	soma = (   (parseInt(pis.substring( 0, 1),10)) * 15)
			+ ((parseInt(pis.substring( 1, 2),10)) * 14)
			+ ((parseInt(pis.substring( 2, 3),10)) * 13)
			+ ((parseInt(pis.substring( 3, 4),10)) * 12)
			+ ((parseInt(pis.substring( 4, 5),10)) * 11)
			+ ((parseInt(pis.substring( 5, 6),10)) * 10)
			+ ((parseInt(pis.substring( 6, 7),10)) * 9)
			+ ((parseInt(pis.substring( 7, 8),10)) * 8)
			+ ((parseInt(pis.substring( 8, 9),10)) * 7)
			+ ((parseInt(pis.substring( 9,10),10)) * 6)
			+ ((parseInt(pis.substring(10,11),10)) * 5)
			+ ((parseInt(pis.substring(11,12),10)) * 4)
			+ ((parseInt(pis.substring(12,13),10)) * 3)
			+ ((parseInt(pis.substring(13,14),10)) * 2)
			+ ((parseInt(pis.substring(14,15),10)) * 1);
	resto = soma % 11;
	if(resto == 0){
		return true;
	}
	else{
		return false;  
	}   
}
function remove(str, sub){
	i = str.indexOf(sub);
	r = "";
	if (i == -1) return str;
	r += str.substring(0,i) + remove(str.substring(i + sub.length), sub);
	return r;   
}
function addslashes(str) {
	str=str.replace(/\\/g,'\\\\');
	str=str.replace(/\'/g,'\\\'');
	str=str.replace(/\"/g,'\\"');
	str=str.replace(/\0/g,'\\0');
	return str;
}
function stripslashes(str) {
	str=str.replace(/\\'/g,'\'');
	str=str.replace(/\\"/g,'"');
	str=str.replace(/\\0/g,'\0');
	str=str.replace(/\\\\/g,'\\');
	return str;
}
function updateTips(t){
	var alert = '<div class="alert alert-danger">' +
		'<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>' +
		'<p>' + t + '</p>' +
	'</div>';
	$("#validateBox").html(alert);	
}		
function checkLength(f, n, min, t){
	var o = $('#' + f);
	if (o.val().length < min) {
		o.addClass("ui-state-error");
		if(t == undefined)
			updateTips(n + " n√£o pode ter menos que " +	min + " caracteres.");
		else
			updateTips(t);
		return false;
	} else {
		return true;
	}
}
function checkNumber(f, n, min, t){
	var o = $('#' + f);
	if (Number(o.val()) < min) {
		o.addClass("ui-state-error");
		o.focus();
		if(t == undefined)
			updateTips(n + " n√£o pode ser menor que " +	min + ".");
		else
			updateTips(t);
		return false;
	} else {
		return true;
	}
}
function checkRegexp(f, regexp, t){
	var o = $('#' + f);
	if (!( regexp.test(o.val()))){
		o.addClass("ui-state-error");
		updateTips(t);
		return false;
	} else {
		return true;
	}
}
///////////////////////////////////////////////////////////////////////////////
//Carregamento inicial do framework ///////////////////////////////////////////
$(function(){					
	$("#btAdd").click(function(){
		var link = window.location + '';
		link = link.split("&");
		window.location = link[0] + "&form=0";		
	});
	$("#btSearch").button({ icons:{primary:'ui-icon-search'} });
	$('#btCheckAll').click(function(){
		var check = this.checked;			
		$('.btCheck').each(function(){
			this.checked = check;
		});			
	});	
	$('.input-group.date').datepicker({
		format: "dd/mm/yyyy",
		language: "pt-BR",
		todayHighlight: true
	});	
	$('.input-mask-numbers').each(function(){
		$(this).keyup(function(){
			mask(this, numeros);		
		}).keypress(function(){
			mask(this, numeros);
		});
	});		
	$('.input-mask-numbers-negative').each(function(){
		$(this).keyup(function(){
			mask(this, numerosNegativos);		
		}).keypress(function(){
			mask(this, numerosNegativos);
		});
	});
	$('.input-mask-cpf').each(function(){
		$(this).keyup(function(){
			mask(this, cpf);		
		}).keypress(function(){
			mask(this, cpf);
		}).attr('maxlength', 14);
	});
	$('.input-mask-cnpj').each(function(){
		$(this).keyup(function(){
			mask(this, cnpj);		
		}).keypress(function(){
			mask(this, cnpj);
		}).attr('maxlength', 18);
	});
	$('.input-mask-date').each(function(){
		$(this).keyup(function(){
			mask(this, data);		
		})
		.keypress(function(){
			mask(this, data);
		})
		.attr('maxlength', 10)
		.attr('placeholder', 'dd/mm/yyyy');
	});
	$('.input-mask-valor').each(function(){
		$(this).keyup(function(){
			mask(this, valor);		
		})
		.keypress(function(){
			mask(this, valor);
		})
		.attr('maxlength', 14);
	});
	$('.input-mask-cep').each(function(){
		$(this).keyup(function(){
			mask(this, cep);		
		}).keypress(function(){
			mask(this, cep);
		}).attr('maxlength', 10);
	});	
	$('.input-mask-phone').each(function(){
		$(this).keyup(function(){
			mask(this, telefone);		
		}).keypress(function(){
			mask(this, telefone);
		}).attr('maxlength', 14);
	});
	$('.input-mask-phoneonly').each(function(){
		$(this).keyup(function(){
			mask(this, telefoneonly);		
		}).keypress(function(){
			mask(this, telefoneonly);
		}).attr('maxlength', 9);
	});
	$('.input-uppercase').each(function(){
		$(this).keyup(function(){
			$(this).val($(this).val().toUpperCase());
		});	
	});
	$('.input-lowercase').each(function(){
		$(this).keyup(function(){
			$(this).val($(this).val().toLowerCase());
		});	
	});
	
	$('#formInsertEdit').find('textarea').each(function(index){
		$(this).keydown(function(){
			if($(this).val().length > $(this).attr('maxlength')){
				$(this).val($(this).val().substring(0, $(this).attr('maxlength')));
			}		
		});
	});	
	$(document).ajaxStart(function(){
		$(".loader").show();
	});	
	$(document).ajaxStop(function(){
		$(".loader").hide();
	});	
});
Date.prototype.addDays = function(days) {
    this.setDate(this.getDate() + parseInt(days));
    return this;
};
Number.prototype.formatMoney = function(c, d, t){
var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

///////////////////////////////////////////////////////////////////////////////