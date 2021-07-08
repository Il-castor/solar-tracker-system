$(document).ready(function () {

    $("#commentForm").validate({
  rules: {
    name: "required",
    email: {
      required: true,
      email: true
    },
    message: "required"
  },
  messages: {
    name: "Inserisci il nome",
    email: {
      required: "Inserisci il tuo indirizzo mail",
      email: "Il tuo indirizzo mail deve essere in questo formato: nome@dominio.com"
    },
    message: "Inserisci il corpo del messaggio"
  }
});


});