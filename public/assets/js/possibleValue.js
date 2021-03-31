let del = function (event) {
    let target = event.target;
    target.nextElementSibling.remove();
    target.previousElementSibling.remove();
    target.remove();
}

document.getElementById('add').addEventListener('click', function (event) {
    let input = document.createElement('input');
    let delButton = document.createElement('input');
    delButton.type = 'button';
    delButton.value = '-';
    delButton.addEventListener('click', del);
    input.type = 'text';
    input.className = 'value';
    input.name = 'values[]';
    input.required = true;
    document.getElementById('add').before(input, delButton, document.createElement('br'));
});

let delItem  = document.getElementsByClassName('del');
Array.from(delItem).forEach(function (item) {
    item.addEventListener('click', del);
});