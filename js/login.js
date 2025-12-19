$(document).ready(function() {
  alert('login.js loaded!'); // Test: Harus muncul saat page load. Hilangkan setelah OK
  console.log('jQuery loaded and ready');

  $('#loginBtn').click(function() {
    login();
  });

  function togglePassword() {
    var passwordField = document.getElementById('password');
    var icon = document.querySelector('.toggle-password img');
    if (passwordField.type === 'password') {
      passwordField.type = 'text';
    } else {
      passwordField.type = 'password';
    }
  }
  window.togglePassword = togglePassword;

  function login() {
    console.log('Login function called');
    var username = $('#username').val().trim();
    var password = $('#password').val();
    if (!username || !password) {
      $('#errorMsg').text('Isi username dan password').show();
      console.log('Validation failed: Empty fields');
      return;
    }
    $('#errorMsg').hide();
    console.log('Sending AJAX to php/login.php with user:', username); // Fix path di url jika perlu
    $.ajax({
      url: 'php/login.php', // Fix: Tanpa ../ jika di root
      type: 'POST',
      data: { username: username, password: password },
      dataType: 'json',
      success: function(res) {
        console.log('AJAX success response:', res);
        if (res.success) {
          console.log('Redirecting to:', res.redirect);
          window.location.href = res.redirect;
        } else {
          $('#errorMsg').text(res.error || 'Login gagal').show();
          console.log('Error from server:', res.error);
        }
      },
      error: function(xhr, status, error) {
        console.error('AJAX error:', status, error, xhr.responseText);
        $('#errorMsg').text('Error koneksi: ' + error).show();
        alert('Cek console untuk detail error');
      }
    });
  }
});