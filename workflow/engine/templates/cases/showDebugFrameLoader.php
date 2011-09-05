<script>
if( typeof parent != 'undefined' ){
  if( typeof parent.parent != 'undefined' ){
    var debugPanel = parent.parent.Ext.getCmp('debugPanel');

    debugPanel.show();
    debugPanel.ownerCt.doLayout();
    debugPanel.expand();

    parent.parent.propStore.load();
    parent.parent.triggerStore.load();
  }
}
</script>