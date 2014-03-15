function RadioValidator($formId)
{
    var ShowAlert = '';
    var AllFormElements = window.document.getElementById("FormID").elements;
    for (i = 0; i < AllFormElements.length; i++) 
    {
        if (AllFormElements[i].type == 'radio') 
        {
            var ThisRadio = AllFormElements[i].name;
            var ThisChecked = 'No';
            var AllRadioOptions = document.getElementsByName(ThisRadio);
            for (x = 0; x < AllRadioOptions.length; x++)
            {
                 if (AllRadioOptions[x].checked && ThisChecked == 'No')
                 {
                     ThisChecked = 'Yes';
                     break;
                 } 
            }   
            var AlreadySearched = ShowAlert.indexOf(ThisRadio);
            if (ThisChecked == 'No' && AlreadySearched == -1)
            {
            ShowAlert = ShowAlert + ThisRadio + ' radio button must be answered\n';
            }     
        }
    }
    if (ShowAlert != '')
    {
    alert(ShowAlert);
    return false;
    }
    else
    {
    return true;
    }
}
