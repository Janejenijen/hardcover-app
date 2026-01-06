$(document).ready(function () {
  $('#loginBtn').click(function () {
    login();
  });

  // Enter key to submit
  $('#password').keypress(function (e) {
    if (e.which === 13) login();
  });

  function togglePassword() {
    var passwordField = document.getElementById('password');
    passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
  }
  window.togglePassword = togglePassword;

  function login() {
    var username = $('#username').val().trim();
    var password = $('#password').val();

    if (!username || !password) {
      $('#errorMsg').text('Isi username dan password').show();
      return;
    }

    $('#errorMsg').hide();
    $('#loginBtn').prop('disabled', true).text('Loading...');

    $.ajax({
      url: 'php/login.php',
      type: 'POST',
      data: { username: username, password: password },
      dataType: 'json',
      success: function (res) {
        if (res.success) {
          window.location.href = res.redirect;
        } else {
          $('#errorMsg').text(res.error || 'Login gagal').show();
          $('#loginBtn').prop('disabled', false).text('Masuk');
        }
      },
      error: function () {
        $('#errorMsg').text('Error koneksi ke server').show();
        $('#loginBtn').prop('disabled', false).text('Masuk');
      }
    });
  }
});