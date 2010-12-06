
/*
Awesome Uploader
AwesomeUploader JavaScript Class

Copyright (c) 2010, Andrew Rymarczyk
All rights reserved.

Redistribution and use in source and minified, compiled or otherwise obfuscated 
form, with or without modification, are permitted provided that the following 
conditions are met:

	* Redistributions of source code must retain the above copyright notice, 
		this list of conditions and the following disclaimer.
	* Redistributions in minified, compiled or otherwise obfuscated form must 
		reproduce the above copyright notice, this list of conditions and the 
		following disclaimer in the documentation and/or other materials 
		provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND 
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED 
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE 
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE 
FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL 
DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR 
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER 
CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, 
OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE 
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

/*
if(SWFUpload !== undefined){
	SWFUpload.UPLOAD_ERROR_DESC = {
		'-200': 'HTTP ERROR'
		,'-210': 'MISSING UPLOAD URL'
		,'-220': 'IO ERROR'
		,'-230': 'SECURITY ERROR'
		,'-240': 'UPLOAD LIMIT EXCEEDED'
		,'-250': 'UPLOAD FAILED'
		,'-260': 'SPECIFIED FILE ID NOT FOUND'
		,'-270': 'FILE VALIDATION FAILED'
		,'-280': 'FILE CANCELLED'
		,'-290': 'UPLOAD STOPPED'
	};
	SWFUpload.QUEUE_ERROR_DESC = {
		'-100': 'QUEUE LIMIT EXCEEDED'
		,'-110': 'FILE EXCEEDS SIZE LIMIT'
		,'-120': 'ZERO BYTE FILE'
		,'-130': 'INVALID FILETYPE'
	};
}
*/

