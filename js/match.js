window.onload = function() {
    document.getElementById('match').addEventListener('submit', validate);
}

function validate(event) {
    var form = document.forms['match'];
    var date = form['date'].value;
    var starttime = form['starttime'].value;
    var location = form['location'].value;
    var info = form['info'];

    if (!validateDate(date) || !validateTime(starttime) || !validateLocation(location) || !validateInfo(info)) {
        event.preventDefault();
        return;
    }
}