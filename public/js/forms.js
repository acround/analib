/*
 * Работа с формами
 */

function form2object(form)
{
    var selectors = new Array(
            'input:hidden:enabled',
            'input:text:enabled',
            'input:password:enabled',
            'input:radio:enabled:checked',
            'input:checkbox:enabled:checked',
            'input:submit:enabled',
            'button:submit:enabled',
            'textarea:enabled'
            );
    var selector = selectors.join(', ');
    var elements = $(selector, form);
    var object = {};
    $(elements).each(function (index, element) {
        object[$(element).attr('name')] = $(element).val();
    });
    return object;
}