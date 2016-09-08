<?php
class EmailServer extends BaseEmailServer
{
	/**
	 * Get the evn_description column value.
	 *
	 * @return string
	 */
	public function loadDefaultAccount ()
	{
		$c = new Criteria( 'workflow' );
    	$del = DBAdapter::getStringDelimiter();

    	$c->clearSelectColumns();
    	$c->addSelectColumn( EmailServerPeer::MESS_ACCOUNT );

    	$c->add( EmailServerPeer::MESS_DEFAULT, 1 );

    	$rs = EmailServerPeer::doSelectRS( $c, Propel::getDBConnection('workflow_ro') );
    	$rs->setFetchmode( ResultSet::FETCHMODE_ASSOC );
    	$rs->next();
    	$row = $rs->getRow();
    	$response=$row;

    	return $response;
	}
}

