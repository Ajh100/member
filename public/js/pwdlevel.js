//密码级别设置
function TipPasswordLevel(pwdleval) {
    var pwdSafeLevel = document.getElementById('div_PwdSafeLevel');
    var listSpan = pwdSafeLevel.getElementsByTagName('span');
    switch (pwdleval) {
        case 0:
            listSpan[0].className = 'pwds';
            listSpan[1].className = 'pwds';
            listSpan[2].className = 'pwds';
            break;
        case 1:
            listSpan[0].className = 'pwdsb';
            listSpan[1].className = 'pwds';
            listSpan[2].className = 'pwds';
            break;
        case 2:
            listSpan[0].className = 'pwdsb';
            listSpan[1].className = 'pwdsb';
            listSpan[2].className = 'pwds';
            break;
        case 3:
            listSpan[0].className = 'pwdsb';
            listSpan[1].className = 'pwdsb';
            listSpan[2].className = 'pwdsb';
            break;
    }
};

//密码级别逻辑
var ischr = false;
var isnum = false;
var isoth = false;
function v_Password() {
    ischr = false;
    isnum = false;
    isoth = false;
    var pwd = document.getElementById('password').value;
    if (pwd.length > 5) {
        for (var i = 0; i < pwd.length; i++) {
            if (pwd.charCodeAt(i) >= 48 && pwd.charCodeAt(i) <= 57) {
                isnum = true;
            }
            else if ((pwd.charCodeAt(i) >= 65 && pwd.charCodeAt(i) <= 90) || (pwd.charCodeAt(i) >= 97 && pwd.charCodeAt(i) <= 122)) {
                ischr = true;
            }
            else {
                isoth = true;
            }
        }
        if (ischr && isnum && isoth && pwd.length > 9) {
            TipPasswordLevel(3);
        }
        else if (ischr && isnum && isoth && pwd.length < 10) {
            TipPasswordLevel(2);
        }
        else if (((ischr && isnum) || (ischr && isoth) || (isoth && isnum)) && pwd.length > 12) {
            TipPasswordLevel(3);
        }
        else if (((ischr && isnum) || (ischr && isoth) || (isoth && isnum)) && pwd.length > 7 && pwd.length < 13) {
            TipPasswordLevel(2);
        }
        else {
            TipPasswordLevel(1);
        }
    }
    else if (pwd.length == 0) {
        TipPasswordLevel(0);
    }
    else {
        TipPasswordLevel(1);
    }

    if (pwd.length > 5) {
        // newPwdValidate();
    }
    else {

    }
};
