function clicktab() {
    //	����������� � ��������
    $('th, td', $(this).closest('tr'))
            .not($(this))
            .not('.nosort')
            .removeClass('sortDown sortUp')
            .addClass('sortHead');
    if ($(this).hasClass('sortHead')) {
        $(this).
                removeClass('sortHead').
                addClass('sortDown').
                attr('title', '������������� �� �����������');
    } else if ($(this).hasClass('sortDown')) {
        $(this).
                removeClass('sortDown').
                addClass('sortUp').
                attr('title', '������������� �� �����������');
    } else if ($(this).hasClass('sortUp')) {
        $(this).
                removeClass('sortUp').
                addClass('sortDown').
                attr('title', '������������� �� ��������');
    }
    //	����������
    var cellIndex = $('th, td', $(this).closest('tr')).index($(this));
    var rows = $('tbody tr', $(this).closest('table'));
    var sarr = [];
    $(rows).each(function (ndx, el) {
        var text = $('th, td', $(el)).eq(cellIndex).text();
        var a = /^(0[1-9]|[12][0-9]|3[01])\.(0[1-9]|1[012])\.(19|20)\d\d$/
        var fr = /^\d+(\.\d{0,})?$/;
        if (a.test(text)) {
            text = text.split('.').reverse().join('.');
        } else if (fr.test(text)) {
            text = parseFloat(text).toString();
            while (text.length < 10)
                text = '0' + text;
        } else {
        }
        sarr[sarr.length] = [text, $(el).html()];
    });
    sarr = sarr.sort();
    if ($(this).hasClass('sortUp')) {
        sarr = sarr.reverse();
    }
    for (var i = 0; i < sarr.length; i++) {
        $(rows).eq(i).html($(sarr[i][1]));
    }
}

$(function () {
    $('table.sortable').each(function (index, el) {
        if ($('thead', $(el)).length == 0) {
            $(el).prepend('<thead></thead>');
            $('thead', el).append($('tr', el).first());
        }
        $('thead', $(el)).
                addClass('sortHead').
                find('th, td').
                not('.nosort').//css('background-color', 'yellow').
                addClass('sortHead').
                attr('title', '�����������').
                click(clicktab)
    })
})

