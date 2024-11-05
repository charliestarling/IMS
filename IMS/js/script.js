var check = function() {
    if (document.getElementById('password').value !=
      document.getElementById('confirm_password').value) {
      document.getElementById('confirm_message').style.color = 'green';
      document.getElementById('confirm_message').innerHTML = 'matching';
      document.getElementById('confirm_message').style.color = 'red';
      document.getElementById('confirm_message').innerHTML = "Error: Passwords don't match";
    } else{
        document.getElementById('confirm_message').innerHTML = '';
    }
}