
$(document).ready(function() {
    // alert('JQuery init');
});

document.getElementById('ccn').addEventListener('input', function (e) {
    var input = e.target;
    var trimmed = input.value.replace(/\s+/g, ''); // Видалення пробілів
    var digits = trimmed.replace(/\D/g, '');
    var formatted = '';
    for (var i = 0; i < digits.length; i++) {
        if (i > 0 && i % 4 === 0) {
            formatted += ' ';
        }
        formatted += digits[i];
    }
    input.value = formatted;
});
var selection = window.getSelection();



