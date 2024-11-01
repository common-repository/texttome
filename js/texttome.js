function validatephone(phoneNumber)
{
    var maintainplus = '';
    var numval = phoneNumber.value
    if ( numval.charAt(0)=='+' )
    {
        var maintainplus = '';
    }
    curphonevar = numval.replace(/[\\A-Za-z!"£$%^&\,*+_={};:'@#~,.Š\/<>?|`¬\]\[]/g,'');
    phoneNumber.value = maintainplus + curphonevar;
    var maintainplus = '';
    phoneNumber.focus;
}