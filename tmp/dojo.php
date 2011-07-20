<html>
<head>

<style type="text/css">
    @import "http://ajax.googleapis.com/ajax/libs/dojo/1.6/dojox/grid/resources/Grid.css";
    @import "http://ajax.googleapis.com/ajax/libs/dojo/1.6/dojox/grid/resources/claroGrid.css";
    .dojoxGrid table { margin: 0; } html, body { width: 100%; height: 100%;
    margin: 0; }
</style>

<script src="http://ajax.googleapis.com/ajax/libs/dojo/1.6/dojo/dojo.xd.js"></script>

<script type="text/javascript">

	dojo.require("dojo.data.ItemFileWriteStore")
	dojo.require("dojox.grid.cells.dijit");
	dojo.require("dojox.grid.cells");
    dojo.require("dojox.grid.DataGrid");
    dojo.require("dojox.data.CsvStore");

	
	
      function setupTable() {

        var attCodeData = {label:'attribute',
                           identifier: 'id',
                            items: [
                                { id:'itemOneId',
                                  alias:'itemOneAlias',
                                  description:'itemOneDescription',
                                  attribute:'itemOneAttribute'
                                },
                                { id:'itemTwoId',
                                  alias:'itemTwoAlias',
                                  description:'itemTwoDescription',
                                  attribute:'itemTwoAttribute'
                                },
                                { id:'itemThreeId',
                                  alias:'itemThreeAlias',
                                  description:'itemThreeDescription',
                                  attribute:'itemThreeAttribute'
                                },
                                { id:'itemFourId',
                                  alias:'itemFourAlias',
                                  description:'itemFourDescription',
                                  attribute:'itemFourAttribute'
                                },
                              ]
                            };

        attCodeStore = new dojo.data.ItemFileWriteStore({data:attCodeData})

        console.log(attCodeStore);
        console.log(attCodeData);

        containerGrid = new dojox.grid.DataGrid({
                      store: attCodeStore,
                      clientSort: true,
                      autoHeight: true,
                      structure: [
                          {field: 'attribute', width: '100px', name: 'Attribute'},
                          {field: 'alias', width: '100px', name: 'Alias'},
                          {field: 'description', width: '200px', name: 'Description'}
                      ]
                  });

		var theStructure = containerGrid.structure;
		theStructure[1].editable = true;
		theStructure[2].editable = true;
		containerGrid.setStructure(theStructure);
		
		theStructure.push({field: 'description2', width: '100px', name: 'Description 2'});
		
				  
        dojo.byId("gridContainer4").appendChild(containerGrid.domNode);
        containerGrid.startup();
      }
	
    dojo.addOnLoad(function() {
	
		setupTable();
		return;

/*		
        // our test data store for this example:
        var store4 = new dojox.data.CsvStore({
            url: 'movies.csv'
        });

        // set the layout structure:
        var layout4 = [{
            field: 'Title',
            name: 'Title of Movie',
            width: '200px'
        },
        {
            field: 'Year',
            name: 'Year',
            width: '50px'
        },
        {
            field: 'Producer',
            name: 'Producer',
            width: 'auto'
        }];

		var attCodeStore = new dojo.data.ItemFileWriteStore({data:store4});

        // create a new grid:
        var grid4 = new dojox.grid.DataGrid({
            query: {
                Title: '*'
            },
            //store: store4,
            store: attCodeStore,
			editable:true,
            clientSort: true,
            rowSelector: '20px',
            structure: layout4
        },
        document.createElement('div'));
		

        // append the new grid to the div "gridContainer4":
        dojo.byId("gridContainer4").appendChild(grid4.domNode);

        // Call startup, in order to render the grid:
        grid4.startup();
*/		
    });
</script>
</head>
<body>
<div id="gridContainer4" style="width: 100%; height: 100%;">
</div>



</body>

</html>