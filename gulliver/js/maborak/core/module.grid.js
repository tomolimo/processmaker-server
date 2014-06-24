leimnud.Package.Public({
	info	:{
		Class	:"maborak",
		File	:"module.grid.js",
		Name	:"grid",
		Type	:"module",
		Version:"0.1"
	},
	content	:function(){
		this.elements={};
		this.make=function(options)
		{
			this.options = {
				theme:"gray"
			}.concatMaborak(options || {});
			this.options.paginator={
				limit:this.options.data.rows.length,
				page	:1
			}.concatMaborak(this.options.paginator || {});
			/* Search Begin */
			if(this.options.search===true)
			{
				this.renderSearch();
			}
			/* Search End */
			this.options.target.appendChild(this.render());
			if(this.options.paginator && !this.options.noPaginator)
			{
				this.paginator();
			}
			return this;
		};
		this.render=function()
		{
			var table,row,cell,dataColumn=this.options.data.column,dataRow;
			var rows=dataColumn.length;
			var width	= 100/dataColumn.length+"%";
			/* Grid table BEGIN */
			if(this.options.title)
			{
				this.elements.titleBar = $dce("div");
				this.elements.titleBar.innerHTML=this.options.title || "";
				this.elements.titleBar.className="app_grid_headerBar___"+this.options.theme;
				this.parent.dom.setStyle(this.elements.titleBar,this.options.styleHeaderBar || {});
				this.options.target.appendChild(this.elements.titleBar);
			}
			table = this.elements.table = $dce("table");
			this.elements.table.className="app_grid_table___"+this.options.theme;
			this.parent.dom.setStyle(table,{
				/*font:"normal 11px arial,tahoma,helvetica,sans-serif",
				width	:"100%",
				borderCollapse:"collapse",
				margin:0*/
			});

			row = table.insertRow(-1);
			/* right Click BEGIN */

			var menuObserver=this.parent.factory(this.parent.pattern.observer,true);
			this.parent.event.add(this.options.target,"click",menuObserver.update);
			var menu = new this.parent.module.app.menuRight();
			menu.make({
				target:row,
				menu:[
				{text:"Option 1",launch:function(){alert(1)}},
				{text:"Option 2",launch:function(){alert(2)}},
				{text:"Option 3",launch:function(){alert(3)}}
				]
			});
			menuObserver.register(menu.remove,menu);
			/* right Click END */
			for(var i=0;i<rows;i++)
			{
				cell = row.insertCell(i);
				cell.onmouseover=function(evt,cell)
				{
					var event = window.event || evt;
					var cell = (window.event)?evt:cell;
					if(event.ctrlKey)
					{
						cell.style.cursor="move";
					}
					else
					{
						cell.style.cursor="default";
					}
				}.extend(this,cell);
				dataRow = dataColumn[i];
				cell.innerHTML=dataRow.title;
				cell.className="app_grid_title___"+this.options.theme;
				this.parent.dom.setStyle(cell,{
					width	:dataColumn[i].width || width
				}.concatMaborak(dataColumn[i].style || {}));
				dataRow.searchable = (dataRow.searchable===false)?false:true;
				if(dataRow.type==="counter" || dataRow.type==="drag")
				{
					this.createColumnData(i,dataRow.data || null);
				}
				else
				{
					cell.onmousedown=this.dragCell.args(i);
				}
				if(dataColumn[i].onchange)
				{
					dataColumn[i].onchange=(dataColumn[i].onchange.isString)?eval(dataColumn[i].onchange):dataColumn[i].onchange;
				}
			}
			this.fillAllData();
			this.renderData();
			return table;
		};
		this.createColumnData=function(key,data)
		{
			var rows = this.options.data.rows;
			for(var i=0;i<rows.length;i++)
			{
				rows[i].data.insert(key,data);
			}
		};
		this.renderData=function()
		{
			//alert(this.dataToRender.length)
			var table	= this.elements.table,row,cell,dataColumn=this.options.data.column,dataRow;
			var rows	= dataColumn.length;
			var width	= 100/dataColumn.length+"%";
			/* parse Rows BEGIN  */
			var limit=this.dataToRender.length;
			var total=this.dataToRender.length;
			var lpp;
			var page = this.options.paginator.page;
			var forp = (this.options.paginator.limit>limit)?limit:this.options.paginator.limit;
			lpp  = (((page)==this.options.paginator.pages)?(total-((page-1)*forp)):forp);
			var inicio = this.options.paginator.limit*(this.options.paginator.page-1);
			inicio = (inicio<0)?0:inicio;
			var fin	 = inicio+lpp;
			//alert(inicio+":"+fin);
			for(var i=inicio;i<fin;i++)
			{
				row = table.insertRow(-1);
				var domCell = [];
				this.options.data.rows[this.dataToRender[i]].info={
					rowIndex:i+1
				}.concatMaborak(this.options.data.rows[this.dataToRender[i]].info || {});
				dataCell = this.options.data.rows[this.dataToRender[i]].data;
				for(var j=0;j<rows;j++)
				{
					cell = row.insertCell(j);
					domCell.push(cell);
					dataRow = dataCell[j] || 0;
					//alert(dataColumn[j])
					cell.className="app_grid_data___"+this.options.theme;
					if(dataColumn[j].paint)
					{
						cell.className="app_grid_data___"+this.options.theme+" app_grid_"+dataColumn[j].paint+"___"+this.options.theme;
					}
					this.parent.dom.setStyle(cell,{
						width	: dataColumn[j].width || width
					}.concatMaborak(dataColumn[j].styleValues || dataRow.style || {}));
					if(dataColumn[j].type==="counter" || dataColumn[j].type==="drag")
					{
						var value = dataRow.value || i+1;
						cell.innerHTML=value;
						if(dataColumn[j].type==="drag")
						{
							cell.onmousedown=this.dragRow.args(this.dataToRender[i]);
						}
					}
					else if(dataColumn[j].type==="text" || dataColumn[j].type==="textarea")
					{
						cell.innerHTML=dataRow.value;
						if(dataColumn[j].edit===true)
						{
							this.editText(this.dataToRender[i],j,cell);
						}
					}
					else if(dataColumn[j].type=="textDropdown")
					{
						cell.innerHTML=dataColumn[j].data[dataRow.value][1];
						if(dataColumn[j].edit===true)
						{
							this.editTextDropdown(this.dataToRender[i],j,cell);
						}
					}
					else if(dataColumn[j].type=="checkbox")
					{
						var cb	= $dce("input");
						cb.type	= "checkbox";
						cell.appendChild(cb);
						cb.checked = (dataRow.value);
						cb.disabled=(dataColumn[j].edit===true)?false:true;
						this.parent.dom.setStyle(cb,{
							padding	:0,
							margin	:0
						});
						cb.onchange=function(evt,data)
						{
							data = (window.event)?evt:data;
							dataRow = data.dataRow;
							cb		= data.cb;
							dataRow.value=cb.checked;
							if(dataColumn[data.indexColumn].onchange)
							{
								dataColumn[data.indexColumn].onchange({index:this.options.data.rows[data.indexRow],dom:cb});
							}
						}.extend(this,{cb:cb,dataRow:dataRow,indexColumn:j,indexRow:this.dataToRender[i]});
					}
					else if(dataColumn[j].type=="dropdown")
					{
						//cell.innerHTML=dataColumn[j].data[dataRow.value][1];
						this.parent.dom.setStyle(cell,{
							padding		:2
						});
						var select		= $dce("select");
						dataCellHeader = dataColumn[j];
						//alert(dataCell.data)
						for(var k=0;k<dataCellHeader.data.length;k++)
						{
							var selected = (dataRow.value===dataCellHeader.data[k][0]);
							//select.options[i]=new Option(dataCell.data[i][1],dataCell.data[i][0],true);
							var option		= $dce("option");
							select.appendChild(option);
							option.value	=dataCellHeader.data[k][0];
							option.selected 	= selected;
							option.text		=dataCellHeader.data[k][1];
						}
						select.onchange=function(evt,data)
						{
							data = (window.event)?evt:data;
							dataRow = data.dataRow;
							select	= data.select;
							dataRow.value=parseInt(select.options[select.selectedIndex].value,10);
						}.extend(this,{select:select,dataRow:dataRow});
						cell.appendChild(select);
						select.disabled=(dataColumn[j].edit===true)?false:true;
					}
				}
				//this.elements.cells.push(domCell);
				//this.elements.rows.push(row);
			}
			/* parse Rows END  */
		};
		this.paginator=function()
		{
			/*this.paginatorDOM = $dce("div");
			var headerP	= $dce("div");
			var theme		= this.options.theme;
			headerP.className="app_grid_paginatorHeader___"+theme;
			for(var i=0;i<this.options.paginator.pages;i++)
			{
				var li = $dce("a");
				li.innerHTML	= i+1;
				li.href		= "#";
				headerP.appendChild(li);
				if((i+1)===this.options.paginator.page)
				{
					li.className	= "app_grid_paginatorLinkSelected___"+theme;
				}
				else
				{
					li.className	= "app_grid_paginatorLink___"+theme;
					li.onmouseup = function(evt,page)
					{
						page = (this.parent.browser.isIE)?evt:page;
						//alert(page+1)
						this.options.paginator.page=page+1;
						this.clear();
						this.paginatorDOM.innerHTML="";
						this.paginator();
						this.renderData();
						}.extend(this,i)
				}
			}
			this.paginatorDOM.appendChild(headerP);
			this.parent.dom.setStyle(headerP,{
				textAlign	:"center",
				padding	:5
			});
			this.options.target.appendChild(this.paginatorDOM);*/

			this.paginatorDOM = $dce("div");
			var headerP	= $dce("div");
			var theme		= this.options.theme;
			headerP.className="app_grid_paginatorHeader___"+theme;
			this.paginatorDOM.appendChild(headerP);
			this.parent.dom.setStyle(headerP,{
				textAlign	:"center",
				padding	:5
			});
			this.options.target.appendChild(this.paginatorDOM);
			var p = headerP;

			(p.appendChild(this.pDf = $dce("a"))).href="#";
			this.pDf.className="app_grid_pDf___"+theme;
			this.pDf.onmouseup=function()
			{
				return this.paginatorTo(1);
			}.extend(this);

			(p.appendChild(this.pDp = $dce("a"))).href="#";
			this.pDp.className="app_grid_pDp___"+theme;
			this.pDp.onmouseup=function()
			{
				return this.paginatorTo(this.options.paginator.page-1);
			}.extend(this);

			//p.innerHTML+=" Page ";
			(p.appendChild(this.pDt1 = $dce("span"))).innerHTML=" Page ";

			(p.appendChild(this.pDC = $dce("input"))).type="text";
			this.pDC.value		= this.options.paginator.page;
			this.pDC.className	= "app_grid_pDC___"+theme;
			this.pDC.onkeyup=function(evt)
			{
				var evt = (window.event)?window.event:evt;
				var key = (evt.which)?evt.which:evt.keyCode;
				var pg = parseInt(this.pDC.value);
				pg = (isNaN(pg)?1:pg);
				if(key==13)
				{
					this.paginatorTo(pg);
				}
				return false;
			}.extend(this);

			(p.appendChild(this.pDt2 = $dce("span"))).innerHTML=" of ";

			(p.appendChild(this.pDT = $dce("span"))).innerHTML=this.options.paginator.pages;
			this.pDT.className="app_grid_pDT___"+theme;


			(p.appendChild(this.pDn = $dce("a"))).href="#";
			this.pDn.className="app_grid_pDn___"+theme;
			this.pDn.onmouseup=function()
			{
				return this.paginatorTo(this.options.paginator.page+1);
			}.extend(this);

			(p.appendChild(this.pDl = $dce("a"))).href="#";
			this.pDl.className="app_grid_pDl___"+theme;
			this.pDl.onmouseup=function()
			{
				return this.paginatorTo(this.options.paginator.pages);
			}.extend(this);
			this.paginatorTo(this.options.paginator.page);
		};
		this.paginatorTo=function(pagTo,force)
		{
			pagTo = (pagTo<=0)?1:((pagTo>this.options.paginator.pages)?this.options.paginator.pages:pagTo);
			this.pDC.value	= pagTo;
			if(pagTo!==this.options.paginator.page || force===true)
			{
				this.options.paginator.page=pagTo;
				this.clear();
				this.renderData();
			}
			if(pagTo===1)
			{
				this.pDf.className="app_grid_pDfDisabled___"+this.options.theme;
				this.pDp.className="app_grid_pDpDisabled___"+this.options.theme;
			}
			else
			{
				this.pDf.className="app_grid_pDf___"+this.options.theme;
				this.pDp.className="app_grid_pDp___"+this.options.theme;
			}
			if(pagTo===this.options.paginator.pages)
			{
				this.pDn.className="app_grid_pDnDisabled___"+this.options.theme;
				this.pDl.className="app_grid_pDlDisabled___"+this.options.theme;
			}
			else
			{
				this.pDn.className="app_grid_pDn___"+this.options.theme;
				this.pDl.className="app_grid_pDl___"+this.options.theme;
			}
			return false;
		};
		this.renderSearched=function(evt,stext)
		{
			//alert(345345)
			this.dataToRender = [];
			var text = this.searchText.value;
			//var r = this.options.data.rows;
			var r = this.options.data.rows;
			var rw = this.elements.table.rows;
			var c = this.options.data.column;
			for(var i=0;i<r.length;i++)
			{
				var display	= "none";
				var dc = r[i].data;
				for(var j=0;j<c.length;j++)
				{
				var d = dc[j];
					//console.info(c[j].type+"::"+d.value)
					if(c[j].type=="text" || c[j].type==="textDropdown" || c[j].type==="dropdown"  || c[j].type==="textarea")
					{
						//alert(d.value+":"+c[j].type)
						//alert((this.getText({i:i,j:j,type:c[j].type})).toString()+"::"+c[j].searchable +"::"+(this.getText({i:i,j:j,type:c[j].type})).toString().match(new RegExp(text,"i")));
						if((this.getText({i:i,j:j,type:c[j].type})).toString().match(new RegExp(text,"i")) && c[j].searchable)
						{
							this.dataToRender.push(i);
							display	= "";
							break;
						}
					}
				}
				//alert(r[i].info.rowIndex)
				//this.elements.table.rows[i+1].style.display=display;
			//this.elements.table.rows[r[i].info.rowIndex].style.display=display;
			}
			this.options.paginator.pages=Math.ceil(this.dataToRender.length/this.options.paginator.limit);
			this.pDT.innerHTML=this.options.paginator.pages;
			//alert(this.dataToRender.length);
			this.paginatorTo(1,true);
			//alert(this.dataToRender.length);
			//this.elements.table.rows[2].style.display="none";
		};
		this.fillAllData=function()
		{

			this.dataToRender=[];
			var c = this.options.data.rows;
			for(var i=0;i<c.length;i++)
			{
				this.dataToRender.push(i);
			}
			this.options.paginator.pages=Math.ceil(this.dataToRender.length/this.options.paginator.limit);
		};
		this.sort=function(data)
		{
			data = {
				column:1
			}.concatMaborak(data || {});
			var d	 = this.dataToRender;
			var r	 = this.options.data.rows;
			var c	 = this.options.data.column[data.column];
			var t	 = c.type;
			var limit= d.length;
			c.order	 = c.order || "ASC";
			data.order = c.order;
			c.order	= (c.order == "ASC")?"DESC":"ASC";
			var sd = [];
			for(var i=0;i<limit;i++)
			{
				sd.push({
					index:d[i],
					value:r[d[i]].data[data.column].value
				});
			}
			var sortedData = sd.sort(function(a,b,order){
				var aComp = a.value[0];
				var bComp = b.value[0];
				//console.info(order);
				order1	= (order=="ASC")?-1:1;
				order2	= (order=="ASC")?1:-1;
				if (aComp < bComp) {return order1}
				if (aComp > bComp) {return order2}
				return 0;
			}.args(data.order));
			var sortedIndex = [];
			for(var i=0;i<sortedData.length;i++)
			{
				sortedIndex.push(sortedData[i].index);
			}
			this.dataToRender = sortedIndex;
			this.paginatorTo(this.options.paginator.page,true);
			return true;
		};
		this.getText=function(data)
		{
			i = data.i;
			j = data.j;
			var r= this.options.data.rows[i].data;
			var c = this.options.data.column[j];
			var t = "";
			if(data.type=="text" || data.type=="textarea")
			{
				t = r[j].value;
			}
			else if(data.type=="textDropdown" || data.type=="dropdown")
			{
				t = c.data[r[j].value][1];
			}
			return t;
		};
		this.renderSearch=function()
		{
			var div = $dce("div");
			div.className="app_grid_headerBar___"+this.options.theme;
			this.parent.dom.setStyle(div,{
				padding:1,
				textAlign:"right"
			});
			this.searchText = new input(false,{width:200});
			this.searchText.onkeyup=function(evt)
			{
				//alert(searchText)
				var evt = (window.event)?window.event:evt;
				var key = (evt.which)?evt.which:evt.keyCode;
				if(key==13)
				{
					this.renderSearched();
				}
				//this.renderSearched();
			}.extend(this);
			div.appendChild(this.searchText);
			div.appendChild(new button("Search",this.renderSearched,{width:70}));
			div.appendChild(new button("Options",function(evt)
			{
				/*evt		= (window.event)?window.event:evt;
				var posM= this.parent.dom.mouse(evt);*/
				options = new leimnud.module.panel();
				options.options={
					size		:{w:200,h:200},
					title		:"",
					target		:this.options.target,
					position	:{x:10,y:10,center:true},
					limit		:true,
					statusBar	:false,
					//titleBar	:false,
					control:{
						drag	:false,
						close	:true,
						resize	:false
					},
					fx:{
						modal	:true,
						shadow	:false,
						fadeIn	:false,
						fadeOut	:true
					}
				};
				//options.styles.fx.opacityModal.Static=70;
				options.setStyle={
					//modal	:{backgroundColor:"white"},
					//frontend:{backgroundColor:"white"},
					//containerWindow:{backgroundColor:"white",border:"1px solid #99BBE8"},
					//content:{border:"1px solid red"},
					//titleBar:{background:"none"},
					content :{padding:0,borderWidth:0,backgroundColor:"transparent",border:"0px solid red"}
				};
				options.make();
				//options.addContent("<div style='text-align:right;'><b>Search in:</b></div><br />");
				var fs = $dce("fieldset");
				this.parent.dom.setStyle(fs,{
					fontWeight	:"bold",
					//border		:"1px solid #99BBE8",
					//height		:"100%",
					color		:"#000"
				});
				var lg = $dce("legend");
				lg.innerHTML="Search in:";
				fs.appendChild(lg);
				options.addContent(fs);

				var dr = this.options.data.column;
				var se = [];
				var cn = $dce("div");
				this.parent.dom.setStyle(cn,{
					fontWeight	:"normal",
					textAlign	:"right",
					//backgroundColor	:"#D0DEF0",
					//border		:"1px solid #99BBE8",
					color		:"#000"
				});
				fs.appendChild(cn);
				for(var i=0;i<dr.length;i++)
				{
					if(dr[i].type==="text" || dr[i].type==="textDropdown" || dr[i].type==="dropdown" || dr[i].type==="textarea")
					{
						var ti = $dce("div");
						this.parent.dom.setStyle(ti,{
							verticalAlign	:"middle",
							padding			:1
						});
						ti.innerHTML=dr[i].title+"&nbsp;&nbsp;&nbsp;";
						cn.appendChild(ti);
						var sl = $dce("input");
						sl.type	= "checkbox";
						ti.appendChild(sl);
						sl.checked = dr[i].searchable;
						sl.onchange=function(evt,data)
						{
							var data = (window.event)?evt:data;
							this.options.data.column[data.ro].searchable=data.a.checked;
						}.extend(this,{a:sl,ro:i});
					}
				}
				//options.addContent(cn);
			}.extend(this),{width:70}).disable());
			div.appendChild(new button("Add").disable());
			div.appendChild(new button("Del").disable());
			div.appendChild(new button("New").disable());
			this.options.target.appendChild(div);
		};
		this.editText=function(indexRow,indexCell,dom)
		{
			dom.ondblclick = function(evt,data)
			{
				data = (window.event)?evt:data;
				var indexRow=data.indexRow,indexCell=data.indexCell;
				var dataCell	= this.options.data.column[indexCell];
				var dataRow 	= this.options.data.rows[indexRow].data[indexCell];
				//alert(indexRow+":"+indexCell+":"+dom.offsetWidth+":"+dom.offsetHeight);
				var tp	  = (dataCell.type=="text")?"input":"textarea";
				var input = $dce(tp);
				if(dataCell.type=="textarea")
				{
					this.parent.dom.setStyle(input,{
						height	:dom.offsetHeight-5
					});
				}
				else
				{
					input.type="text";
				}
				this.parent.dom.setStyle(input,{
					font		:"normal 8pt Tahoma,sans-serif,Tahoma",
					padding	:3,
					paddingLeft	:3,
					border		:"1px solid buttonHighlight",
					borderLeft	:"1px solid buttonShadow",
					borderTop	:"1px solid buttonShadow",
					backgroundColor:"#EEE",
					overflow	:"none",
					width		:"100%"
				});
				dom.innerHTML="";
				this.parent.dom.setStyle(dom,{
					padding	:1
				});
				input.value=dataRow.value;
				input.onblur=function()
				{
					this.parent.dom.setStyle(dom,{
						padding	:5
					});
					//alert(this.options.data.column[indexCell].onchange);
					//dom.innerHTML=dataRow.value = input.value.escapeHTML();
					dom.innerHTML=dataRow.value = input.value;
					if(this.options.data.column[indexCell].onchange)
					{
						this.options.data.column[indexCell].onchange({index:this.options.data.rows[indexRow],dom:input});
					}

				}.extend(this);
				dom.appendChild(input);
				input.focus();

			}.extend(this,{indexRow:indexRow,indexCell:indexCell,dom:dom});
		};
		this.editTextDropdown=function(indexRow,indexCell,dom)
		{
			dom.ondblclick = function(evt,data)
			{
				data = (window.event)?evt:data;
				var indexRow=data.indexRow,indexCell=data.indexCell;
				var dataCell	= this.options.data.column[indexCell];
				var dataRow 	= this.options.data.rows[indexRow].data[indexCell];
				dom.innerHTML="";
				this.parent.dom.setStyle(dom,{
					padding	:1
				});
				var select		= $dce("select");
				this.parent.dom.setStyle(select,{
					font:"normal 11px arial,tahoma,helvetica,sans-serif",
					width:"100%"
				});
				for(var i=0;i<dataCell.data.length;i++)
				{
					var selected = (dataRow.value===dataCell.data[i][0]);
					//select.options[i]=new Option(dataCell.data[i][1],dataCell.data[i][0],true);
					var option	= $dce("option");
					select.appendChild(option);
					option.value=dataCell.data[i][0];
					option.selected = selected;
					option.text	=dataCell.data[i][1];
				}
				dom.appendChild(select);
				select.onblur=function()
				{
					this.parent.dom.setStyle(dom,{
						padding	:5
					});
					//alert(dataRow.value+":"+dataCell.data[indexRow][1])
					//alert(dataRow.value)
					dom.innerHTML=this.editTextDropdownSelectUID(indexCell,indexRow);
				}.extend(this);
				select.onchange=function()
				{
					//alert(dataRow.value+":"+select.options[select.selectedIndex].value)
					this.options.data.rows[indexRow].data[indexCell].value=parseInt(select.options[select.selectedIndex].value,10);
					//dataRow.value=select.options[select.selectedIndex].value;
					if(this.options.data.column[indexCell].onchange)
					{
						this.options.data.column[indexCell].onchange({index:this.options.data.rows[indexRow],dom:select});
					}
					select.blur();
				}.extend(this);
				select.focus();
			}.extend(this,{indexRow:indexRow,indexCell:indexCell,dom:dom});
		};
		this.editTextDropdownSelectUID=function(cell,row)
		{
			data	= this.options.data.column[cell].data;
			uid	= this.options.data.rows[row].data[cell].value;
			//alert(data+":"+uid);
			for(var i=0;i<data.length;i++)
			{
				if(data[i][0]===uid)
				{
					return data[i][1];
				}
			}
			return "";
		};
		this.clear=function()
		{
			//alert(this.elements.table.rows.length);
			var total = this.elements.table.rows.length;
			//for(var i=0;i<total;i++)
			for(var i=total-1;i>0;i--)
			{
				//alert(this.elements.table.rows[i])
				this.parent.dom.remove(this.elements.table.rows[i]);
			}
		};
		this.searchables=function()
		{
			var dr = this.options.data.column;
			var se = [];
			for(var i=0;i<dr.length;i++)
			{
				if(dr[i].type==="text")
				{
					se.push(i);
				}
			}
			return se;
		};
		this.dragCell=function(evt,cellIndex)
		{
			var event = window.event || evt;
			if(this.inDragProcess===true || !event.ctrlKey)
			{
				return false;
			}
			this.inDragProcess=true;
			//alert(this.parent.event.dom(event).parentNode);
			cellIndex	= (this.parent.browser.isIE)?evt:cellIndex;
			//var domRow = this.elements.table.rows[row+1];
			var domCell = this.parent.event.dom(event);
			this.phantomCellStatic = $dce("div");
			this.phantomCell = $dce("div");
			var j = this.parent.dom.position(domCell,false,true);
			var k = this.parent.dom.position(this.elements.table.rows[this.elements.table.rows.length-1].cells[domCell.cellIndex],true,true);
			//console.info(k)
			this.parent.dom.setStyle([this.phantomCell,this.phantomCellStatic],{
				position:"absolute",
				width	:domCell.offsetWidth-1,
				height	:(k.y-j.y)-1,
				border	:"1px solid #99BBE8",
				backgroundColor	:"#E6EDF7",
				top		:j.y,
				left	:j.x
			});
			this.parent.dom.setStyle(this.phantomCell,{
				border	:"1px solid #ff4400",
				backgroundColor	:"orange"
			});
			this.parent.dom.setStyle(this.phantomCellStatic,{
				border	:"1px solid green",
				backgroundColor	:"green"
			});

			this.parent.dom.opacity(this.phantomCell,30);
			this.parent.dom.opacity(this.phantomCellStatic,20);
			this.options.target.appendChild(this.phantomCellStatic);
			this.options.target.appendChild(this.phantomCell);

			this.dropToCell = new this.parent.module.drop();
			this.dropToCell.make();
			this.registerCellsToDrop();

			this.dragToCell=new this.parent.module.drag({
				elements:this.phantomCell,
				limit:"y"
			});
			this.dragToCell.events={
				move	:this.dropToCell.captureFromArray.args(this.dragToCell,false,true),
				finish	:function(movedUID,domCell){
					//var insertRowIn = (this.drop.selected===false)?
					var insertRowIn,begin;
					var currentIndex = parseInt(domCell.cellIndex);
					this.parent.dom.opacity(domCell,100);
					if(this.dragToCell.moved)
					{
						if(this.dropToCell.selected===false)
						{
							var uid=this.dropToCell.arrayPositions.length-1;
							if((this.dropToCell.position.y > this.dropToCell.arrayPositions[uid].y2))
							{
								insertCellIn= uid;
								begin		= false;
							}
							else
							{
								insertCellIn= 0;
								begin		= true;
							}
						}
						else
						{
							insertCellIn= this.dropToCell.selected;
							begin		= true;
						}
						//var newRow=this.elements.table.insertRow((begin)?insertRowIn+1:-1);
						var row0 = this.elements.table.rows[0];
						var insI = (begin)?insertCellIn+1:row0.length-1;
						//console.info("Nuevo en: "+insI);
						//alert(insI)
						var newCell = row0.insertCell(insI);
						newCell.parentNode.replaceChild(domCell,newCell);
						//console.info("Nuevo cellIndex: "+domCell.cellIndex+":"+newCell.cellIndex);
						for(var i=1;i<this.elements.table.rows.length;i++)
						{
							var cll = this.elements.table.rows[i].cells[currentIndex];
							var newLl = this.elements.table.rows[i].insertCell(insI);
							newLl.parentNode.replaceChild(cll,newLl);
						}
					}
					else
					{
						insertCellIn= domCell.cellIndex-1;
						begin		= true;
					}
					this.parent.dom.remove([this.phantomCellStatic,this.imageAddCell]);
					this.dragToCell.flush();
					new this.parent.module.fx.move().make({
						duration:((this.dragToCell.moved)?500:0),
						end		:this.parent.dom.position(domCell,false,true),
						dom		:this.phantomCell,
						onFinish	:function()
						{
							new this.parent.module.fx.fade().make({
								duration:500,
								end		:0,
								dom		:this.phantomCell,
								onFinish:function(){
									this.parent.dom.remove(this.phantomCell);
									this.inDragProcess=false;
								}.extend(this)
							});
						}.extend(this)
					});
				}.extend(this,cellIndex,domCell)
			};
			this.dragToCell.make();
			this.dragToCell.onInit(event,0);
			this.imageAddCell = $dce("img");
			this.imageAddCell.src=this.parent.info.images+"nc.gif";
			this.setImageAddCell(domCell.cellIndex-1);
			this.options.target.appendChild(this.imageAddCell);
			return false;
		};
		this.dragRow=function(evt,row)
		{
			var event = window.event || evt;
			if(this.inDragProcess===true || !event.ctrlKey)
			{
				return false;
			}
			this.inDragProcess=true;
			//alert(this.parent.event.dom(event).parentNode);
			row	= (this.parent.browser.isIE)?evt:row;
			//alert(row)
			//var domRow = this.elements.table.rows[row+1];
			var domRow = this.parent.event.dom(event).parentNode;
			//alert(domRow.rowIndex)
			this.phantomRowStatic = $dce("div");
			this.phantomRow = $dce("div");
			var j = this.parent.dom.position(domRow,false,true);
			this.parent.dom.setStyle([this.phantomRow,this.phantomRowStatic],{
				position:"absolute",
				width	:domRow.clientWidth-1,
				height	:domRow.clientHeight-1,
				border	:"1px solid #99BBE8",
				backgroundColor	:"#E6EDF7",
				top		:j.y,
				left	:j.x
			});
			this.parent.dom.setStyle(this.phantomRow,{
				border	:"1px solid #99BBE8",
				backgroundColor	:"#E6EDF7"
			});
			this.parent.dom.setStyle(this.phantomRowStatic,{
				border	:"1px solid green",
				backgroundColor	:"green"
			});
			//this.parent.dom.opacity(domRow,40);
			this.parent.dom.opacity(this.phantomRow,60);
			this.parent.dom.opacity(this.phantomRowStatic,20);
			this.options.target.appendChild(this.phantomRowStatic);
			this.options.target.appendChild(this.phantomRow);

			this.drop = new this.parent.module.drop();
			this.drop.make();
			this.registerRowsToDrop();

			//this.createthis.phantomRowRow(row+1);
			this.parent.dom.setStyle(domRow,{
				//display:"none"
			});
			this.drag=new this.parent.module.drag({
				elements:this.phantomRow,
				limit:"x"
			});
			this.drag.events={
				init	:function(row){

				}.extend(this,row+1),
				move	:this.drop.captureFromArray.args(this.drag,false,true),
				finish	:function(movedUID,domRow){
					//var insertRowIn = (this.drop.selected===false)?
					var insertRowIn,begin;
					this.parent.dom.opacity(domRow,100);
					if(this.drag.moved)
					{
						if(this.drop.selected===false)
						{
							var uid=this.drop.arrayPositions.length-1;
							if((this.drop.position.y > this.drop.arrayPositions[uid].y2)/* && this.lastSelected===uid*/)
							{
								insertRowIn	= uid;
								begin		= false;
							}
							else
							{
								insertRowIn	= 0;
								begin		= true;
							}
						}
						else
						{
							insertRowIn	= this.drop.selected;
							begin		= true;
						}
						var newRow=this.elements.table.insertRow((begin)?insertRowIn+1:-1);
						newRow.parentNode.replaceChild(domRow,newRow);
						//alert(movedUID+":"+this.options.data.rows[movedUID].info.rowIndex+":"+domRow.rowIndex)
						this.options.data.rows[movedUID].info.rowIndex=domRow.rowIndex;
						//alert(domRow.rowIndex)
					}
					else
					{
						insertRowIn	= domRow.rowIndex-1;
						begin		= true;
					}
					this.parent.dom.remove([this.phantomRowStatic,this.imageAddRow]);
					//window.status=insertRowIn+":"+begin;
					this.drag.flush();
					//this.parent.dom.remove([this.phantomRow,this.phantomRowStatic,this.imageAddRow]);
					new this.parent.module.fx.move().make({
						duration:((this.drag.moved)?500:0),
						//end		:{x:this.drop.arrayPositions[insertRowIn].x1,y:this.drop.arrayPositions[insertRowIn].y1},
						end		:this.parent.dom.position(domRow,false,true),
						dom		:this.phantomRow,
						onFinish	:function(insertRowIn,begin,domRow)
						{
							new this.parent.module.fx.fade().make({
								duration:500,
								end		:0,
								dom		:this.phantomRow,
								onFinish	:function(){
									this.parent.dom.remove(this.phantomRow);
									this.inDragProcess=false;
								}.extend(this)
							});
						}.extend(this,insertRowIn,begin,domRow)
					});
					//alert("insertRowIn="+insertRowIn+":begin="+begin+":movedUID="+movedUID+":domRow="+domRow);
					//alert(this.drop.arrayPositions[insertRowIn].y1);
				}.extend(this,row,domRow)
			};
			this.drag.make();
			this.drag.onInit(event,0);
			this.imageAddRow = $dce("img");
			this.imageAddRow.src=this.parent.info.images+"nr.gif";
			this.setImageAddRow(domRow.rowIndex-1);
			this.options.target.appendChild(this.imageAddRow);
			//this.elements.table.rows[1]=this.elements.table.rows[3];
			/*var uidMm = this.parent.event.add(this.options.target,"mousemove",function(evt)
			{
			evt = window.event || evt;
			var m = this.parent.dom.mouse(evt);
			window.status = "x="+m.x+": y="+m.y;
			}.extend(this));
			this.parent.event.removeFromUid(uidMm);*/
			//alert(uidMm)
			/*var tableCloned = this.elements.table.cloneNode(false);
			this.parent.dom.setStyle(tableCloned,{
			//border	: "1px dashed red"
			});
			//alert(this.elements.table.clientWidth)
			this.phantomRow.appendChild(tableCloned);
			var headCloned = this.elements.table.rows[0].cloneNode(true)
			var rowCloned = domRow.cloneNode(true);
			this.parent.dom.opacity(headCloned,0);
			for(var k=0;k<headCloned.cells.length;k++)
			{
			this.parent.dom.setStyle(headCloned.cells[k],{
			borderWidth:0,
			height:1
			});
			this.parent.dom.setStyle(rowCloned.cells[k],{
			borderTop:"1px solid red",
			borderBottom:"1px solid red"
			});
			}
			this.parent.dom.setStyle(rowCloned.cells[0],{
			borderLeft:"1px solid red"
			});
			this.parent.dom.setStyle(rowCloned.cells[rowCloned.cells.length-1],{
			borderRight:"1px solid red"
			});
			tableCloned.appendChild(headCloned);
			tableCloned.appendChild(rowCloned);
			//this.fixColumnsWidth(domRow,rowCloned);
			this.parent.dom.setStyle(this.phantomRow,{
			top	:j.y-headCloned.clientHeight,
			left:j.x
			});*/

			//this.ss();
			//domRow.style.border="2px solid red";
			//domRow.tabIndex=3;
			//alert(domRow.rowIndex)
		};
		this.setImageAddRow=function(uid,last)
		{
			this.parent.dom.setStyle(this.imageAddRow,{
				position:"absolute",
				zIndex	:100,
				top:((last)?this.drop.arrayPositions[uid].y2:this.drop.arrayPositions[uid].y1)-5,
				left:this.drop.arrayPositions[uid].x1-1
			});
		};
		this.setImageAddCell=function(uid,last)
		{
			this.parent.dom.setStyle(this.imageAddCell,{
				position:"absolute",
				zIndex	:100,
				top:((last)?this.dropToCell.arrayPositions[uid].y2:this.dropToCell.arrayPositions[uid].y1)-5,
				left:this.dropToCell.arrayPositions[uid].x1-5
			});
		};
		this.registerCellsToDrop=function(inRow)
		{
			var row = this.elements.table.rows[0];
			for(var i=1;i<row.cells.length;i++)
			{
				//if(i!=inRow)
				//{
				//alert(this.elements.table.rows[i])
				this.dropToCell.register({
					element	: row.cells[i],
					value	: i,
					events	: {
						over:function(i)
						{
							//this.setImageAddRow(this.drop.lastSelected);
							//console.info(this.dropToCell.selected);
							if(this.dropToCell.selected!==false)
							{
								this.setImageAddCell(this.dropToCell.selected);
							}
						}.extend(this,i),
						out		:function(i)
						{
							if(this.dropToCell.selected===false)
							{
								var uid=this.dropToCell.arrayPositions.length-1;
								if(this.dropToCell.position.y > this.dropToCell.arrayPositions[uid].y2)
								{
									this.setImageAddCell(uid,true);
								}
							}
						}.extend(this,i)
					}
				});
				//}
			}
			this.dropToCell.setArrayPositions(true);
		};
		this.registerRowsToDrop=function(inRow)
		{
			for(var i=1;i<this.elements.table.rows.length;i++)
			{
				//if(i!=inRow)
				//{
				//alert(this.elements.table.rows[i])
				this.drop.register({
					element	: this.elements.table.rows[i],
					value	: i,
					events	: {
						over:function(i)
						{
							//this.setImageAddRow(this.drop.lastSelected);
							if(this.drop.selected!==false)
							{
								this.setImageAddRow(this.drop.selected);
							}
						}.extend(this,i),
						out		:function(i)
						{
							if(this.drop.selected===false)
							{
								var uid=this.drop.arrayPositions.length-1;
								if(this.drop.position.y > this.drop.arrayPositions[uid].y2)
								{
									this.setImageAddRow(uid,true);
								}
							}
						}.extend(this,i)
					}
				});
				//}
			}
			this.drop.setArrayPositions(true);
		};
		/**
		* {Dom} a Source
		* {Dom} b Destiny
		*/
		this.fixColumnsWidth=function(a,b)
		{
			var h=0;
			for(var i=0;i<a.cells.length;i++)
			{
				this.parent.dom.setStyle(b.cells[i],{
					width:a.cells[i].clientWidth-2
				});
				h+=a.cells[i].clientWidth+2;
			}
			//alert(h)
		};
		this.createPhantomRow=function(In)
		{
			var domRow = this.elements.table.rows[In];
			var ga = this.elements.table.insertRow(In);
			for(var i=0;i<this.elements.table.rows[0].cells.length;i++)
			{
				var gc = ga.insertCell(i);
				gc.innerHTML=" ";
			}
			ga.style.height=domRow.clientHeight;
		};
		this.save=function(type)
		{
			type = type || "json";
			var data={};
			data.column=this.options.data.column;
			data.rows = [];
			for(var i=0;i<this.options.data.rows.length;i++)
			{
				var row = this.options.data.rows[i];
				var ro = {
					info: row.info || {},
					data: []
				};
				for(var j=0;j<row.data.length;j++)
				{
					if(row.data[j]!=null)
					{
						var v = this.options.data.rows[i].data[j];
						ro.data.push(v);
					}
				}
				data.rows.push(ro);
			}
			return (type=='json')?data.toJSONString():data;
		};
		this.add=function()
		{

		},
		this.expand(this);
	}
});
