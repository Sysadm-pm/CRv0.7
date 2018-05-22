$(document).ready(function(){

  //Проверка на незаполненные поля в форме
  $("#CommentForm .captcha button").click(function(){
    if (document.getElementById("commentUser").value=="" || document.getElementById("commentUser").value=="Имя"){alert("Заполните поле Имя");}
    else if (document.getElementById("commentEmail").value=="" || document.getElementById("commentEmail").value=="E-mail"){alert("Заполните поле E-mail");}
    else if (!validateEmail(document.getElementById("commentEmail").value)){alert("Некорректно введен E-mail");}
    else if (document.getElementById("commentMessage").value==""){alert("Заполните поле Сообщение");}
    else if (document.getElementById("commentCaptcha").value==""){alert("Введите код");}
    else{document.forms.CommentFormSend.submit();}
  });

  //При клике на изображении “Закрыть окно”, скрывать форму.
  $("#CommentForm .header div a").click(function(){
    document.getElementById("CommentForm").style.display="none";
  });

});

function validateEmail(email) {
  //проверка введённого email
  var reg = /^(([^<>()[].,;:s@"]+(.[^<>()[].,;:s@"]+)*)|(".+"))@(([[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}])|(([a-zA-Z-0-9]+.)+[a-zA-Z]{2,}))$/
  if (!email.match(reg)) {
    return (false);
  }
  return(true);
}
//Функция внедряет дочерний блок с комментариями в родительский
function CommentView_TeleportChild(parent,id){
    document.getElementById("CommentView_Child"+parent) .appendChild(document.getElementById("CommentView_Parent"+id));
}
//Отобразить Форму в нужном месте и задать скрытому полю CommentParent ID родительского комментария
function CommentView_FormView(parent){
    document.getElementById("CommentView_Form"+parent) .appendChild(document.getElementById("CommentForm"));
    document.getElementById("CommentForm").style.display="block";
    document.getElementById("CommentParent").value=parent;
}

$(document).ready(function(){

    //Проверка на незаполненные поля в форме
    $("#CommentForm .captcha button").click(function(){
        if (document.getElementById("commentUser").value=="" || document.getElementById("commentUser").value=="Имя"){alert("Заполните поле Имя");}
        else if (document.getElementById("commentEmail").value=="" || document.getElementById("commentEmail").value=="E-mail"){alert("Заполните поле E-mail");}
        else if (!validateEmail(document.getElementById("commentEmail").value)){alert("Некорректно введен E-mail");}
        else if (document.getElementById("commentMessage").value==""){alert("Заполните поле Сообщение");}
        else if (document.getElementById("commentCaptcha").value==""){alert("Введите код");}
        else{document.forms.CommentFormSend.submit();}
    });

    //При клике на изображении “Закрыть окно”, скрывать форму.
    $("#CommentForm .header div a").click(function(){
        document.getElementById("CommentForm").style.display="none";
    });

});

function validateEmail(email) {
    //проверка введённого email
    var reg = /^(([^<>()[].,;:s@"]+(.[^<>()[].,;:s@"]+)*)|(".+"))@(([[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}])|(([a-zA-Z-0-9]+.)+[a-zA-Z]{2,}))$/
    if (!email.match(reg)) {
        return (false);
    }
    return(true);
}