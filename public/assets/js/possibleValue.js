document.getElementById('add').addEventListener('click', function (event) {
    let input = document.createElement('input');
    input.type = 'text';
    input.className = 'value';
    input.name = 'values[]';
    let pos = document.getElementsByClassName('value').length;
    document.getElementsByClassName('value').item(pos-1).after(document.createElement('br'), input);
});

