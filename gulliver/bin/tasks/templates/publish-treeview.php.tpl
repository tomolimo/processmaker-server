<?php
/**
 * publish-treeview.php
 *  
 */
  global $G_PUBLISH;
  global $G_CONTENT;
  global $G_FORM;
  global $G_TABLE;
  if ( !is_object( $G_PUBLISH ) ) die ("Publisher object is required by this template!");
?>
<table width="100%" cellpadding="5" cellspacing="0" border="0">
<?php
  if (isset($_SESSION['G_MESSAGE_TYPE']) && isset($_SESSION['G_MESSAGE'])) {
    echo('<b>' . G::capitalize($_SESSION['G_MESSAGE_TYPE']) . '</b> : ' . $_SESSION['G_MESSAGE']);
    unset($_SESSION['G_MESSAGE_TYPE']);
    unset($_SESSION['G_MESSAGE']);
  }
  if( is_array( $G_PUBLISH->Parts ) )
  {
    $nplim = count( $G_PUBLISH->Parts );
    print "<tr>";
    	print "<td width=270 valign=top>";
    	$G_PUBLISH->RenderContent( 0, false );
    	print "</td>";
    	print "<td valign=top>";
	  	  print "<table border=0>";
	  	  for( $npcount = 1; $npcount < $nplim; $npcount++ )    {
	  	    print( "<tr>\n<td align=\"left\" valign=\"top\">\n" );
	  	    $G_PUBLISH->RenderContent( $npcount, false );
	  	    print( "</td>\n</tr>\n" );
	  	  }
	  	  print "</table>";
    	print "</td>";
    print "</tr>";
  }
?>
</table>
