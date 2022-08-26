/**
 * Даты в JavaScript: количество дней в месяце
 * usage:new Date().daysInMonth()
 * @returns {Number}
 */
Date.prototype.daysInMonth = function () {
    return 33 - new Date(this.getFullYear(), this.getMonth(), 33).getDate();
};

/**
 * @description разность между двумя датами (date1-date2)
 * Даты принимаются в формате dd.mm.yyyy
 *
 * @param {String} date1
 * @param {String} date2
 * @returns {String}
 */
function subDate(date1, date2) {
    var vdate1 = date1.split('.');
    var vdate2 = date2.split('.');
    var year = vdate1[2] - vdate2[2];
    var month = vdate1[1] - vdate2[1];
    var day = vdate1[0] - vdate2[0];
    if (month < 0) {
        year--;
        month = 12 + month;
    }
    if (day < 0) {
        month--;
        var dim = new Date(vdate1[2], (parseInt(vdate1[1]) - 2), 1).daysInMonth();
        day = dim + day;
    }
    return year + '.' + month + '.' + day;
}

/**
 * Вычисляет интервал между датами в виде массива: [лет, месяцев, дней]
 *
 * @param {Date} d1
 * @param {Date} d2
 * @returns {Array}
 */
function dateInterval(d1, d2) {
    var y = d2.getFullYear() - d1.getFullYear();
    var m = d2.getMonth() - d1.getMonth();
    if (m < 0) {
        y--;
        m += 12;
    }
    var d = d2.getDate() - d1.getDate();
    if (d < 0) {
        m--;
//		d += 30;
        d += new Date(d2.getFullYear(), d2.getMonth(), 1).daysInMonth();
        if (m < 0) {
            y--;
            m += 12;
        }
    }
    return new Array(y, m, d);
}

