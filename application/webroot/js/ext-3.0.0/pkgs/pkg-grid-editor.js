/*
 * Ext JS Library 3.0.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
Ext.grid.CellSelectionModel=function(a){Ext.apply(this,a);this.selection=null;this.addEvents("beforecellselect","cellselect","selectionchange");Ext.grid.CellSelectionModel.superclass.constructor.call(this)};Ext.extend(Ext.grid.CellSelectionModel,Ext.grid.AbstractSelectionModel,{initEvents:function(){this.grid.on("cellmousedown",this.handleMouseDown,this);this.grid.getGridEl().on(Ext.EventManager.useKeydown?"keydown":"keypress",this.handleKeyDown,this);var a=this.grid.view;a.on("refresh",this.onViewChange,this);a.on("rowupdated",this.onRowUpdated,this);a.on("beforerowremoved",this.clearSelections,this);a.on("beforerowsinserted",this.clearSelections,this);if(this.grid.isEditor){this.grid.on("beforeedit",this.beforeEdit,this)}},beforeEdit:function(a){this.select(a.row,a.column,false,true,a.record)},onRowUpdated:function(a,b,c){if(this.selection&&this.selection.record==c){a.onCellSelect(b,this.selection.cell[1])}},onViewChange:function(){this.clearSelections(true)},getSelectedCell:function(){return this.selection?this.selection.cell:null},clearSelections:function(b){var a=this.selection;if(a){if(b!==true){this.grid.view.onCellDeselect(a.cell[0],a.cell[1])}this.selection=null;this.fireEvent("selectionchange",this,null)}},hasSelection:function(){return this.selection?true:false},handleMouseDown:function(b,d,a,c){if(c.button!==0||this.isLocked()){return}this.select(d,a)},select:function(f,c,b,e,d){if(this.fireEvent("beforecellselect",this,f,c)!==false){this.clearSelections();d=d||this.grid.store.getAt(f);this.selection={record:d,cell:[f,c]};if(!b){var a=this.grid.getView();a.onCellSelect(f,c);if(e!==true){a.focusCell(f,c)}}this.fireEvent("cellselect",this,f,c);this.fireEvent("selectionchange",this,this.selection)}},isSelectable:function(c,b,a){return !a.isHidden(b)},handleKeyDown:function(i){if(!i.isNavKeyPress()){return}var h=this.grid,n=this.selection;if(!n){i.stopEvent();var m=h.walkCells(0,0,1,this.isSelectable,this);if(m){this.select(m[0],m[1])}return}var b=this;var l=function(g,c,e){return h.walkCells(g,c,e,b.isSelectable,b)};var d=i.getKey(),a=n.cell[0],j=n.cell[1];var f;switch(d){case i.TAB:if(i.shiftKey){f=l(a,j-1,-1)}else{f=l(a,j+1,1)}break;case i.DOWN:f=l(a+1,j,1);break;case i.UP:f=l(a-1,j,-1);break;case i.RIGHT:f=l(a,j+1,1);break;case i.LEFT:f=l(a,j-1,-1);break;case i.ENTER:if(h.isEditor&&!h.editing){h.startEditing(a,j);i.stopEvent();return}break}if(f){this.select(f[0],f[1]);i.stopEvent()}},acceptsNav:function(c,b,a){return !a.isHidden(b)&&a.isCellEditable(b,c)},onEditorKey:function(f,d){var b=d.getKey(),h,c=this.grid,a=c.activeEditor;if(b==d.TAB){if(d.shiftKey){h=c.walkCells(a.row,a.col-1,-1,this.acceptsNav,this)}else{h=c.walkCells(a.row,a.col+1,1,this.acceptsNav,this)}d.stopEvent()}else{if(b==d.ENTER){a.completeEdit();d.stopEvent()}else{if(b==d.ESC){d.stopEvent();a.cancelEdit()}}}if(h){c.startEditing(h[0],h[1])}}});Ext.grid.EditorGridPanel=Ext.extend(Ext.grid.GridPanel,{clicksToEdit:2,forceValidation:false,isEditor:true,detectEdit:false,autoEncode:false,trackMouseOver:false,initComponent:function(){Ext.grid.EditorGridPanel.superclass.initComponent.call(this);if(!this.selModel){this.selModel=new Ext.grid.CellSelectionModel()}this.activeEditor=null;this.addEvents("beforeedit","afteredit","validateedit")},initEvents:function(){Ext.grid.EditorGridPanel.superclass.initEvents.call(this);this.on("bodyscroll",this.stopEditing,this,[true]);this.on("columnresize",this.stopEditing,this,[true]);if(this.clicksToEdit==1){this.on("cellclick",this.onCellDblClick,this)}else{if(this.clicksToEdit=="auto"&&this.view.mainBody){this.view.mainBody.on("mousedown",this.onAutoEditClick,this)}this.on("celldblclick",this.onCellDblClick,this)}},onCellDblClick:function(b,c,a){this.startEditing(c,a)},onAutoEditClick:function(c,b){if(c.button!==0){return}var f=this.view.findRowIndex(b);var a=this.view.findCellIndex(b);if(f!==false&&a!==false){this.stopEditing();if(this.selModel.getSelectedCell){var d=this.selModel.getSelectedCell();if(d&&d[0]===f&&d[1]===a){this.startEditing(f,a)}}else{if(this.selModel.isSelected(f)){this.startEditing(f,a)}}}},onEditComplete:function(b,d,a){this.editing=false;this.activeEditor=null;b.un("specialkey",this.selModel.onEditorKey,this.selModel);var c=b.record;var g=this.colModel.getDataIndex(b.col);d=this.postEditValue(d,a,c,g);if(this.forceValidation===true||String(d)!==String(a)){var f={grid:this,record:c,field:g,originalValue:a,value:d,row:b.row,column:b.col,cancel:false};if(this.fireEvent("validateedit",f)!==false&&!f.cancel&&String(d)!==String(a)){c.set(g,f.value);delete f.cancel;this.fireEvent("afteredit",f)}}this.view.focusCell(b.row,b.col)},startEditing:function(g,b){this.stopEditing();if(this.colModel.isCellEditable(b,g)){this.view.ensureVisible(g,b,true);var c=this.store.getAt(g);var f=this.colModel.getDataIndex(b);var d={grid:this,record:c,field:f,value:c.data[f],row:g,column:b,cancel:false};if(this.fireEvent("beforeedit",d)!==false&&!d.cancel){this.editing=true;var a=this.colModel.getCellEditor(b,g);if(!a){return}if(!a.rendered){a.render(this.view.getEditorParent(a))}(function(){a.row=g;a.col=b;a.record=c;a.on("complete",this.onEditComplete,this,{single:true});a.on("specialkey",this.selModel.onEditorKey,this.selModel);this.activeEditor=a;var e=this.preEditValue(c,f);a.startEdit(this.view.getCell(g,b).firstChild,e===undefined?"":e)}).defer(50,this)}}},preEditValue:function(a,c){var b=a.data[c];return this.autoEncode&&typeof b=="string"?Ext.util.Format.htmlDecode(b):b},postEditValue:function(c,a,b,d){return this.autoEncode&&typeof c=="string"?Ext.util.Format.htmlEncode(c):c},stopEditing:function(a){if(this.activeEditor){this.activeEditor[a===true?"cancelEdit":"completeEdit"]()}this.activeEditor=null}});Ext.reg("editorgrid",Ext.grid.EditorGridPanel);Ext.grid.GridEditor=function(b,a){Ext.grid.GridEditor.superclass.constructor.call(this,b,a);b.monitorTab=false};Ext.extend(Ext.grid.GridEditor,Ext.Editor,{alignment:"tl-tl",autoSize:"width",hideEl:false,cls:"x-small-editor x-grid-editor",shim:false,shadow:false});