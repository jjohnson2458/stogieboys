/*
* global javasctipt funcitons
*/

function checkDecimal(obj, objStr){
    var objNumber;
    if(isNaN(objStr) && objStr!=''){
        alert('Value entered is not numeric');
        objNumber = '0.00';
    }
    else if(objStr==''){
        objNumber = '0.00';
    }
    else if(objStr.indexOf('.')!=-1){
        if(((objStr.length) - (objStr.indexOf('.')))>3){
            objStr = objStr.substr(0,((objStr.indexOf('.'))+3));
        }
        if(objStr.indexOf('.')==0){
            objStr = '0' + objStr;
        }
        var sLen = objStr.length;
        var TChar = objStr.substr(sLen-3,3);
        if(TChar.indexOf('.')==0){
            objNumber = objStr;
        }
        else if(TChar.indexOf('.')==1){
            objNumber = objStr + '0';
        }
        else if(TChar.indexOf('.')==2){
            objNumber = objStr + '00';
        }
    }
    else{
        objNumber = objStr + '.00';
    }
    obj.value = objNumber;
}

function checkInteger(pField)
{
var pattern=/^[0-9]*$/;
	if(!(pattern.test(pField.value)) & (pField.value!="")){
		alert(pField.value + " is not an integer");
		pField.focus();
		return false;
	} else {
		return true;
	}
}


function disableEnterKey(e)
{
     var key;      
     if(window.event)
          key = window.event.keyCode; //IE
     else
          key = e.which; //firefox      

     return (key != 13);
}

var prevColor;

function changeColor(id,color) {
if (color==='prev') {
id.style.background = window.prevColor;
}
else {
window.prevColor = id.style.background;
id.style.background = color;
}
}