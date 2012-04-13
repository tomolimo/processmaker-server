leimnud.Package.Public({
  info  :{
    Class   :"maborak",
    File    :"module.validator.js",
    Name    :"validator",
    Type    :"module",
    Version :"1.4"
  },
  content :function(param)
  {
    this.valid=param.valid || false;
    this.invalid=param.invalid || false;
    this.validArray=(this.valid.isArray)?this.valid:[];
    this.invalidArray=(this.invalid.isArray)?this.invalid:[];
    this.add=param.add || false;
    this.generateKeys=function()
    {
      this.keys=[];
      this.keys['es']=[];
      this.keys["es"]["Alpha"]=["abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ","áéíóúñÁÉÍÓÚÑüïÜÏ"," "];
      this.keys["es"]["Int"]=[[47,57]].concat("+-");
      this.keys["es"]["Real"]=[[48,57]].concat(".,-+");
      this.keys["es"]["Any"]=this.keys["es"]["Alpha"].concat("!#$%&/()=???+*{}[]-_.:,;'|\"\\@",[[48,57]]);
      this.keys["es"]["AlphaNum"]=this.keys['es']["Int"].concat(this.keys["es"]["Alpha"][0],this.keys["es"]["Alpha"][1]," ");
      this.keys["es"]["Field"]=["abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_"];
      this.keys["es"]["Email"]=[this.keys["es"]["Alpha"][0]].concat("._-@1234567890");
      this.keys["es"]["Login"]=[this.keys["es"]["Alpha"][0]].concat("._-@1234567890");
      this.keys["es"]["Path"]=this.keys['es']["Field"].concat("/"," ");
      this.keys["es"]["NodeName"]=this.keys['es']["Field"].concat("-");

      this.keys["en"]=[];
      this.keys["en"]["Alpha"]=[this.keys["es"]["Alpha"][0]];
      this.keys["en"]["Int"]=[[48,57]].concat("+-");
      this.keys["en"]["Real"]=[[48,57]].concat(".,-+");
      this.keys["en"]["Any"]=this.keys["en"]["Alpha"].concat("!#$%&/()=???+*{}[]-_.:,;'|\"\\@",[[48,57]]);
      this.keys["en"]["AlphaNum"]=this.keys['en']["Int"].concat(this.keys["en"]["Alpha"][0]," ");
      //this.keys["en"]["AlfaNum"]=this.keys['en']["Int"];
      this.keys["en"]["Field"]=this.keys["es"]["Field"];
      this.keys["en"]["Email"]=[this.keys["es"]["Alpha"][0]].concat("._-@1234567890");
      this.keys["en"]["Login"]=[this.keys["es"]["Alpha"][0]].concat("._-@1234567890");
      this.keys["en"]["Path"]=this.keys['es']["Field"].concat("/"," ");
      this.keys["en"]["Tag"]=this.keys['es']["Field"].concat(","," ");
      this.keys["en"]["NodeName"]=this.keys['es']["Field"].concat("-");

      
      return (this.keys[this.lang][this.type])?this.keys[this.lang][this.type]:this.keys[this.lang]["Alpha"];
    };
    this.result=function()
    {		    
      if(this.validArray[0].toLowerCase()=="any")
      {
        return true;
      }
      if(this.isNumber(param.key))
      {
        this.key=param.key;
      }
      else if(typeof param.key=="object")
      {
        this.key=(param.key.which) ?param.key.which : param.key.keyCode;
      }
      else
      {
        this.key=false;
      }
      this.lang= param.lang || "en";

      var valid=true;
      for(var i=0;i<this.validArray.length;i++)
      {
        this.type=this.validArray[i];
        //alert(this.generateKeys().toStr(true))
        valid=this.engine(this.generateKeys());
        if(valid===true){return true;}
      }
      if(this.validArray.length===0)
      {
        valid=this.engine([])
      }
      return valid;
    };
    this.isNumber=function(a)
    {
      return (a>=0)?true:false;
    };
    this.compareChar=function(_string,car) {
      var i = 0,a=false;
      //alert(_string+":"+car)
      while ( i <_string.length && !a ) {
        //alert(_string[i]+":"+(_string.charCodeAt(i))+":"+car);
        a= (_string.charCodeAt(i) == car);
        i ++;
      }
      //alert(a)
      return a;
    };
    this.isAlfaUS=function()
    {
      patron=[];
      patron[0]=validator.keys.alfa[0];
      patron[1]=validator.keys.alfa[2];
      return patron;
    };
    this.isAlfa=function()
    {
      patron=validator.keys.alfa;
      return patron;
    };
    this.checkAdd=function(p)
    {
      if(this.add)
      {
        return p.concat(this.add)
      }
      else
      {
        return p;
      }
    };
    this.engine=function(p)
    {
      //alert(this.lang+":"+leimnud.tools.object.toString(p))
      this.patron=this.checkAdd(p);
      //alert(p);
      //alert(leimnud.tools.object.toString(this.patron))
      var valid=false;
      //$("d").innerHTML=" ";
      //alert(this.patron.toStr(true))
      for(var i=0;i<this.patron.length;i++)
      {
        var b=this.patron[i];
        //alert(this.patron[i])
        var type= typeof this.patron[i];
        if(type=="string")
        {
          //	alert(556)
          valid=this.compareChar(this.patron[i],this.key);
        }
        else if (type=="object")
        {
        //alert(this.patron[i]);
         // alert(this.key+":"+this.patron[i][0]+":"+this.patron[i][1])
          valid=(this.key>=this.patron[i][0] && this.key<=this.patron[i][1])?true:false;
        }
        else if(type=="number")
        {
          if(this.keys[this.lang]['validatorByLetter'])
          {
            //valid=(String.fromCharCode(this.key)==String.fromCharCode(this.patron[i]))?true:false;
            valid=(this.key==this.patron[i])?true:false;
            //$("d").innerHTML+="[ "+String.fromCharCode(this.key)+" : "+String.fromCharCode(this.patron[i])+" [mykey:"+String.fromCharCode(this.patron[i]).charCodeAt(0)+"] ] [2007]["+this.key+"]["+this.patron[i]+"]<br> ";
          }
          else
          {
            valid=(this.key==this.patron[i])?true:false;
          }
        }
        if(valid===true){return true;}
      }
      //alert(valid)
      return valid;
    };
  }
});
