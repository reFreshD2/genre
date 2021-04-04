let del = function (event) {
    let target = event.target;
    target.parentNode.remove();
    target.remove();
}

document.getElementById('add').addEventListener('click', function (event) {
    document.getElementById('add').parentNode.before(createGroup());
});

function createGroup() {
    let divGroup = document.createElement('div');
    divGroup.className = 'group';
    let label = document.createElement('label');
    label.className = 'dis';
    label.append('Значение качественного признака ');
    let delButton = document.createElement('input');
    delButton.type = 'button';
    delButton.value = 'x';
    delButton.className = 'del';
    delButton.addEventListener('click', del);
    let input = document.createElement('input');
    input.type = 'text';
    input.className = 'value input';
    input.name = 'values[]';
    input.required = true;
    let highlight = document.createElement('span');
    highlight.className = 'highlight';
    let bar = document.createElement('span');
    bar.className = 'bar';
    divGroup.appendChild(label);
    divGroup.appendChild(delButton);
    divGroup.appendChild(input);
    divGroup.appendChild(highlight);
    divGroup.appendChild(bar);
    return divGroup;
}

let delItem  = document.getElementsByClassName('del');
Array.from(delItem).forEach(function (item) {
    item.addEventListener('click', del);
});
