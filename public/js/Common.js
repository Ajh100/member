var Cmd = {};
Cmd = {
    GetCookie: function (name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) {
                return decodeURIComponent(c.substring(nameEQ.length, c.length))
            }
        } return null
    },
    SetCookie: function (name, value, expires, path, domain) {
        if (!expires) expires = -1;
        if (!path) path = "/";
        var d = "" + name + "=" + value;
        var e;
        if (expires < 0) {
            e = "";
        }
        else if (expires == 0) {
            var f = new Date(1970, 1, 1);
            e = ";expires=" + f.toUTCString();
        }
        else {
            var now = new Date();
            var f = new Date(now.getTime() + expires * 1000);
            e = ";expires=" + f.toUTCString();
        }
        var dm;
        if (!domain) {
            dm = "";
        }
        else {
            dm = ";domain=" + domain;
        }
        document.cookie = name + "=" + value + ";path=" + path + e + dm;
    },
    /* 字符串截取方法 */
    GetCharactersLen: function (charStr, cutCount) {
        if (charStr == null || charStr == '') return '';
        var totalCount = 0;
        var newStr = '';
        for (var i = 0; i < charStr.length; i++) {
            var c = charStr.charCodeAt(i);
            if (c < 255 && c > 0) {
                totalCount++;
            } else {
                totalCount += 2;
            }
            if (totalCount >= cutCount) {
                newStr += charStr.charAt(i);
                break;
            }
            else {
                newStr += charStr.charAt(i);
            }
        }
        return newStr;
    },
    GetUrlParam: function (name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null)
            return unescape(r[2]);
        return null;
    },
    /*获取字符串的字符长度*/
    GetRealLength: function (str) {
        return str.replace(/[^\x00-\xff]/g, "aa").length;
    },

    /* 跨域请求 */
    JSLoad: function (args) {
        s = document.createElement("script");
        s.setAttribute("type", "text/javascript");
        s.setAttribute("src", args.url);
        s.onload = s.onreadystatechange = function () {
            if (!s.readyState || s.readyState == "loaded" || s.readyState == "complete") {
                if (typeof args.callback == "function") args.callback(this, args);
                s.onload = s.onreadystatechange = null;
                try {
                    s.parentNode && s.parentNode.removeChild(s);
                } catch (e) { }
            }
        };
        document.getElementsByTagName("head")[0].appendChild(s);
    }



};
