function check_date(input){
var validformat=/^\d{4}\-\d{2}\-\d{2}$/ //Basic check for format validity
var returnval=false
if (!validformat.test(input.value))
alert("Invalid Date Format. Please correct and submit again.")
else{ //Detailed check for valid date ranges
var monthfield=input.value.split("-")[1]
var dayfield=input.value.split("-")[2]
var yearfield=input.value.split("-")[0]
var dayobj = new Date(yearfield, monthfield-1, dayfield)
if ((dayobj.getMonth()+1!=monthfield)||(dayobj.getDate()!=dayfield)||(dayobj.getFullYear()!=yearfield))
alert("Invalid Day, Month, or Year range detected. Please correct and submit again.")
else
returnval=true
}
if (returnval==false) input.select()
return returnval
}
    
function check_time(input){
var validformat=/^\d{2}\:\d{2}$/
var returnval=false
if(!validformat.test(input.value))
        alert("Invalid Time Format. Please enter as HH:MM")
else {
        var hours=input.value.split(":")[0]
        var minutes=input.value.split(":")[1]
        
        if(hours>24||minutes>59){
                alert("Sorry time values out of range. Please enter valid values")
        }
        else
            returnval=true
}
return returnval
}

function check_everything(form_name){
    if(form_name.ename.value.match(/^\s*$/)){
        alert("Sorry! You have to enter an event name.");
        form_name.ename.focus();
        return false
    }
    form_name.ename.value = form_name.ename.value.replace(/^\s+|\s+$/g,'')
    result = check_date(form_name.start_date);
    if (result==false){ form_name.start_date.focus(); return false}
    result = check_date(form_name.end_date);
    if (result==false) {form_name.end_date.focus(); return false}
    result = check_time(form_name.start_time);
    if (result==false) {form_name.start_time.focus(); return false}
    result = check_time(form_name.end_time);
    if (result==false) {form_name.end_time.focus(); return false}
 
}      