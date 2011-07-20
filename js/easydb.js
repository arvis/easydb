Ext.onReady(function(){
    Ext.QuickTips.init();

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
	

    // create the data store
    var store = new Ext.data.ArrayStore({
        //fields: [{name: 'id'}]
        fields: []
    });

	var fieldPropsWindow;
	var gridPropsWindow;
	var col_count=1;
	var gridPanel;	
	var formPanel;
	
	
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
			selectOnFocus:true,
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
				handler: saveData,
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
			},
			],
		});
		return designGrid;
	}
	
	
	function showHelp(){

		//Ext.MessageBox.show('Help system','Not yet implemented.".');
		//alert("Not yet implemented.");
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
				handler: saveData,
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
			},
			
			],
		});
		return gridView;
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

	
	var grid_cols=[
/*	
            {
                id       :'id',
                header   : 'ID', 
                width    : 60, 
                sortable : true, 
				//editor: new fm.TextField({allowBlank: false}),
                dataIndex: 'id'
            }
*/			
        ];
	
	function addCol(field_header, field_type){
		var grid_field;
		
		if (field_type=="text") grid_field=new Ext.form.TextField();
		else if (field_type=="date") grid_field=new Ext.form.DateField();
		else if (field_type=="number") grid_field=new Ext.form.NumberField();
		
		
		var field_id="field_"+col_count;
		gridPanel.addColumn({name: field_id}, {header: field_header,editor: grid_field, dataIndex: field_id});
		if (gridPanel.getStore().getCount()<1) addRow();
		
		gridPanel.stopEditing();
		gridPanel.doLayout();
		
		//console.log("row id "+gridPanel.getStore().getCount()+" col "+col_count );
		gridPanel.startEditing(gridPanel.getStore().getCount()-1, col_count-1);
		//gridPanel.startEditing(0, 0);
		col_count=col_count+1;
		
	}
	
	
	function showTableList(){
	
	
	}
	
	function addRow(){
		var u = new store.recordType({});
		store.insert(gridPanel.getStore().getCount(), u);
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
	
	function onGridColumnSave(){
		console.log("start saving grid data");
		
		//TODO: write unit tests for this option
		var colModel=gridPanel.getColumnModel();
		var colCount=colModel.getColumnCount();
		
		var storeData=gridPanel.getStore().fields;
		console.log("col count is "+colModel.getColumnCount());
		
		var columnConfigs=new Array();
		
		for (var i=0;i<colModel.getColumnCount();i++){
			var colData={};
			colData['id']=colModel.getColumnId(i);
			colData['header']=colModel.getColumnHeader(i);
			colData['col_width']=colModel.getColumnWidth(i);
			colData['data_index']=colModel.getDataIndex(i);
			colData['field_type']=storeData.items[i].type.type;
			//TODO: setting additional data for columnd data
			console.log("col data is "+colData['header'] );
			
			columnConfigs.push(colData);
			
		}
		
		console.log(" initial data 2 is "+columnConfigs[0]['header'] );

/*		
		var myArray = new Array();
		myArray[0] = "Football";
		myArray[1] = "Baseball";
		myArray[2] = "Cricket";
		myArray['aaaa'] = "zebra";
		var foodJson = Ext.encode(myArray);
		console.log("json arr "+foodJson);
*/		
		
		var coldDataJSON=Ext.encode(columnConfigs);
		console.log("JSON is "+coldDataJSON+" initial data is "+columnConfigs[0]['header'] );
		
		//sending columnd data to server
	Ext.Ajax.request
	({
		url:'view.php',
		method:'POST',
			params:{columns:coldDataJSON, grid_action:'save_grid_config'},
			success:function(response, opts)
			{
				
			}
	});
	
	
	
	
	}
	
	function onGridProperties(){
	
	}

	function saveData(){
		//saving grid configuration, if in design mode
		onGridColumnSave();
		//saving grid data
		
		
	
	}
	
	
	var tb = new Ext.Toolbar({width: 650});

	function onDesignViewClick(){
		changeView("design");
	}
		
	function onGridViewClick(){
		changeView("grid");
	}
	
	function changeView(view_type){
		
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
	
    tb.add({
			name:'grid_list',
			id:'grid_list',
            text: 'My tables',
            iconCls: 'user',
			cls: 'x-btn-text-icon',
			icon: 'images/folder_blue.png',		
			scale: 'large',

            menu: {
                xtype: 'menu',
                plain: true,
                items: [
					{
						text:'Sample grid 1',
                        width: 'auto',
                        tooltip: 'Sample grid 1'
                    },
					{
						text:'Sample grid 2',
                        iconCls: 'add',
                        width: 'auto',
						tooltip: 'Sample grid 2'
                    },
					{
						text:'New table...',
                        iconCls: 'add',
                        width: 'auto',
						tooltip: 'New table'
                    },
					{
						text:'Starter page',
                        iconCls: 'add',
                        width: 'auto',
						tooltip: 'Starter page'
                    },
					]
                }
            }
			
			);
		
    // create the Grid
	
	
	gridPanel=getDesignViewGrid(store);
	// TODO: check if there is any rows in store, and if there is none, add one row 
	//addRow();
	
    // render the grid to the specified div in the page
    //addRow();
	
	formPanel=new Ext.form.FormPanel({frame:false,width:650});
	formPanel.add(gridPanel);
	
	formPanel.render('main_grid');
	tb.render('main_toolbar');

	
	
});

