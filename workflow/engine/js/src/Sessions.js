PM.Sessions = (function () {
    var Sessions = function () {
        if (this.getCookie('singleSignOn') === '1') {
            this.register();
            this.eraseCookie('singleSignOn');
        }
        if (window.location.pathname.indexOf("login") === -1 &&
            window.location.pathname.indexOf("sysLogin") === -1 &&
            window.location.pathname.indexOf("authentication") === -1 &&
            window.location.pathname.indexOf("/sys/") === -1 &&
            this.getCookie('PM-TabPrimary') !== '101010010') {
           this.isClose = (this.getLabel('mainWindowClose') === "true");
           try {
              if (this.isClose && parent.parent.parent.window.name === "") {
                 this.register();
              }
           } catch (e) { }
            this.checkTab();
        }
    };

    Sessions.prototype.register = function () {
        this.setLabel('mainWindowClose', false);
        window.name = this.getCookie('PM-TabPrimary');
    };

    Sessions.prototype.checkTab = function () {
        var ieVersion,
            msg,
            win;
        if (window.name === this.getCookie('PM-TabPrimary')) {
            this.setLabel('mainWindowClose', false);
       }
       try {
          if (parent.parent.parent.window.name !== this.getCookie('PM-TabPrimary') &&
             parent.parent.parent.window.name.indexOf(this.getCookie('PM-TabPrimary')) === -1) {
             ieVersion = this.detectBrowser();
             msg = this.getLabel('ID_BLOCKER_MSG');
             win = window.open('', '_self', '');
             if (ieVersion && ieVersion <= 11) {
                win.document.execCommand('Stop');
                win.open("/errors/block.php", "_self");
             } else if (ieVersion && ieVersion <= 13) {
                win.document.execCommand('Stop');
                win.open("/errors/block.php", "_self");
             } else {
                win.stop();
                win.open("/errors/block.php", "_self");
             }
          }
       } catch (e) { }
    };

    Sessions.prototype.detectBrowser = function() {
        var ua = window.navigator.userAgent,
            msie = ua.indexOf('MSIE '),
            trident = ua.indexOf('Trident/'),
            edge = ua.indexOf('Edge/');

        // Test values; Uncomment to check result …

        // IE 10
        // ua = 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)';

        // IE 11
        // ua = 'Mozilla/5.0 (Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko';

        // IE 12 / Spartan
        // ua = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36 Edge/12.0';

        // Edge (IE 12+)
        // ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2486.0 Safari/537.36 Edge/13.10586';
        if (msie > 0) {
            // IE 10 or older => return version number
            return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
        }
        if (trident > 0) {
            // IE 11 => return version number
            var rv = ua.indexOf('rv:');
            return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
        }
        if (edge > 0) {
            // Edge (IE 12+) => return version number
            return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
        }
        // other browser
        return false;
    };

    Sessions.prototype.getCookie = function (cname) {
        var name = cname + "=",
            c,
            ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1);
            if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
        }
        return "";
    };

    Sessions.prototype.createCookie = function(name, value, days) {
        var date,
            expires;
        if (days) {
            date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            expires = "; expires="+date.toGMTString();
        } else {
            expires = "";
        }
        document.cookie = name+"="+value+expires+"; path=/";
    };

    Sessions.prototype.eraseCookie = function(name) {
        this.createCookie(name,"",-1);
    };

    Sessions.prototype.setLabel = function(nameLabel, labelValue) {
        localStorage.setItem(nameLabel, labelValue);
    };

    Sessions.prototype.getLabel = function(nameLabel) {
        return localStorage.getItem(nameLabel);
    };

    Sessions.prototype.addEventHandler = function (elem, eventType, handler) {
        if (elem.addEventListener)
            elem.addEventListener(eventType, handler, false);
        else if (elem.attachEvent)
            elem.attachEvent('on' + eventType, handler);
    };

    Sessions.prototype.isClose = false;

    return new Sessions();
})();

PM.Sessions.addEventHandler(window, "unload",function () {
    if (window.name === PM.Sessions.getCookie('PM-TabPrimary')){
        PM.Sessions.setLabel('mainWindowClose', true);
    }
});