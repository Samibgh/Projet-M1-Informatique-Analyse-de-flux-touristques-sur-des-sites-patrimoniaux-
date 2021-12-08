
function validateForm() {
    let messageStatus = "";
    let error = false;

    document.getElementById('name-div').style.backgroundColor = "#fff";
    document.getElementById('firstname-div').style.backgroundColor = "#fff";
    document.getElementById('email-div').style.backgroundColor = "#fff";
    document.getElementById('subject-div').style.backgroundColor = "#fff";
    document.getElementById('message-div').style.backgroundColor = "#fff";

    console.log("validate fonction");
    var name =  document.getElementById('name').value;
    if (name == "") {
        document.querySelector('.status').classList.remove("d-none");
        document.querySelector('.status').classList.add("alert-danger");
        messageStatus += "Le nom ne doit pas être vide<br>";
        document.getElementById('name-div').style.backgroundColor = "#fba";
        error = true;
    }
    var firstname =  document.getElementById('firstname').value;
    if (firstname == "") {
        document.querySelector('.status').classList.remove("d-none");
        document.querySelector('.status').classList.add("alert-danger");
        messageStatus += "Le prénom ne doit pas être vide<br>";
        document.getElementById('firstname-div').style.backgroundColor = "#fba";
        error = true;
    }
    var email =  document.getElementById('email').value;
    if (email == "") {
        document.querySelector('.status').classList.remove("d-none");
        document.querySelector('.status').classList.add("alert-danger");
        messageStatus +="L'email ne doit pas être vide<br>";
        document.getElementById('email-div').style.backgroundColor = "#fba";
        error = true;
    } else {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if(!re.test(email)){
            document.querySelector('.status').classList.remove("d-none");
            document.querySelector('.status').classList.add("alert-danger");
            messageStatus += "Format d'email invalide<br>";
            document.getElementById('email-div').style.backgroundColor = "#fba";
            error = true;
        }
    }
    var subject =  document.getElementById('subject').value;
    if (subject == "") {
        document.querySelector('.status').classList.remove("d-none");
        document.querySelector('.status').classList.add("alert-danger");
        messageStatus += "Le sujet ne doit pas être vide";
        document.getElementById('subject-div').style.backgroundColor = "#fba";
        error = true;
    }
    var message =  document.getElementById('message').value;
    if (message == "") {
        document.querySelector('.status').classList.remove("d-none");
        document.querySelector('.status').classList.add("alert-danger");
        messageStatus += "Le message ne doit pas être vide<br>";
        document.getElementById('message-div').style.backgroundColor = "#fba";
        error = true;
    }


    if(error){
        console.log("pas ok");
        document.querySelector('.status').classList.remove("d-none");
        document.querySelector('.status').classList.add("alert-danger");
        document.querySelector('.status').innerHTML = messageStatus;
    }
    else{
        console.log("ok");
        document.querySelector('.status').innerHTML = "Le message a bien été envoyé, merci !";
        document.querySelector('.status').classList.remove("d-none");
        document.querySelector('.status').classList.remove("alert-danger");
        document.querySelector('.status').classList.add("alert-success");

        document.getElementById('name').value = "";
        document.getElementById('firstname').value = "";
        document.getElementById('email').value = "";
        document.getElementById('subject').value = "";
        document.getElementById('message').value = "";
    }

  }

document.addEventListener('DOMContentLoaded', function(){
  let police = 0.9;

  let taillep = document.getElementById('texte+');
  let taillem = document.getElementById('texte-');
  
  taillep.onclick = function () {
    police += 0.1;
    document.body.style.fontSize = police + "rem";
  };

  taillem.onclick = function () {
    if(police > 0.1) {
      police -= 0.1;
      document.body.style.fontSize = police + "rem";
    }
  };
}, false);
