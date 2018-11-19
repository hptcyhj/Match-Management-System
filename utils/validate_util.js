function showError(divName, helpName) {
    var div = document.getElementById(divName);
    var help = document.getElementById(helpName);
    div.classList.add("has-error");
    help.classList.remove("hidden");
}

function removeError(divName, helpName) {
    var div = document.getElementById(divName);
    var help = document.getElementById(helpName);
    div.classList.remove("has-error");
    help.classList.add("hidden");
}

function validateUsername(username) {
    var regex = /^[a-zA-Z\s]+$/;
    if (username.length < 2 || username.length > 50 || !regex.test(username)) {
        showError("div-username", "username-invalid");
        return false;
    }
    else {
        removeError("div-username", "username-invalid");
        return true;
    }
}

function validatePassword(password) {
    if (password.length < 6 || password.length > 50) {
        showError("div-password", "password-invalid");
        return false;
    }
    else {
        removeError("div-password", "password-invalid");
        return true;
    }
}

function validateEmail(email) {
    var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (!regex.test(email) || email.length < 6 || email.length > 255) {
        showError("div-email", "email-invalid");
        return false;
    }
    else {
        removeError("div-email", "email-invalid");
        return true;
    }
}

function validatePhone(phone) {
    if (phone.length < 3 || phone.length > 30 || isNaN(phone)) {
        showError("div-phone", "phone-invalid");
        return false;
    }
    else {
        removeError("div-phone", "phone-invalid");
        return true;
    }
}

function validateDate(date) {
    var regex = /^\d{4}-\d{1,2}-\d{1,2}$/;
    if (!regex.test(date) || isNaN(Date.parse(date))) {
        showError("div-date", "date-invalid");
        return false;
    }
    else {
        removeError("div-date", "date-invalid");
        return true;
    }
}

function validateTime(time) {
    var hhmm = /(2[0-3]|[01][0-9]):([0-5][0-9])/;
    var hhmmss = /^(?:2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/;
    if ((time.length == 5 && hhmm.test(time)) || (time.length == 8 && hhmmss.test(time))) {
        removeError("div-time", "time-invalid");
        return true;
    }
    else {
        showError("div-time", "time-invalid");
        return false;
    }
}

function validateLocation(location) {
    if (location.length < 3 || location.length > 100) {
        showError("div-location", "location-invalid");
        return false;
    }
    else {
        removeError("div-location", "location-invalid");
        return true;
    }
}

function validateInfo(info) {
    if (info.length > 1000) {
        showError("div-info", "info-invalid");
        return false;
    }
    else {
        removeError("div-info", "info-invalid");
        return true;
    }
}