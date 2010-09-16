/* Manages a table of Tree type.
 * @author David Callizaya
 */
function G_Tree() {
  this.lastSelected = false;
  this.lastSelectedClassName = 'treeNode';
  var me = this;
  this.changeSign = function( element, newSign ){
    /*element must be the TR of the current node */
    var spans = element.cells[0].childNodes;//getElementsByTagName('SPAN');
    for(var r= 0 ; r<spans.length ; r++ ) {
      if(spans[r].nodeName==='SPAN') {
        if(spans[r].getAttribute('name')===newSign) {
          spans[r].style.display='';
        } else {
          spans[r].style.display='none';
        }
      }
    }
  };
  this.getRowOf=function (element) {
    //NOTE: IF (element.offsetParent==null) there is no efect.
    while(element.nodeName!='BODY') {
      if (element.getAttribute('name')) {
        if (element.getAttribute('name').substr(0,9)==='treeNode[') {
          var regexp = /^treeNode\[[^\]]+\]\[([^\]]+)\]$/;
          result = regexp.exec(element.getAttribute('name'));
          if (!(result && result.length>=2)) return false;
          //Now element is the TR of the current node.
          return element.parentNode;
        }
      }
      element = element.parentNode;
    }
    return false;
  };
  this.contract=function( element ){
    if (!(element = this.getRowOf(element))) return;
    var row = element.rowIndex;
    if ( (row+1)>= element.parentNode.rows.length ) return;
    element.parentNode.rows[row+1].style.display = 'none';
    this.changeSign( element , 'plus' );
  };

this.expand=function( element ){
    if (!(element = this.getRowOf(element))) return;
    var row = element.rowIndex;
    if ( (row+1)>= element.parentNode.rows.length ) return;
    element.parentNode.rows[row+1].style.display = '';
    this.changeSign( element , 'minus' );
  };
  this.select=function( element ){
    if (!(element = this.getRowOf(element))) return;
    if (me.lastSelected) {
     if (me.lastSelected.cells[1]) me.lastSelected.cells[1].className=me.lastSelectedClassName;
    }
    me.lastSelected = element ;
    //me.lastSelected.cells[1].style.filter='Light';
    //me.lastSelected.cells[1].filters['Light'].addAmbient(155,155,155,255);
    me.lastSelectedClassName=me.lastSelected.cells[1].className;
    me.lastSelected.cells[1].className="treeNodeSelected";
  };
  this.refresh=function( div , server ) {
    div.innerHTML = ajax_function( server ,'','' );
  };
};
var tree = new G_Tree();
