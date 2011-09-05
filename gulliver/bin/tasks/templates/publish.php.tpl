<?php
/**
 * publish.php
 *  

 */
  global $G_PUBLISH;
  global $G_CONTENT;
  global $G_FORM;
  global $G_TABLE;
  global $RBAC;
  if ( !is_object( $G_PUBLISH ) ) die ("Publisher object is required by this template!");
?>
<table width="100%" cellpadding="5" cellspacing="0" border="0">
<?php
  if (isset($_SESSION['G_MESSAGE_TYPE']) && isset($_SESSION['G_MESSAGE'])) {
    echo('<tr><td class="temporalMessage" align="center"><strong>' . G::capitalize($_SESSION['G_MESSAGE_TYPE']) . '</strong> : ' . $_SESSION['G_MESSAGE'] . '</td></tr>');
    unset($_SESSION['G_MESSAGE_TYPE']);
    unset($_SESSION['G_MESSAGE']);
  }
  if( is_array( $G_PUBLISH->Parts ) )
  {
    $nplim = count( $G_PUBLISH->Parts );
    for( $npcount = 0; $npcount < $nplim; $npcount++ )
    {
      print( "<tr>\n<td align=\"center\">\n" );
      if (isset($RBAC->userObj))
      	$G_PUBLISH->RenderContent( $npcount, ($RBAC->userCanAccess('WF_SHOW_XMLFORM_NAME')==1?true:false) );
      else
      	$G_PUBLISH->RenderContent( $npcount );
      print( "</td>\n</tr>\n" );
    }
  }
?>
</table>
