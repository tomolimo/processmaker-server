<script>
try{
  var debugPanel = parent.Ext.getCmp('debugPanel');

  debugPanel.show();
  debugPanel.ownerCt.doLayout();
  debugPanel.expand();

  parent.propStore.load();
  parent.triggerStore.load();
} catch(e){}
</script>