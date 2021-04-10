let checkboxes = document.getElementsByClassName('checkbox');

document.getElementById('selectAll').addEventListener('click', function () {
    Array.from(checkboxes).forEach(function (checkbox) {
        if (!checkbox.checked) {
            checkbox.checked = true;
        }
    })
});

document.getElementById('unselectAll').addEventListener('click', function () {
    Array.from(checkboxes).forEach(function (checkbox) {
        if (checkbox.checked) {
            checkbox.checked = false;
        }
    })
});
