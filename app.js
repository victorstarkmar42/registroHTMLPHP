$(document).on("submit", ".form_registro", function(event){
    event.preventDefault();
    var $form = $(this);
   
    var data_form = {
        email: $("input[id='email']",$form).val(),
        password: $("input[id='password']", $form).val(),
        password2: $("input[id='password2']", $form).val(),
        name: $("input[id='name']", $form).val(),
        phone: $("input[id='phone']", $form).val(),
    }

if(data_form.email.length < 6 ){
    $("#msg_error").text("Necesitamos un email valido.").show();
    return false;        
}else if(data_form.password.length < 8){
    $("#msg_error").text("Tu password debe ser minimo de 8 caracteres.").show();
    return false;   
}else if(data_form.password2.length < 8){
    $("#msg_error").text("Tu password debe ser minimo de 8 caracteres.").show();
    return false;   
}else if(data_form.password != data_form.password2){
    $("#msg_error").text("Las contraseñas no coinciden").show();
    return false;   
}


$("#msg_error").hide();
var url_php = 'http://localhost/sistem2/registro_backend.php';

$.ajax({
    type:'POST',
    url: url_php,
    data: data_form,
    dataType: 'json',
    async: true,
})
.done(function ajaxDone(res){
   console.log(res); 
   if(res.error !== undefined){
        $("#msg_error").text(res.error).show();
        return false;
   } 

   if(res.redirect !== undefined){
    window.location = res.redirect;
}
})
.fail(function ajaxError(e){
    console.log(e);
})
.always(function ajaxSiempre(){
    console.log('Final de la llamada ajax.');
})
return false;
});
