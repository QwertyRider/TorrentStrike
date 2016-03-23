function klappe(id)
{
        var klappText = document.getElementById('k' + id);
        var klappBild = document.getElementById('pic' + id);

        if (klappText.style.display == 'none') {
                  klappText.style.display = 'block';
                  // klappBild.src = 'images/blank.gif';
        }
        else {
                  klappText.style.display = 'none';
                  // klappBild.src = 'images/blank.gif';
        }
}

function klappe_news(id)
{
        var klappText = document.getElementById('k' + id);
        var klappBild = document.getElementById('pic' + id);

        if (klappText.style.display == 'none') {
                  klappText.style.display = 'block';
                  klappBild.src = 'pic/minus.gif';
        }
        else {
                  klappText.style.display = 'none';
                  klappBild.src = 'pic/plus.gif';
        }
}

   function setCookie(name, value, expires, path, domain, secure)
{
 var curCookie = name + "=" + escape(value) +
     ((expires) ? "; expires=" + expires.toGMTString() : "") +
     ((path) ? "; path=" + path : "") +
     ((domain) ? "; domain=" + domain : "") +
     ((secure) ? "; secure" : "");
 document.cookie = curCookie;
}

// Javascript cookie code for timezone offset
var curDate = new Date();
var userZone = curDate.getTimezoneOffset();
curDate.setTime(curDate.getTime() + 365 * 24 * 60 * 60 * 1000);
setCookie("userZone", userZone, curDate, '/', null, null);
