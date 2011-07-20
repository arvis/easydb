<?php


?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="/extjs4/resources/css/ext-all.css" />
    <link rel="stylesheet" type="text/css" href="/extjs4/shared/example.css" />
    <script type="text/javascript" src="/extjs4/bootstrap.js"></script>
<script>

/*

This file is part of Ext JS 4

Copyright (c) 2011 Sencha Inc

Contact:  http://www.sencha.com/contact

GNU General Public License Usage
This file may be used under the terms of the GNU General Public License version 3.0 as published by the Free Software Foundation and appearing in the file LICENSE included in the packaging of this file.  Please review the following information to ensure the GNU General Public License version 3.0 requirements will be met: http://www.gnu.org/copyleft/gpl.html.

If you are unsure which license is appropriate for your use, please contact the sales department at http://www.sencha.com/contact.

*/
Ext.require([
    'Ext.selection.CellModel',
    'Ext.grid.*',
    'Ext.form.*',
    'Ext.data.*',
    'Ext.util.*',
    'Ext.state.*'
]);

Ext.onReady(function() {
    Ext.QuickTips.init();
    
    // setup the state provider, all state information will be saved to a cookie
    Ext.state.Manager.setProvider(Ext.create('Ext.state.CookieProvider'));

    // sample static data for the store
    var myData = [
        ['3m Co',                               71.72, 0.02,  0.03,  '9/1 12:00am'],
        ['Alcoa Inc',                           29.01, -0.42,  -1.47,  '9/1 12:00am'],
        ['Wal-Mart Stores, Inc.',               45.45, 0.73,  1.63,  '9/1 12:00am']
    ];

    /**
     * Custom function used for column renderer
     * @param {Object} val
     */
    function change(val) {
        if (val > 0) {
            return '<span style="color:green;">' + val + '</span>';
        } else if (val < 0) {
            return '<span style="color:red;">' + val + '</span>';
        }
        return val;
    }

    /**
     * Custom function used for column renderer
     * @param {Object} val
     */
    function pctChange(val) {
        if (val > 0) {
            return '<span style="color:green;">' + val + '%</span>';
        } else if (val < 0) {
            return '<span style="color:red;">' + val + '%</span>';
        }
        return val;
    }

	var store_fields=[
           {name: 'company'},
           {name: 'price',      type: 'float'},
           {name: 'change',     type: 'float'},
           {name: 'pctChange',  type: 'float'},
           {name: 'lastChange', type: 'date', dateFormat: 'n/j h:ia'},
		  {name: 'dummy'}

        ];
	
    // create the data store
    var store = Ext.create('Ext.data.ArrayStore', {
        fields: store_fields,
        //data: myData
    });

	var cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
			clicksToEdit: 1
		});
	
	var col_count=1;
	
	function add_row(){
		store.insert(0,{});
		cellEditing.startEditByPosition({row: 0, column: 0});
	};
	
	var grid_cols=[
            {
                text     : 'field_1',
                //flex     : 1,
                sortable : false,
                dataIndex: 'company',
				field: {
					allowBlank: false
				}
				
            }
/*			
			,
            {
                text     : 'Price',
                width    : 75,
                sortable : true,
                renderer : 'usMoney',
                dataIndex: 'price'
            },
            {
                text     : 'Change',
                width    : 75,
                sortable : true,
                renderer : change,
                dataIndex: 'change'
            },
            {
                text     : '% Change',
                width    : 75,
                sortable : true,
                renderer : pctChange,
                dataIndex: 'pctChange'
            }
/*			
			,
            {
                text     : 'Last Updated',
                width    : 85,
                sortable : true,
                renderer : Ext.util.Format.dateRenderer('m/d/Y'),
                dataIndex: 'lastChange'
            }
*/			
        ]
	
    // create the Grid
    var grid = Ext.create('Ext.grid.Panel', {
        store: store,
        stateful: true,
        stateId: 'stateGrid',
        columns: grid_cols,
        height: 350,
        width: 600,
        title: 'Array Grid',
        renderTo: 'grid-example',
        viewConfig: {
            stripeRows: true
        },
		tbar: [{
			text: 'Add Column',
			handler : function(){
				// Create a record instance through the ModelManager
				//var r = Ext.ModelManager.create({});
				
/*			
				var col_header="column-"+col_count;
				var column = Ext.create('Ext.grid.column.Column', {text: col_header,id:col_header, flex:1,width: 75, header:col_header,sortable : true,field: { allowBlank: false, xtype: 'datefield', format: 'm/d/y',  }});	
				column.getEditor = function (record, defaultField) { return this.field };
				//column.getEditor = function (record) { return this.field };
				col_count=col_count+1;
				//grid.headerCt.insert(grid.columns.length, column);
				
/*				
				column.setEditor({
					   xtype: 'numberfield',
					   allowBlank: false,
					   allowNegative: false,
					   allowDecimals: false,
					});				
*/				
				col_count=col_count+1
				grid_cols.push({header: 'field_'+col_count, dataIndex: 'field_'+col_count, field: {xtype: 'textfield', allowBlank: false}});
				store_fields.push({name:'field_'+col_count});
				
				grid.reconfigure(store, grid_cols);
				grid.getView().refresh();

				return;

			}
		},
		{
			text:'Add row',
			handler :add_row
		}
		
		
		],
		plugins: [cellEditing]
    });
	

/*	
	var column = Ext.create('Ext.grid.column.Column', {
	text: 'aColumn',
	header: 'aColumn',
	width:200,
	field: { xtype: 'datefield', format: 'm/d/y', minValue: '01/01/06'}
	});
*/

	
});



</script>

</head>
<body>



<div id="grid-example">
</div>

</body>

</html>
