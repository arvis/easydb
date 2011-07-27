Ext.onReady(function(){
    Ext.QuickTips.init();
	var tb = new Ext.Toolbar({width: 650});
	
	var fieldPropsWindow;
	var gridPropsWindow;
	var col_count=1;
	var gridPanel;	
	var formPanel;
	var tableListMenu;
	var grid_cols=[];
	var tableId;

	var store;
	
    store = new Ext.data.ArrayStore({
        //fields: [{name: 'id'}]
        fields: []
    });
	
	var storeFields=[];
	var gridMode="design"; //sets mode to design or grid //TODO: make as enum
	

	function setJsonStore(table_id){
/*	
		var reader = new Ext.data.JsonReader({
			successProperty: 'success',
			idProperty: 'id',
			root: 'data',
			fields:storeFields
		}
		);

		// The new DataWriter component.
		var writer = new Ext.data.JsonWriter({
			encode: true,
			autoLoad:false,
			writeAllFields: false
		});
	
		var proxy = new Ext.data.HttpProxy({
			api: {
				read : 'view.php?grid_action=get_table_data',
				create : 'view.php?grid_action=set_table_data',
				update: 'view.php?grid_action=set_table_data',
				destroy: 'view.php?grid_action=set_table_data'
			}
		});
	
	
	
		var store_params={table_id:table_id};
		store = new Ext.data.JsonStore({
				idProperty: 'id',
				id: 'main_grid',
				proxy: proxy,
				reader: reader,
				writer: writer,  // <-- plug a DataWriter into the store just as you would a Reader
				autoSave: true, // <-- false would delay executing create, update, destroy requests until specifically told to do so with some [save] buton.
				
				baseParams:	store_params,
				autoLoad:false,
				autoSave: false,
				//fields: [{name: 'id'}]
				//fields: storeFields
		});	
*/		

		//var store_params={grid_action:'get_table_data',table_id:table_id};
		var store_params={table_id:table_id};
		
		// The new DataWriter component.
		var writer = new Ext.data.JsonWriter({
			encode: true,
			autoLoad:false,
			writeAllFields: false
		});
		
		var proxy = new Ext.data.HttpProxy({
			api: {
				read : 'view.php?grid_action=get_table_data',
				create : 'view.php?grid_action=set_table_data',
				update: 'view.php?grid_action=set_table_data',
				destroy: 'view.php?grid_action=set_table_data'
			}
		});
		
		

		store = new Ext.data.JsonStore({
				url:  'view.php',
				root: 'data',
				idProperty: 'id',
				baseParams:	store_params,
				
				writer: writer, 
				proxy: proxy,
				
				autoLoad:false,
				autoSave: false,
				//fields: [{name: 'id'}]
				fields: storeFields
		});	
		
		return true;
	}
	

	Ext.override(Ext.data.Store,{
		addField: function(field){
			field = new Ext.data.Field(field);
			this.recordType.prototype.fields.replace(field);
			if(typeof field.defaultValue != 'undefined'){
				this.each(function(r){
					if(typeof r.data[field.name] == 'undefined'){
						r.data[field.name] = field.defaultValue;
					}
				});
			}
		},
		removeField: function(name){
			this.recordType.prototype.fields.removeKey(name);
			this.each(function(r){
				delete r.data[name];
				if(r.modified){
					delete r.modified[name];
				}
			});
		}
	});
	Ext.override(Ext.grid.ColumnModel,{
		addColumn: function(column, colIndex){
			console.log("addColumn 1 ");
			if(typeof column == 'string'){
				column = {header: column, dataIndex: column};
			}
			var config = this.config;
			this.config = [];
			if(typeof colIndex == 'number'){
				config.splice(colIndex, 0, column);
			}else{
				colIndex = config.push(column);
			}
			this.setConfig(config);
			return colIndex;
		},
		removeColumn: function(colIndex){
			var config = this.config;
			this.config = [config[colIndex]];
			config.splice(colIndex, 1);
			this.setConfig(config);
		}
	});
	Ext.override(Ext.grid.GridPanel,{
		addColumn: function(field, column, colIndex){
			console.log("addColumn 2 ");
			if(!column){
				if(field.dataIndex){
					column = field;
					field = field.dataIndex;
				} else{
					column = field.name || field;
				}
			}
			this.store.addField(field);
			return this.colModel.addColumn(column, colIndex);
		},
		removeColumn: function(name, colIndex){
			this.store.removeField(name);
			if(typeof colIndex != 'number'){
				colIndex = this.colModel.findColumnIndex(name);
			}
			if(colIndex >= 0){
				this.colModel.removeColumn(colIndex);
			}
		}
	});
	
	
	function getFormPropsPanel(){
		var fieldTypeCombo = new Ext.form.ComboBox({
			store: new Ext.data.ArrayStore({
				id: 0,
				fields: [
					'displayText'
				],
				data: [['text'], ['number'],['date']]
			}),
			displayField: 'displayText',
			typeAhead: true,
			mode: 'local',
			name:'field_type',
			id:'field_type',
			editable:false,
			value:'text',
			triggerAction: 'all',
			emptyText:'Select data type...',
			selectOnFocus:true
		});

		
		var fieldPropsPanel = new Ext.FormPanel({
        labelWidth: 75, // label settings here cascade unless overridden
        //url:'save-form.php',
        frame:true,
        name:'field_prop_panel',
        id:'field_prop_panel',
        bodyStyle:'padding:5px 5px 0',
        width: 350,
		autoHeight:true,
        defaults: {width: 230},
        defaultType: 'textfield',
        items: [{
                fieldLabel: 'Field Name',
                name: 'field_name',
                id: 'field_name',
				//value:field_name,
                allowBlank:false
            },
				fieldTypeCombo
			]
		});
	
		return fieldPropsPanel;
	
	}

	function getDesignViewGrid(grid_store){
		var designGrid = new Ext.grid.EditorGridPanel({
			store: grid_store,
			columns:grid_cols ,
			stripeRows: true,
			//autoHeight:true,
			height:400,
			width: 650,
			title: 'Grid 1',
			tbar: [
			{
				text: 'Add Column',
				iconCls: 'silk-add',
				handler: showAddColWindow,
				scope: this
			},{
				text: 'Add Row',
				iconCls: 'silk-add',
				handler: addRow,
				scope: this
			}, '-',
			{
				text: 'Save',
				cls: 'x-btn-text-icon',
				icon: 'images/save_all_16.png',		
				handler: saveData
			},
			{
				text: 'Delete Row',
				iconCls: 'silk-delete',
				handler: addRow,
				scope: this
			},
			'->',
			{
				text: 'Help',
				iconCls: 'silk-info',
				handler: showHelp,
				scope: this
			}
			]
		});
		return designGrid;
	}
	
	
	
	function getGridViewGrid(grid_store){
		var gridView = new Ext.grid.EditorGridPanel({
			store: grid_store,
			columns:grid_cols ,
			stripeRows: true,
			//autoHeight:true,
			height:400,
			width: 650,
			title: 'Grid 1- grid view',
			tbar: [
			{
				text: 'Add Row',
				iconCls: 'silk-add',
				handler: addRow,
				scope: this
			}, '-',
			{
				text: 'Save',
				cls: 'x-btn-text-icon',
				icon: 'images/save_all_16.png',		
				handler: saveData
			},
			{
				text: 'Delete Row',
				iconCls: 'silk-delete',
				handler: addRow,
				scope: this
			},
			'->',
			{
				text: 'Help',
				iconCls: 'silk-info',
				handler: showHelp,
				scope: this
			}
			
			]
		});
		return gridView;
	}

	function showHelp(){

		//Ext.MessageBox.show('Help system','Not yet implemented.".');
		//alert("Not yet implemented.");
	}
	
	
	
	function showGridPropsWindow(){
	
        if(!gridPropsWindow){
			
			var gridPropsPanel = new Ext.FormPanel({
				labelWidth: 75, // label settings here cascade unless overridden
				frame:true,
				name:'grid_prop_panel',
				id:'grid_prop_panel',
				bodyStyle:'padding:5px 5px 0',
				width: 350,
				autoHeight:true,
				defaults: {width: 230},
				defaultType: 'textfield',
				items: [{
						fieldLabel: 'Table Name',
						name: 'grid_name',
						id: 'grid_name',
						//value:field_name,
						allowBlank:false
					}
					]
				});
			
			
            gridPropsWindow = new Ext.Window({
                //applyTo:'hello-win',
                layout:'fit',
				width: 350,
                //height:300,
				autoHeight:true,
                closeAction:'hide',
                plain: true,
				title: 'Field properties',
                items: gridPropsPanel,

                buttons: [{
                    text:'Ok',
                    handler: function(){
					
                    }
                },{
                    text: 'Cancel',
                    handler: function(){
                        gridPropsWindow.hide();
                    }
                }]
            });
        }
		
		//refreshing current row count
		//TODO: add this on some sort of show event or something
		var field_name="Field_"+col_count;
		//gridPropsWindow.findById('field_prop_panel').getForm().findField('field_name').setValue(field_name);
        gridPropsWindow.show();
	
	
	
	}

	
	
	function showAddColWindow(){
	
        if(!fieldPropsWindow){
			var fieldPropsPanel=getFormPropsPanel();
			
            fieldPropsWindow = new Ext.Window({
                //applyTo:'hello-win',
                layout:'fit',
				width: 350,
                //height:300,
				autoHeight:true,
                closeAction:'hide',
                plain: true,
				title: 'Field properties',
                items: fieldPropsPanel,

                buttons: [{
                    text:'Ok',
                    handler: function(){
						var grid_field_name=fieldPropsPanel.getForm().findField('field_name').getValue();
						var grid_field_type=fieldPropsPanel.getForm().findField('field_type').getValue();
						
						var formData=fieldPropsPanel.getForm().getValues();
						fieldPropsWindow.hide();
						addCol(grid_field_name,grid_field_type);
                    }
                },{
                    text: 'Cancel',
                    handler: function(){
                        fieldPropsWindow.hide();
                    }
                }]
            });
        }
		
		//refreshing current row count
		//TODO: add this on some sort of show event or something
		var field_name="Field_"+col_count;
		fieldPropsWindow.findById('field_prop_panel').getForm().findField('field_name').setValue(field_name);
        fieldPropsWindow.show();
	
		//return fieldPropsWindow;
	}

	function getFieldEditor(field_type){
		var grid_field;
		if (field_type=="text") grid_field=new Ext.form.TextField();
		else if (field_type=="date") grid_field=new Ext.form.DateField();
		else if (field_type=="number") grid_field=new Ext.form.NumberField();
		
		return grid_field;
	
	}

	
	function addCol(field_header, field_type,field_id, start_edit){
		var grid_field;
		
		if ( start_edit === undefined ) {
		start_edit = true;
		}
		
		grid_field=getFieldEditor(field_type);

		if (field_id==undefined) 
			field_id="field_"+col_count;
			
		gridPanel.addColumn({name: field_id}, {header: field_header,editor: grid_field, dataIndex: field_id});
		if (start_edit && gridPanel.getStore().getCount()<1) addRow();
		
		storeFields.push({name:field_id,type:field_type});
		
		gridPanel.stopEditing();
		gridPanel.doLayout();
		
		//FIXME: removed for now
		if (start_edit) gridPanel.startEditing(gridPanel.getStore().getCount()-1, col_count-1);
		//gridPanel.startEditing(0, 0);
		col_count=col_count+1;
		
	}
	
	
	function addRow(){
		var u = new store.recordType({table_id:tableId});
		
		gridPanel.stopEditing();
		store.insert(gridPanel.getStore().getCount(), u);
		gridPanel.startEditing(gridPanel.getStore().getCount()-1, 3);
		
	}
	
	var dataViewType=new Ext.menu.Menu({
		text:'Design view',
		cls: 'x-btn-text-icon',
		icon: 'images/spanner_48.png',		
		
		items: [
		{
			text: 'Design View'
		},
		{
			text: 'Grid View'
		}
		]
	});
	
	//var menu = new Ext.menu.Menu({});
	
	function onGridColumnSave(grid_to_save){
		console.log("start saving grid data");
		
		//TODO: write unit tests for this option
		var colModel=grid_to_save.getColumnModel();
		
		var storeData=grid_to_save.getStore().fields;
		
		var columnConfigs=new Array();
		
		for (var i=0;i<colModel.getColumnCount();i++){
			var colData={};
			colData['id']=0;//colModel.getColumnId(i);
			colData['header']=colModel.getColumnHeader(i);
			colData['col_width']=colModel.getColumnWidth(i);
			colData['data_index']=colModel.getDataIndex(i);
			colData['field_type']=storeData.items[i].type.type;
			//TODO: setting additional data for columnd data
			
			columnConfigs.push(colData);
			
		}
		
		var coldDataJSON=Ext.encode(columnConfigs);
		//console.log("JSON is "+coldDataJSON+" initial data is "+columnConfigs[0]['header'] );
		
		//sending columnd data to server
		var success_funct=function(response, opts){
		
		};
		
		var grid_data=Ext.encode({id:1,grid_name:'my grid'});

		var grid_params={columns:coldDataJSON, grid_action:'set_table_data', grid_data:grid_data};
		
		sendToServer(grid_params,success_funct,null);
		
		return true;
	}

	
	function changeTable(table_id){
		console.log("changeTable table id is "+table_id);
	
		//check if there is unsaved changes
		
		// get field config from server and apply to grid
		// get data from server and apply to grid
	
	
	}
	
	
	function newTable(){
		console.log("new table function");
	}
	
	function startPage(){
		console.log("start page function");
	}
	
	

	function getTableProperties(table_id){
	
	}

	function getTableFields(table_id){
		var grid_params={grid_action:'get_table_fields',table_id:table_id};
		
		var success_funct=function(response, opts){
			var responseJSON = Ext.decode(response.responseText);
				
				if(responseJSON['success']){
					var data=responseJSON['data'];
					
					grid_cols=[{name:'id',dataIndex:'id',header:'id'},{name:'table_id',dataIndex:'table_id', defaultValue:table_id,header:'table_id'},{name:'uid',dataIndex:'uid',header:'uid'} ];
					storeFields=[{name:'id'},{name:'table_id', defaultValue:table_id},{name:'uid'} ];
					
					//grid_field=getFieldEditor(field_type);

					
					for(var prop in data) {
						if(data.hasOwnProperty(prop)){
							//console.log("col data is "+data[prop]['header']+" id " +data[prop]['id']);
							grid_cols.push({name:data[prop]['id'], dataIndex:data[prop]['id'], editor:getFieldEditor(data[prop]['field_type']), header:data[prop]['header'] });
							storeFields.push({name:data[prop]['id'], type: data[prop]['field_type']});
						}	
					}
					
					//storeFields=[{name:'4e2b228b73f07'}];
					setJsonStore(table_id);	
					store.load({callback:function(){console.log("new count is add "+store.getCount()); onGridViewClick();}});
					
					
				 
				}
				else {
					//no valid data
					return;
				}
		};
		
		sendToServer(grid_params,success_funct,null);
	}
	
	function getTableData(table_id){
		store.load();
	}
	



	function getTableList(){
		var success_funct=function(response, opts){
			var responseJSON = Ext.decode(response.responseText);
				
				if(responseJSON['success']){
					var gridList=responseJSON['data'];
					
					for(var prop in gridList) {
						if(gridList.hasOwnProperty(prop)){
							//console.log(gridList[prop]['table_name']);
							tableListMenu.add({
								text: gridList[prop]['table_name'] ,
								id: gridList[prop]['id'] ,
								width: 'auto',

								tooltip: gridList[prop]['table_name']
							});
						}	
					}
					
/*					
					for(var i=0; i<gridList.length; i++) {
						console.log("table name is "+ gridList[i]['table_name']);
						tableListMenu.add({
							text: gridList[i]['table_name'] ,
							width: 'auto',
							tooltip: gridList[i]['table_name']
						});
					}
*/					
					tb.doLayout();
				
				}
				else {
					//no valid data
					return;
				}
				

			
		};
		
		var grid_params={grid_action:'get_table_list'};
		sendToServer(grid_params,success_funct,null);
	
	}
	
	function sendToServer(grid_params,success_funct, failure_funct){
		Ext.Ajax.request
		({
			url:'view.php',
			method:'POST',
			params:grid_params, //{columns:data_to_send, grid_action:grid_action},
			success:success_funct
			
		});
	
	}

	
	function onGridProperties(){
	
	}

	function saveData(){
		//saving grid configuration, if in design mode
		if (gridMode=="desing")
			onGridColumnSave(gridPanel);
		//saving grid data
		
		saveJsonStore();
	}
	
	function saveJsonStore(){
		console.log("start saveJsonStore");
		store.save();
	}

	
	
	
	

	function onDesignViewClick(){
		changeView("design");
		gridMode="design";
	}
		
	function onGridViewClick(){
		changeView("grid");
		gridMode="view";
	}
	
	function changeView(view_type){
		gridPanel.stopEditing();

		var button_text="View Type";
		if (view_type=="grid"){
			button_text="Grid view";
			gridPanel=getGridViewGrid(store);
		}
		else if (view_type=="design"){
			button_text="Design view";
			gridPanel=getDesignViewGrid(store);
		}
		
		Ext.getCmp('grid_views').setText(button_text);
		
		formPanel.removeAll();
		formPanel.add(gridPanel);
		formPanel.doLayout();
	}
	
	
    tb.add({
			name:'grid_views',
			id:'grid_views',
            text: 'Design view',
			cls: 'x-btn-text-icon',
			icon: 'images/appointment-new.png',		
			scale: 'large',

            menu: {
                xtype: 'menu',
                plain: true,
                items: [
					{
						text:'Desgin view',
                        iconCls: 'add',
                        width: 'auto',
                        handler: onDesignViewClick,
                        tooltip: 'Use this view when adding new columns and for all looks'
                    },
					{
						text:'Grid view',
                        iconCls: 'add',
                        width: 'auto',
                        handler: onGridViewClick,
						tooltip: 'Basic view for data inserting, editing and deleting'
						
                    },
					
					
					]
                }
            }
        
		, {
        text: 'Save',
		cls: 'x-btn-text-icon',
		icon: 'images/save_all.png',		
		scale: 'large',
		handler: saveData,
		iconCls: 'save',
		tooltip: 'Save all data and configuration'
		},
		{
        text: 'Properties',
		handler: showGridPropsWindow,
		cls: 'x-btn-text-icon',
		icon: 'images/settings.png',		
		scale: 'large',
		iconCls: 'properties',
		tooltip: 'Opens grid properties window'
		},
		'->'
	
	);
	
	//adding grid list
	tableListMenu=new Ext.menu.Menu({
		plain: true,
		items: [
			{
				text:'New table...',
				iconCls: 'add',
				name:'new_table',
				id:'new_table',
				handler: startPage,
				width: 'auto'
			},
			{
				text:'Starter page',
				iconCls: 'add',
				name:'start_page',
				id:'start_page',
				handler: startPage,
				width: 'auto'
			}
			]
	});
	
	
    tb.add({
			name:'grid_list',
			id:'grid_list',
            text: 'My tables',
            iconCls: 'user',
			cls: 'x-btn-text-icon',
			icon: 'images/folder_blue.png',		
			scale: 'large',
            menu: tableListMenu
			
	});
		
		
	function onTableListClick(local_menu,menuItem, e){
		table_id=menuItem.getItemId();
		
		
		//TODO: prevent continuing event from handlers
		// if this is not table names, do nothing
		if (table_id=="start_page" || table_id=="new_table" ) 
			return;
		else
			tableId=table_id;
		
		
	
		//console.log("onTableListClick "+ menuItem.getItemId());
		
		//reseting grid
		//TODO: move it to another function
		
/*		
		var store_params={grid_action:'get_table_data',table_id:tableId};
		
		//grid_cols=[];
		store = new Ext.data.JsonStore({
				url:  'view.php',
				root: 'data',
				idProperty: 'id',
				baseParams:	store_params,
				autoLoad:false,
				
				//fields: [{name: 'id'}]
				//fields: [{name: 'id'},{name: '4e2b228b73f07'}]
				fields: []
			});	
		grid_cols=[];
*/		
		
		getTableFields(tableId);

		
		//getTableData(tableId);

		
	} 	
	
	//setting on click event for table list
	tableListMenu.on('click', onTableListClick);
	
	gridPanel=getDesignViewGrid(store);
	getTableList();	
	
	
	formPanel=new Ext.form.FormPanel({frame:false,width:650});
	formPanel.add(gridPanel);
	
	
	
	
	
	formPanel.render('main_grid');
	tb.render('main_toolbar');

	
	
});

