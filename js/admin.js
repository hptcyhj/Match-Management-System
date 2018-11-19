window.onload = function () {
    addEventListenerByClass('reg', 'click', function (event) {
        register(event);
    });

    addEventListenerByClass('unreg', 'click', function (event) {
        unregister(event);
    });

    addEventListenerByClass('update', 'click', function(event) {
        updateMatch(event);
    });

    addEventListenerByClass('delete', 'click', function(event) {
        deleteMatch(event);
    });

    addEventListenerByClass('reset', 'click', function(event) {
        resetMatch(event);
    });
}

function addEventListenerByClass(className, event, fn) {
    var list = document.getElementsByClassName(className);
    for (var i = 0; i < list.length; i++) {
        list[i].addEventListener(event, fn);
    }
}

function register(event) {
    regHelper('reg', event.currentTarget.value);
}

function unregister(event) {
    regHelper('unreg', event.currentTarget.value);
}

function regHelper(type, matchId) {
    var form = document.createElement('form');
    form.setAttribute('method', 'POST');
    form.setAttribute('action', 'admin.php');

    var input1 = document.createElement('input');
    input1.setAttribute('type', 'hidden');
    input1.setAttribute('name', 'type');
    input1.setAttribute('value', type);

    var input2 = document.createElement('input');
    input2.setAttribute('type', 'hidden');
    input2.setAttribute('name', 'matchId');
    input2.setAttribute('value', matchId);

    form.appendChild(input1);
    form.appendChild(input2);
    document.body.appendChild(form);
    form.submit();
}

function updateMatch(event) {
    helper('update', event.currentTarget.value);
}

function resetMatch(event) {
    helper('reset', event.currentTarget.value);
}

function deleteMatch(event) {
    helper('delete', event.currentTarget.value);
}

function helper(type, matchId) {
    var form = document.createElement('form');
    form.setAttribute('method', 'POST');

    if (type == 'update') {
        form.setAttribute('action', 'update.php');
    }
    else {
        form.setAttribute('action', 'admin.php');
    }
    

    var input1 = document.createElement('input');
    input1.setAttribute('type', 'hidden');
    input1.setAttribute('name', 'type');
    input1.setAttribute('value', type);

    var input2 = document.createElement('input');
    input2.setAttribute('type', 'hidden');
    input2.setAttribute('name', 'matchId');
    input2.setAttribute('value', matchId);

    form.appendChild(input1);
    form.appendChild(input2);
    document.body.appendChild(form);
    form.submit();
}