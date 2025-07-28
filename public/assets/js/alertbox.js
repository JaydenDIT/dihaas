function ConfirmDialog(title, msg, $true="Yes", $false="No") { /*change*/
    var $content =  "<div class='dialog-ovelay'>" +
                    "<div class='dialog'><header>" +
                        " <h3> " + title + " </h3> " +
                        "<i class='fa fa-close'></i>" +
                    "</header>" +
                    "<div class='dialog-msg'>" +
                        " <p> " + msg + " </p> " +
                    "</div>" +
                    "<footer>" +
                        "<div class='controls'>" +
                            " <button class='button button-danger doAction'>" + $true + "</button> " +
                            " <button class='button button-default cancelAction'>" + $false + "</button> " +
                        "</div>" +
                    "</footer>" +
                "</div>" +
            "</div>";
    $('body').prepend($content); 
    $('.dialog-ovelay').fadeIn(500, function () {
        //$(this).remove();
    });   
    $('.doAction').click(function () {
        //window.open($link, "_blank"); 
        $(this).parents('.dialog-ovelay').fadeOut(500, function () {
            $(this).remove();
        });
    });
    $('.cancelAction, .fa-close').click(function () {
        $(this).parents('.dialog-ovelay').fadeOut(500, function () {
            $(this).remove();
        });
    });   
    
}
/*
const ui = {
    confirm: async (title,message) => createConfirm(title,message)
}
*/
  
//const createConfirm = (title,message) => {
async function createConfirmDialog(title,message){
    return new Promise((complete, failed)=>{

        var $content =  "<div class='dialog-ovelay'>" +
                    "<div class='dialog'><header>" +
                        " <h3> " + title + " </h3> " +
                        "<i class='fa fa-close'></i>" +
                    "</header>" +
                    "<div class='dialog-msg'>" +
                        " <div> " + message + " </div> " +
                    "</div>" +
                    "<footer>" +
                        "<div class='controls'>" +
                            " <button id='confirmYes' class='button button-danger doAction'>Yes</button> " +
                            " <button id='confirmNo' class='button button-default cancelAction'>No</button> " +
                        "</div>" +
                    "</footer>" +
                "</div>" +
            "</div>";
        $('body').prepend($content);
        $('.doAction').click(function () {            
            $(this).parents('.dialog-ovelay').fadeOut(500, function () {
                $(this).remove();
            });
            complete(true);
        });
        $('.cancelAction, .fa-close').click(function () {
            $(this).parents('.dialog-ovelay').fadeOut(500, function () {
                $(this).remove();
            });
            complete(false);
        });
        
    });
}
                       
async function getConfirm(title, message){
    //const confirm = await ui.confirm(title, message);
    const confirm = await createConfirmDialog(title, message);
    
    if(confirm){
      //alert('yes clicked');
    } else{
      //alert('no clicked');
    }
    
    //return confirm;
}