AwesomeUploader = Ext.extend(Ext.Panel, {
	jsonUrl:'/test/router/fileMan/'
	,jsonUrlUpload:'processes_doUpload'
	,swfUploadItems:[]
	,doLayout:function(){
		AwesomeUploader.superclass.doLayout.apply(this, arguments);
		this.fileGrid.getView().refresh();
	}
	,initComponent:function(){
		
		this.addEvents(
			'fileupload' 
				// fireEvent('fileupload', Obj thisUploader, Bool uploadSuccessful, Obj serverResponse);
				//server response object will at minimum have a property "error" describing the error.
			,'fileselectionerror'
				// fireEvent('fileselectionerror', String message)
				//fired by drag and drop and swfuploader if a file that is too large is selected.
				//Swfupload also fires this even if a 0-byte file is selected or the file type does not match the "flashSwfUploadFileTypes" mask
		);
	
		var fields = ['id', 'name', 'size', 'status', 'progress'];
		this.fileRecord = Ext.data.Record.create(fields);

		this.initialConfig = this.initialConfig || {};
		this.initialConfig.awesomeUploaderRoot = this.initialConfig.awesomeUploaderRoot || '/skins/ext/images/gray/shapes/';

		Ext.apply(this, this.initialConfig, {
			flashButtonSprite:this.initialConfig.awesomeUploaderRoot+ 'swfupload_browse_button_trans_56x22.PNG'
			,flashButtonWidth:'56'
			,flashButtonHeight:'22'
			,flashUploadFilePostName:'Filedata'
			,disableFlash:false
			,flashSwfUploadPath:this.initialConfig.awesomeUploaderRoot+'swfupload.swf'
			//,flashSwfUploadFileSizeLimit:'3 MB' //deprecated
			,flashSwfUploadFileTypes:'*.*'
			,flashSwfUploadFileTypesDescription:'All Files'
			,flashUploadUrl:'processes_doUpload.php'
			,xhrUploadUrl:'xhrupload.php'
			,xhrFileNameHeader:'X-File-Name'
			,xhrExtraPostDataPrefix:'extraPostData_'
			,xhrFilePostName:'Filedata'
			,xhrSendMultiPartFormData:false
			,maxFileSizeBytes: 3145728 // 3 * 1024 * 1024 = 3 MiB
			,standardUploadFilePostName:'Filedata'
			,standardUploadUrl:'processes_doUpload.php'
			,iconStatusPending:this.initialConfig.awesomeUploaderRoot+'hourglass.png'
			,iconStatusSending:this.initialConfig.awesomeUploaderRoot+'loading.gif'
			,iconStatusAborted:this.initialConfig.awesomeUploaderRoot+'cross.png'
			,iconStatusError:this.initialConfig.awesomeUploaderRoot+'cross.png'
			,iconStatusDone:this.initialConfig.awesomeUploaderRoot+'tick.png'
			,supressPopups:false
			,extraPostData:{}
			,width:440
			,height:250
			,autoScroll: true
			,border:true
			,frame:true
			,layout:'absolute'
			,fileId:0
			,items:[
			{
				//swfupload and upload button container
			},{
				xtype:'grid'
				,x:0
				,y:30
				,width:this.initialConfig.gridWidth || 420
				,height:this.initialConfig.gridHeight || 200
				,enableHdMenu:false
				,store:new Ext.data.ArrayStore({
					fields: fields
					,reader: new Ext.data.ArrayReader({idIndex: 0}, this.fileRecord)
				})
				,columns:[
					{header:'File Name',dataIndex:'name', width:150}
					,{header:'Size',dataIndex:'size', width:60, renderer:Ext.util.Format.fileSize}
					,{header:'&nbsp;',dataIndex:'status', width:30, scope:this, renderer:this.statusIconRenderer}
					,{header:'Status',dataIndex:'status', width:60}
					,{header:'Progress',dataIndex:'progress',scope:this, renderer:this.progressBarColumnRenderer}
				]
				,listeners:{
					render:{
						scope:this
						,fn:function(){
							this.fileGrid = this.items.items[1];
							this.initFlashUploader();
							this.initDnDUploader();								
						}	
					}
				}
			}]
		});
				
		AwesomeUploader.superclass.initComponent.apply(this, arguments);
	}
	,fileAlert:function(text){
		if(this.supressPopups){
			return true;
		}
		if(this.fileAlertMsg === undefined || !this.fileAlertMsg.isVisible()){
			this.fileAlertMsgText = 'Error uploading:<BR>'+text;
			this.fileAlertMsg = Ext.MessageBox.show({
				title:'Upload Error',
				msg: this.fileAlertMsgText,
				buttons: Ext.Msg.OK,
				modal:false,
				icon: Ext.MessageBox.ERROR
			});
		}else{
				this.fileAlertMsgText += text;
				this.fileAlertMsg.updateText(this.fileAlertMsgText);
				this.fileAlertMsg.getDialog().focus();
		}
		
	}
	,statusIconRenderer:function(value){
		switch(value){
			default:
				return value;
			case 'Pending':
				return '<img src="'+this.iconStatusPending+'" width=16 height=16>';
			case 'Sending':
				return '<img src="'+this.iconStatusSending+'" width=16 height=16>';
			case 'Aborted':
				return '<img src="'+this.iconStatusAborted+'" width=16 height=16>';
			case 'Error':
				return '<img src="'+this.iconStatusError+'" width=16 height=16>';
			case 'Done':
				return '<img src="'+this.iconStatusDone+'" width=16 height=16>';
		}
	}
	,progressBarColumnTemplate: new Ext.XTemplate(
			'<div class="ux-progress-cell-inner ux-progress-cell-inner-center ux-progress-cell-foreground">',
				'<div>{value} %</div>',
			'</div>',
			'<div class="ux-progress-cell-inner ux-progress-cell-inner-center ux-progress-cell-background" style="left:{value}%">',
				'<div style="left:-{value}%">{value} %</div>',
			'</div>'
    )
	,progressBarColumnRenderer:function(value, meta, record, rowIndex, colIndex, store){
        meta.css += ' x-grid3-td-progress-cell';
		return this.progressBarColumnTemplate.apply({
			value: value
		});
	}
	,addFile:function(file){
	
		var fileRec = new this.fileRecord(
			Ext.apply(file,{
				id: ++this.fileId
				,status: 'Pending'
				,progress: '0'
				,complete: '0'
			})
		);
		this.fileGrid.store.add(fileRec);
		
		return fileRec;
	}
	,updateFile:function(fileRec, key, value){
		fileRec.set(key, value);
		fileRec.commit();
	}
	,initStdUpload:function(param){
		if(this.uploader){
			this.uploader.fileInput = null; //remove reference to file field. necessary to prevent destroying file field during upload.
			Ext.destroy(this.uploader);
		}else{
			Ext.destroy(this.items.items[0]);
		}
		this.uploader = new Ext.ux.form.FileUploadField({
			renderTo:this.body
			,buttonText:'Browse...'
			,x:0
			,y:0
			,style:'position:absolute;'
			,buttonOnly:true
			,name:this.standardUploadFilePostName
			,listeners:{
				scope:this
				,fileselected:this.stdUploadFileSelected
			}
		});
		
	}
	,initFlashUploader:function(){
	
		if(this.disableFlash){
			this.initStdUpload();
			return true;
		}
	
		var settings = {
			flash_url: this.flashSwfUploadPath
			,upload_url: this.flashUploadUrl
			,file_size_limit: this.maxFileSizeBytes + ' B'
			,file_types: this.flashSwfUploadFileTypes
			,file_types_description: this.flashSwfUploadFileTypesDescription
			,file_upload_limit: 100
			,file_queue_limit: 0
			,debug: false
			,post_params: this.extraPostData
			,button_image_url: this.flashButtonSprite
			,button_width: this.flashButtonWidth
			,button_height: this.flashButtonHeight
			,button_window_mode: 'opaque'
			,file_post_name: this.flashUploadFilePostName
			,button_placeholder: this.items.items[0].body.dom
			,file_queued_handler: this.swfUploadfileQueued.createDelegate(this)
			,file_dialog_complete_handler: this.swfUploadFileDialogComplete.createDelegate(this)
			,upload_start_handler: this.swfUploadUploadStart.createDelegate(this)
			,upload_error_handler: this.swfUploadUploadError.createDelegate(this)
			,upload_progress_handler: this.swfUploadUploadProgress.createDelegate(this)
			,upload_success_handler: this.swfUploadSuccess.createDelegate(this)
			,upload_complete_handler: this.swfUploadComplete.createDelegate(this)
			,file_queue_error_handler: this.swfUploadFileQueError.createDelegate(this)
			,minimum_flash_version: '9.0.28'
			,swfupload_load_failed_handler: this.initStdUpload.createDelegate(this)
		};
		this.swfUploader = new SWFUpload(settings);
	}
	,initDnDUploader:function(){
		
		//==================
		// Attach drag and drop listeners to document body
		// this prevents incorrect drops, reloading the page with the dropped item
		// This may or may not be helpful
		if(!document.body.BodyDragSinker){
			document.body.BodyDragSinker = true;
			
			var body = Ext.fly(document.body);
			body.on({
				dragenter:function(event){
					return true;
				}
				,dragleave:function(event){
					return true;
				}
				,dragover:function(event){
					event.stopEvent();
					return true;
				}
				,drop:function(event){
					event.stopEvent();
					return true;
				}
			});
		}
		// end body events
		//==================
		
		this.el.on({
			dragenter:function(event){
				event.browserEvent.dataTransfer.dropEffect = 'move';
				return true;
			}
			,dragover:function(event){
				event.browserEvent.dataTransfer.dropEffect = 'move';
				event.stopEvent();
				return true;
			}
			,drop:{
				scope:this
				,fn:function(event){
					event.stopEvent();
					var files = event.browserEvent.dataTransfer.files;

					if(files === undefined){
						return true;
					}
					var len = files.length;
					while(--len >= 0){
						this.processDnDFileUpload(files[len]);
					}
				}
			}
		});
		
	}
	,processDnDFileUpload:function(file){

		var fileRec = this.addFile({
			name: file.name
			,size: file.size
		});
		
		if(file.size > this.maxFileSizeBytes){
			this.updateFile(fileRec, 'status', 'Error');
			this.fileAlert('<BR>'+file.name+'<BR><b>File size exceeds allowed limit.</b><BR>');
			this.fireEvent('fileselectionerror', 'File size exceeds allowed limit.');
			return true;
		}
	
		var upload = new Ext.ux.XHRUpload({
			url:this.xhrUploadUrl
			,filePostName:this.xhrFilePostName
			,fileNameHeader:this.xhrFileNameHeader
			,extraPostData:this.extraPostData
			,sendMultiPartFormData:this.xhrSendMultiPartFormData
			,file:file
			,listeners:{
				scope:this
				,uploadloadstart:function(event){
					this.updateFile(fileRec, 'status', 'Sending');
				}
				,uploadprogress:function(event){
					this.updateFile(fileRec, 'progress', Math.round((event.loaded / event.total)*100));
				}
				// XHR Events
				,loadstart:function(event){
					this.updateFile(fileRec, 'status', 'Sending');
				}
				,progress:function(event){
					fileRec.set('progress', Math.round((event.loaded / event.total)*100) );
					fileRec.commit();
				}
				,abort:function(event){
					this.updateFile(fileRec, 'status', 'Aborted');
					this.fireEvent('fileupload', this, false, {error:'XHR upload aborted'});
				}
				,error:function(event){
					this.updateFile(fileRec, 'status', 'Error');
					this.fireEvent('fileupload', this, false, {error:'XHR upload error'});
				}
				,load:function(event){
					
					try{
						var result = Ext.util.JSON.decode(upload.xhr.responseText);//throws a SyntaxError.
					}catch(e){
						Ext.MessageBox.show({
							buttons: Ext.MessageBox.OK
							,icon: Ext.MessageBox.ERROR
							,modal:false
							,title:'Upload Error!'
							,msg:'Invalid JSON Data Returned!<BR><BR>Please refresh the page to try again.'
						});
						this.updateFile(fileRec, 'status', 'Error');
						this.fireEvent('fileupload', this, false, {error:'Invalid JSON returned'});
						return true;
					}
					if( result.success ){
						fileRec.set('progress', 100 );
						fileRec.set('status', 'Done');
						fileRec.commit();						
						this.fireEvent('fileupload', this, true, result);
					}else{
						this.fileAlert('<BR>'+file.name+'<BR><b>'+result.error+'</b><BR>');
						this.updateFile(fileRec, 'status', 'Error');
						this.fireEvent('fileupload', this, false, result);
					}
				}
			}
		});
		upload.send();
	}
	,swfUploadUploadProgress:function(file, bytesComplete, bytesTotal){
		this.updateFile(this.swfUploadItems[file.index], 'progress', Math.round((bytesComplete / bytesTotal)*100));	
	}
	,swfUploadFileDialogComplete:function(){
		this.swfUploader.startUpload();
	}
	,swfUploadUploadStart:function(file){
		this.swfUploader.setPostParams(this.extraPostData); //sync post data with flash
		this.updateFile(this.swfUploadItems[file.index], 'status', 'Sending');
	}
	,swfUploadComplete:function(file){ //called if the file is errored out or on success
		this.swfUploader.startUpload(); //as per the swfupload docs, start the next upload!
	}
	,swfUploadUploadError:function(file, errorCode, message){
		this.fileAlert('<BR>'+file.name+'<BR><b>'+message+'</b><BR>');//SWFUpload.UPLOAD_ERROR_DESC[errorCode.toString()]

		this.updateFile(this.swfUploadItems[file.index], 'status', 'Error');
		this.fireEvent('fileupload', this, false, {error:message});
	}
	,swfUploadSuccess:function(file, serverData){ //called when the file is done
		try{
			var result = Ext.util.JSON.decode(serverData);//throws a SyntaxError.
		}catch(e){
			Ext.MessageBox.show({
				buttons: Ext.MessageBox.OK
				,icon: Ext.MessageBox.ERROR
				,modal:false
				,title:'Upload Error!'
				,msg:'Invalid JSON Data Returned!<BR><BR>Please refresh the page to try again.'
			});
			this.updateFile(this.swfUploadItems[file.index], 'status', 'Error');
			this.fireEvent('fileupload', this, false, {error:'Invalid JSON returned'});
			return true;
		}
		if( result.success ){
			this.swfUploadItems[file.index].set('progress',100);
			this.swfUploadItems[file.index].set('status', 'Done');
			this.swfUploadItems[file.index].commit();
			this.fireEvent('fileupload', this, true, result);
		}else{
			this.fileAlert('<BR>'+file.name+'<BR><b>'+result.error+'</b><BR>');
			this.updateFile(this.swfUploadItems[file.index], 'status', 'Error');
			this.fireEvent('fileupload', this, false, result);
		}
	}
	,swfUploadfileQueued:function(file){
		this.swfUploadItems[file.index] = this.addFile({
			name: file.name
			,size: file.size
		});
		return true;
	}
	,swfUploadFileQueError:function(file, error, message){
		this.swfUploadItems[file.index] = this.addFile({
			name: file.name
			,size: file.size
		});
		this.updateFile(this.swfUploadItems[file.index], 'status', 'Error');
		this.fileAlert('<BR>'+file.name+'<BR><b>'+message+'</b><BR>');
		this.fireEvent('fileselectionerror', message);
	}
	,stdUploadSuccess:function(form, action){
		form.el.fileRec.set('progress',100);
		form.el.fileRec.set('status', 'Done');
		form.el.fileRec.commit();
		this.fireEvent('fileupload', this, true, action.result);
	}
	,stdUploadFail:function(form, action){
		this.updateFile(form.el.fileRec, 'status', 'Error');
		this.fireEvent('fileupload', this, false, action.result);
		this.fileAlert('<BR>'+form.el.fileRec.get('name')+'<BR><b>'+action.result.error+'</b><BR>');
	}
	,stdUploadFileSelected:function(fileBrowser, fileName){
		
		var lastSlash = fileName.lastIndexOf('/'); //check for *nix full file path
		if( lastSlash < 0 ){
			lastSlash = fileName.lastIndexOf('\\'); //check for win full file path
		}
		if(lastSlash > 0){
			fileName = fileName.substr(lastSlash+1);
		}
		var file = {
			name:fileName
			,size:'0'
		};
		
		if(Ext.isDefined(fileBrowser.fileInput.dom.files) ){
			file.size = fileBrowser.fileInput.dom.files[0].size;
		};
		
		var fileRec = this.addFile(file);
		
		if( file.size > this.maxFileSizeBytes){
			this.updateFile(fileRec, 'status', 'Error');
			this.fileAlert('<BR>'+file.name+'<BR><b>File size exceeds allowed limit.</b><BR>');
			this.fireEvent('fileselectionerror', 'File size exceeds allowed limit.');
			return true;
		}
		
		var formEl = document.createElement('form'),
			extraPost;
		for( attr in this.extraPostData){
			extraPost = document.createElement('input'),
			extraPost.type = 'hidden';
			extraPost.name = attr;
			extraPost.value = this.extraPostData[attr];
			formEl.appendChild(extraPost);
		}
		formEl = this.el.appendChild(formEl);
		formEl.fileRec = fileRec;
		fileBrowser.fileInput.addClass('au-hidden');
		formEl.appendChild(fileBrowser.fileInput);
		formEl.addClass('au-hidden');
		var formSubmit = new Ext.form.BasicForm(formEl,{
			method:'POST'
			,fileUpload:true
		});
		
		formSubmit.submit({
			url:this.standardUploadUrl
			,scope:this
			,success:this.stdUploadSuccess
			,failure:this.stdUploadFail
		});
		this.updateFile(fileRec, 'status', 'Sending');
		this.initStdUpload(); //re-init uploader for multiple simultaneous uploads
	}

});

Ext.reg('awesomeuploader', AwesomeUploader);
