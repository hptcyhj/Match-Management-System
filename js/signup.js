window.onload = function() {
    document.getElementById('signup').addEventListener('submit', validate);
}

function validate(event) {
    var form = document.forms['signup'];
    var username = form['username'].value;
    var password = form['password'].value;
    var email = form['email'].value;
    var phone = form['phone'].value;

    if (!validateUsername(username) || !validatePassword(password) || !validateEmail(email) || !validatePhone(phone)) {
        event.preventDefault();
        return;
    }
}