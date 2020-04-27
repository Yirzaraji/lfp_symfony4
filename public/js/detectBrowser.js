function detectBrowser(){

    if((navigator.userAgent.indexOf("MSIE") != -1 ) || (!!document.documentMode == true ))
    //IF IE > 10 
    {
        $(document).ready(function(){
            $("#myModal").modal('show');
        });
    }
    else 
    {

    }

}