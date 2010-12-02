/**
 * 
 * Effects 
 * 
 * @Author Erik A. Ortiz <erik@colosa.com, aortiz.erik@gmail.com>
 * @Date Feb 11th, 2009
 */

/**
 * fade effect
 * 
 * i.e. fade('myDivId')
 *   or fade('myDivId', true) -> this for fadeIn and fadeOut in the self time
 */

var TimeToFade = 1000.0;

function fade(eid, inOut){
	
  inOut = ( typeof(inOut) != 'undefined' )? true: false;
	  
  var element = document.getElementById(eid);
  if(element == null)
    return;
   
  if(element.FadeState == null)
  {
    if(element.style.opacity == null
        || element.style.opacity == ''
        || element.style.opacity == '1')
    {
      element.FadeState = 2;
    }
    else
    {
      element.FadeState = -2;
    }
  }
   
  if(element.FadeState == 1 || element.FadeState == -1)
  {
    element.FadeState = element.FadeState == 1 ? -1 : 1;
    element.FadeTimeLeft = TimeToFade - element.FadeTimeLeft;
  }
  else
  {
    element.FadeState = element.FadeState == 2 ? -1 : 1;
    element.FadeTimeLeft = TimeToFade;
    if(inOut){
    	setTimeout("animateFadeInOut(" + new Date().getTime() + ",'" + eid + "')", 33);
    }
    else 
    	setTimeout("animateFade(" + new Date().getTime() + ",'" + eid + "')", 33);
  }  
}


function animateFade(lastTick, eid)
{  
  var curTick = new Date().getTime();
  var elapsedTicks = curTick - lastTick;
 
  var element = document.getElementById(eid);
 
  if(element.FadeTimeLeft <= elapsedTicks)
  {
    element.style.opacity = element.FadeState == 1 ? '1' : '0';
    element.style.filter = 'alpha(opacity = ' + (element.FadeState == 1 ? '100' : '0') + ')';
    element.FadeState = element.FadeState == 1 ? 2 : -2;
    
    return;
  }
 
  element.FadeTimeLeft -= elapsedTicks;
  var newOpVal = element.FadeTimeLeft/TimeToFade;
  if(element.FadeState == 1)
    newOpVal = 1 - newOpVal;

  element.style.opacity = newOpVal;
  element.style.filter = 'alpha(opacity = ' + (newOpVal*100) + ')';
 
  setTimeout("animateFade(" + curTick + ",'" + eid + "')", 33);
}


function animateFadeInOut(lastTick, eid)
{  
  var curTick = new Date().getTime();
  var elapsedTicks = curTick - lastTick;
 
  var element = document.getElementById(eid);
 
  if(element.FadeTimeLeft <= elapsedTicks)
  {
    element.style.opacity = element.FadeState == 1 ? '1' : '0';
    element.style.filter = 'alpha(opacity = ' + (element.FadeState == 1 ? '100' : '0') + ')';
    element.FadeState = element.FadeState == 1 ? 2 : -2;
    
    fade(eid);
    return;
  }
 
  element.FadeTimeLeft -= elapsedTicks;
  var newOpVal = element.FadeTimeLeft/TimeToFade;
  if(element.FadeState == 1)
    newOpVal = 1 - newOpVal;

  element.style.opacity = newOpVal;
  element.style.filter = 'alpha(opacity = ' + (newOpVal*100) + ')';
 
  setTimeout("animateFadeInOut(" + curTick + ",'" + eid + "')", 33);
}

/**
 * Effect
 * 
 * To fade backgroud or text
 * 
 * @author Erik A. Ortiz <erik@colosa.com, aortiz.erik@gmail.com>
 * 
 */

var Effect = function(element){
    this.max=255;
    this.min=100;
    this.incrementor=1;
    
    this.element=(typeof(element) != 'undefined')? element: "text";  //"text" o "background"
    //----------------------------------------------

    this.r=this.max;
    this.g=this.max;
    this.b=this.max;

    this.target = '';
    this.iTarget = '';
    
    switch(this.element){
        case "text":
	        this.target="color";
	        this.iTarget="this.max-";
        break;
        case "background":
	        this.iTarget="";
	        this.target="backgroundColor";
        break;
    }
    
    this.setElement = function(e){
    	this.element = e;
    	
    	switch(this.element){
	        case "text":
		        this.target="color";
		        this.iTarget="this.max-";
	        break;
	        case "background":
		        this.iTarget="";
		        this.target="backgroundColor";
	        break;
	    }
    }
    
    this.setMin = function(e){
    	this.min = e;
    }
    
    this.setMax = function(e){
    	this.max = e;
    }
    
    this.fadeIn = function (obj){
        variation = -1;
        increment=variation*this.incrementor;
        eval('obj.style.'+this.target+'="RGB("+('+this.iTarget+'this.r)+","+('+this.iTarget+'this.g)+","+('+this.iTarget+'this.b)+")"');
        this.r+=increment;
        this.b+=increment;
        this.g+=increment;
        eso=obj;
        elincrement=variation;
        if(this.r>this.min && this.r<this.max){
            seguir=window.setTimeout("oEffect.fadeIn(eso,elincrement)",10);
        }
        else{
            this.r-=increment;
            this.g-=increment;
            this.b-=increment;
        }
    }
    
    this.fadeOut = function (obj){
        variation = 1;
        increment=variation*this.incrementor;
        eval('obj.style.'+this.target+'="RGB("+('+this.iTarget+'this.r)+","+('+this.iTarget+'this.g)+","+('+this.iTarget+'this.b)+")"');
        this.r+=increment;
        this.b+=increment;
        this.g+=increment;
        eso=obj;
        elincrement=variation;
        if(this.r>this.min && this.r<this.max){
            seguir=window.setTimeout("oEffect.fadeOut(eso,elincrement)",10);
        }
        else{
            this.r-=increment;
            this.g-=increment;
            this.b-=increment;
        }
    }

    /**
     * FadeIn and fade Out to the same time
     */
    this.fade = function (obj){
        
        variation = -1;
        
        increment=variation*this.incrementor;
        eval('obj.style.'+this.target+'="RGB("+('+this.iTarget+'this.r)+","+('+this.iTarget+'this.g)+","+('+this.iTarget+'this.b)+")"');
        this.r+=increment;
        this.b+=increment;
        this.g+=increment;
        eso=obj;
        elincrement=variation;
        if(this.r>this.min && this.r<this.max){
            seguir=window.setTimeout("oEffect.fade(eso,elincrement)",10);
        }
        else{
            this.r-=increment;
            this.g-=increment;
            this.b-=increment;
            
            if( typeof('cb') != 'undefined'){
                setTimeout("oEffect.fadeOut(document.getElementById('"+obj.id+"'))",0);
            } else {
                setTimeout("oEffect.fadeOut(document.getElementById('"+obj.id+"'))",0);
            }
        }
    }

}

//default object, always it should be declared.
var oEffect = new Effect();

