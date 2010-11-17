Ext.onReady(function() {

var tree = new Ext.tree.TreePanel({
    renderTo: Ext.getBody(),
    title: 'Step List',
    width: 250,
    height: 250,
    userArrows: true,
    animate: true,
    autoScroll: true,
//    dataUrl: 'casesToReviseTreeContent?APP_UID=4425000044ce3eda54f6d41019986116&DEL_INDEX=3',
    dataUrl: casesPanelUrl,
    root: {
        nodeType : 'async',
        text     : 'To Revise',
        id       : 'node-root'
    },
    listeners: {
        render: function() {
            this.getRootNode().expand();
        }
    }
})
    tree.render();
//    tree.expand();
});