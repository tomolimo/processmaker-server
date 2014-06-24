leimnud.Package.Public({
	info	:{
		Class	:"maborak",
		File	:"module.dom.js",
		Name	:"dom",
		Type	:"module",
		Version	:"0.1"
	},
	content	:{
		button:function(label,go,style,prop)
		{
			this.make=function(label,go,style,prop)
			{				
				this.button = (label && label.tagName)?$(label):(new this.parent.module.dom.create("input",{
					className:"module_app_button___gray",
					type	:"button",
					value	:label || "Button"
				}.concatMaborak(prop || {}),style || {}));
/*				this.button = $dce("input");
				this.button.className="module_app_button___gray";
				this.button.type="button";
				this.button.value=label || "Button";*/
				this.button.disable=function()
				{
					this.button.disabled=true;
					this.button.className="module_app_buttonjs___gray module_app_buttonDisabled___gray";
					return this.button;
				}.extend(this);
				this.button.enable=function()
				{
					this.button.disabled=false;
					this.button.className="module_app_buttonjs___gray";
					return this.button;
				}.extend(this);
				this.button.onmouseover	=this.mouseover;
				this.button.onmouseout	=this.mouseout;
				//this.parent.event.add(this.button,"mouseover",this.mouseover);
				//this.parent.event.add(this.button,"mouseout",this.mouseout);
				this.parent.dom.setStyle(this.button,style || {});
				if(typeof go==="function"){
					this.button.onmouseup=go.args(this.button);
				}
				return this.button;
			};
			this.mouseover=function()
			{
				if(this.button.disabled==true){return false;}
				this.button.className="module_app_buttonjs___gray module_app_buttonHover___gray";
				return false;
			};
			this.mouseout=function()
			{
				if(this.button.disabled==true){return false;}
				this.button.className="module_app_buttonjs___gray";
				return false;
			};
			this.expand();
			return this.make(label,go,style,prop);
		},
		input:function(options)
		{
			this.make=function(options)
			{
				this.input = (options && options.tagName)?$(options):(new this.parent.module.dom.create("input",{
					className:"module_app_input___gray",
					type	:"text",
					value	:options.label || "",
					maxLength :options.maxlength || "30"
				}.concatMaborak(options.properties || {}),(options.style || {})));

				this.input.disable=function()
				{
					this.input.disabled=true;
					this.input.className=this.input.className+" module_app_inputDisabled___gray";
					return this.input;
				}.extend(this);
				this.input.enable=function()
				{
					this.input.disabled=false;
					this.input.className=this.input.className.split(" ")[0];
					return this.input;
				}.extend(this);
				this.input.passed=function()
				{       if ('\v'=='v')  //verify if is internet explorer
                                          this.input.className="module_app_inputPassed_ie___gray "+((this.input.className.split(' ')[1]) || '');
                                        else
					  this.input.className="module_app_inputPassed___gray "+((this.input.className.split(' ')[1]) || '');
					return this.input;
				}.extend(this);
				this.input.normal=function()
				{
					this.input.className=this.input.className+" "+((this.input.className.split(' ')[1]) || '');
					return this.input;
				}.extend(this);
				this.input.failed=function()
				{       if ('\v'=='v')  //verify if is internet explorer
                                          this.input.className="module_app_inputFailed_ie___gray "+((this.input.className.split(' ')[1]) || '');
					else
                                          this.input.className="module_app_inputFailed___gray "+((this.input.className.split(' ')[1]) || '');
					return this.input;
				}.extend(this);
//				this.parent.event.add(this.input,"mouseover",this.mouseover);
//				this.parent.event.add(this.input,"mouseout",this.mouseout);
				//this.parent.dom.setStyle(this.input,style || {});
				return this.input;
			};
			this.mouseover=function()
			{
				this.input.className="module_app_input___gray module_app_inputHover___gray";
				return false;
			};
			this.mouseout=function()
			{
				this.input.className="module_app_input___gray";
				return false;
			};
			this.expand();
			return this.make(options || {});
		},
		select:function(options)
		{
            this.options = {
                data:[],
                selected:0,
                properties:{},
                style:{}
            }.concatMaborak(options || {});
			this.make=function()
			{
                this.select = new this.parent.module.dom.create("select",this.options.properties,this.options.style);
				this.select.className="module_app_select___gray";
                this.makeData();
                this.select.selected=function()
                {
                    return this.select.options[this.select.selectedIndex];
                }.extend(this);
                this.select.clear=function()
                {
                    var a = this.select.options;
                    var b = a.length;
                    for(var i=0;i<b;i++)
                    {
                        a[0].parentNode.removeChild(a[0]);
                    }
                }.extend(this);
                this.select.addOption=function(data)
                {
                    data = {
                        value   :null,
                        text    :null,
                        selected:false,
                        key     :false
                    }.concatMaborak(data || {});
                    var o = new Option(data.text,data.value,data.selected);
                    if(data.key===false)
                    {
                        this.select.append(o);
                    }
                    else
                    {
                        this.select.insertBefore(o,this.select.options[data.key]);
                        this.select.selectedIndex=data.key;
                    }
                }.extend(this);
		return this.select;
		};
            this.makeData=function(){
                var d = this.options.data;
                var j = d.length;

                for(var i=0;i<j;i++)
                {
                    this.select[i]=new Option(d[i].text,d[i].value,((this.options.selected===i)?true:false));
                }
            };
			this.expand();
			return this.make();
		},
		create:function(dom,properties,style)
		{
			this.dom = $dce(dom);
			this.parent.dom.setProperties(this.dom,properties || {});
			this.parent.dom.setStyle(this.dom,style || {});
			return new this.parent.module.dom.methods(this.dom);
		},
		methods:function(dom)
		{
			if(!dom){return false;}
			if(dom.domed==true){return dom;}
			this.dom = dom;
			this.dom.dom = this.dom;
			this.dom.domed = true;
			this.dom.replace = function(dom)
			{
				if(!dom)
				{
					return this.dom;
				}
				else
				{
					this.dom.parentNode.replaceChild(dom,this.dom);
					return dom;
				}
			}.extend(this);
			this.dom.before = function(dom)
			{
				if(!dom)
				{
					return this.dom;
				}
				else
				{
					this.dom.parentNode.insertBefore(dom,this.dom);
					return dom;
				}
			}.extend(this);
			this.dom.append = function()
			{
				for(var i=0;i<arguments.length;i++)
				{
					if(arguments[i])
					{
						this.dom.appendChild(arguments[i]);
					}
				}
				return this.dom;
			}.extend(this);
			this.dom.remove = function()
			{
				this.dom.parentNode.removeChild(this.dom);
			}.extend(this);
			this.dom.opacity = function(o)
			{
				this.parent.dom.opacity(this.dom,o);
				return this.dom;
			}.extend(this);
			this.dom.setStyle = function(style)
			{
				this.parent.dom.setStyle(this.dom,style || {});
				return this.dom;
			}.extend(this);

			return this.dom;
		},
		radioByValue:function(param)
		{
			var radio_name=$n(param.name) || false;
			var radio_value=param.value || false;
			for(var i=0;i<radio_name.length;i++)
			{
				if(radio_name[i].value==radio_value)
				{
					return radio_name[i];
				}
			}
			return false;
		}
	}
});
