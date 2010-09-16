/* Web Resource
 * @author: David Callizaya <davidsantos@colosa.com>
 * Depend of common.js
 */
function WebResource(uri,parameters,method)
{
    var request;
    request = get_xmlhttp();
    var response;
    try
    {
    	if (!method ) method ="POST";
    	if (parameters != '') {
    		parameters += '&rand=' + Math.random();
    	}
    	else {
    		parameters = 'rand=' + Math.random();
    	}
    	data = parameters;
    	request.open( method, uri + ((method==='GET')?('?'+data): '') , false);
      if (method==='POST') request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      request.send(((method==='GET')? null : data));
      var type=request.getResponseHeader('Content-Type');
      var reType=/\w+\/\w+/;
      var maType=reType.exec(type);
      type=maType?maType[0]:'';//type.split(String.fromCharCode(9)).join("").trim();
  	}catch(ss)
  	{
  		alert("error"+ss.message);
  	}
      switch(type)
      {
        case "text/json":
          try
          {
            eval('response='+request.responseText+';');
            break;
          }
          catch (err)
          {
          }
            G.alert('<textarea style="width:100%;" rows="9">'+request.responseText+'</textarea>');
            return ;
          break;
        case "text/javascript":
          if (window.execScript)
              window.execScript( request.responseText ,'javascript');
          else
              window.setTimeout( request.responseText, 0 );
          break;
        case "text/html":
          response=$dce('div');
          response.innerHTML=request.responseText;
          break;
      }
    /*var r;
    for(r in response)
    {
      eval('this.'+r+'=response[r];');
    }*/
    return response;
}
function __wrCall(uri,func,parameters)
{
  var param=[];
  for(var a=0;a<parameters.length;a++) param.push(parameters[a]);
  return WebResource(uri,"function="+func+"&parameters="+encodeURIComponent(param.toJSONString()));
}
/* Required functions */
if (!Object.prototype.toJSONString) {
    Array.prototype.toJSONString = function () {
        var a = ['['],
            b,
            i,
            l = this.length,
            v;
        function p(s) {
            if (b) {
                a.push(',');
            }
            a.push(s);
            b = true;
        }
        for (i = 0; i < l; i += 1) {
            v = this[i];
            switch (typeof v) {
            case 'undefined':
            case 'function':
            case 'unknown':
                break;
            case 'object':
                if (v) {
                    if (typeof v.toJSONString === 'function') {
                        p(v.toJSONString());
                    }
                } else {
                    p("null");
                }
                break;
            default:
                p(v.toJSONString());
            }
        }
        a.push(']');
        return a.join('');
    };
    Boolean.prototype.toJSONString = function () {
        return String(this);
    };
    Date.prototype.toJSONString = function () {
        function f(n) {
            return n < 10 ? '0' + n : n;
        }
        return '"' + this.getFullYear() + '-' +
                f(this.getMonth() + 1) + '-' +
                f(this.getDate()) + 'T' +
                f(this.getHours()) + ':' +
                f(this.getMinutes()) + ':' +
                f(this.getSeconds()) + '"';
    };
    Number.prototype.toJSONString = function () {
        return isFinite(this) ? String(this) : "null";
    };
    Object.prototype.toJSONString = function () {
        var a = ['{'],
            b,
            k,
            v;
        function p(s) {
            if (b) {
                a.push(',');
            }
            a.push(k.toJSONString(), ':', s);
            b = true;
        }
        for (k in this) {
            if (this.hasOwnProperty(k)) {
                v = this[k];
                switch (typeof v) {
                case 'undefined':
                case 'function':
                case 'unknown':
                    break;
                case 'object':
                    if (v) {
                        if (typeof v.toJSONString === 'function') {
                            p(v.toJSONString());
                        }
                    } else {
                        p("null");
                    }
                    break;
                default:
                    p(v.toJSONString());
                }
            }
        }
        a.push('}');
        return a.join('');
    };

    (function (s) {
        var m = {
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"' : '\\"',
            '\\': '\\\\'
        };
        s.parseJSON = function (filter) {
            try {
                if (/^("(\\.|[^"\\\n\r])*?"|[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t])+?$/.
                        test(this)) {
                    var j = eval('(' + this + ')');
                    if (typeof filter === 'function') {

                        function walk(k, v) {
                            if (v && typeof v === 'object') {
                                for (var i in v) {
                                    if (v.hasOwnProperty(i)) {
                                        v[i] = walk(i, v[i]);
                                    }
                                }
                            }
                            return filter(k, v);
                        }

                        j = walk('', j);
                    }
                    return j;
                }
            } catch (e) {
            }
            return this;
        };

        s.toJSONString = function () {
            if (/["\\\x00-\x1f]/.test(this)) {
                return '"' + this.replace(/([\x00-\x1f\\"])/g, function(a, b) {
                    var c = m[b];
                    if (c) {
                        return c;
                    }
                    c = b.charCodeAt();
                    return '\\u00' +
                        Math.floor(c / 16).toString(16) +
                        (c % 16).toString(16);
                }) + '"';
            }
            return '"' + this + '"';
        };
    })(String.prototype);
}
if (!String.prototype.trim)
{
  String.prototype.trim=function()
  {
    var cadena=this;
  	for(i=0; i<cadena.length; )
  	{
  		if(cadena.charAt(i)==" ")
  			cadena=cadena.substr(i+1, cadena.length);
  		else
  			break;
  	}

  	for(i=cadena.length-1; i>=0; i=cadena.length-1)
  	{
  		if(cadena.charAt(i)==" ")
  			cadena=cadena.substr(0,i);
  		else
  			break;
  	}
  	return cadena.toString();
  }
}