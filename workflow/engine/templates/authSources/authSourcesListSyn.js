var synchronizeDepartmentsLDAPADV = function() {
  iGrid = Ext.getCmp('infoGrid');
  rowSelected = iGrid.getSelectionModel().getSelected();
  if (rowSelected) {
    location.href = 'authSourcesSynchronize?authUid=' + rowSelected.data.AUTH_SOURCE_UID + '&tab=synchronizeDepartments';
  }
};

var synchronizeGroupsLDAPADV = function() {
  iGrid = Ext.getCmp('infoGrid');
  rowSelected = iGrid.getSelectionModel().getSelected();
  if (rowSelected) {
    location.href = 'authSourcesSynchronize?authUid=' + rowSelected.data.AUTH_SOURCE_UID + '&tab=synchronizeGroups';
  }
};

var synchronizeDepartmentsButtonLDAPADV = new Ext.Action({
  text: _('ID_DEPARTMENTS_SYNCHRONIZE'),
  iconCls: 'ICON_DEPARTAMENTS',
  disabled: true,
  handler: synchronizeDepartmentsLDAPADV
});

var synchronizeGroupsButtonLDAPADV = new Ext.Action({
  text: _('ID_GROUPS_SYNCHRONIZE'),
  iconCls: 'ICON_GROUPS',
  disabled: true,
  handler: synchronizeGroupsLDAPADV
});

var _rowselectLDAPADV = function(sm, index, record) {
  if (record.get('AUTH_SOURCE_PROVIDER') == 'ldapAdvanced') {
    synchronizeDepartmentsButtonLDAPADV.enable();
    synchronizeGroupsButtonLDAPADV.enable();
  }
};

var _rowdeselectLDAPADV = function(sm, index, record) {
  synchronizeDepartmentsButtonLDAPADV.disable();
  synchronizeGroupsButtonLDAPADV.disable();
};

_rowselect.push(_rowselectLDAPADV);
_rowdeselect.push(_rowdeselectLDAPADV);
_pluginActionButtons.push(synchronizeDepartmentsButtonLDAPADV);
_pluginActionButtons.push(synchronizeGroupsButtonLDAPADV);