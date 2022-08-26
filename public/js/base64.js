
// ������� ����������� ������ � base64
function base64encode(str) {
    // ������� ��� base64-��������������
    var b64chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefg' +
            'hijklmnopqrstuvwxyz0123456789+/=';
    var b64encoded = '';
    var chr1, chr2, chr3;
    var enc1, enc2, enc3, enc4;

    for (var i = 0; i < str.length; ) {
        chr1 = str.charCodeAt(i++);
        chr2 = str.charCodeAt(i++);
        chr3 = str.charCodeAt(i++);

        enc1 = chr1 >> 2;
        enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);

        enc3 = isNaN(chr2) ? 64 : (((chr2 & 15) << 2) | (chr3 >> 6));
        enc4 = isNaN(chr3) ? 64 : (chr3 & 63);

        b64encoded += b64chars.charAt(enc1) + b64chars.charAt(enc2) +
                b64chars.charAt(enc3) + b64chars.charAt(enc4);
    }
    return b64encoded;
}


// ������� ������������� ������ �� base64
function base64decode(str) {
    // ������� ��� base64-��������������
    var b64chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefg' +
            'hijklmnopqrstuvwxyz0123456789+/=';
    var b64decoded = '';
    var chr1, chr2, chr3;
    var enc1, enc2, enc3, enc4;

    str = str.replace(/[^a-z0-9\+\/\=]/gi, '');

    for (var i = 0; i < str.length; ) {
        enc1 = b64chars.indexOf(str.charAt(i++));
        enc2 = b64chars.indexOf(str.charAt(i++));
        enc3 = b64chars.indexOf(str.charAt(i++));
        enc4 = b64chars.indexOf(str.charAt(i++));

        chr1 = (enc1 << 2) | (enc2 >> 4);
        chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
        chr3 = ((enc3 & 3) << 6) | enc4;

        b64decoded = b64decoded + String.fromCharCode(chr1);

        if (enc3 < 64) {
            b64decoded += String.fromCharCode(chr2);
        }
        if (enc4 < 64) {
            b64decoded += String.fromCharCode(chr3);
        }
    }
    return b64decoded;
}
