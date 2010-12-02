<?php
/**
 * paged-table.php
 *  
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2008 Colosa Inc.23
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * For more information, contact Colosa Inc, 2566 Le Jeune Rd., 
 * Coral Gables, FL, 33134, USA, or email info@colosa.com.
 * 
 */
  global $G_TABLE;
  global $G_CONTENT;
  global $HTTP_SESSION_VARS;
  global $HTTP_GET_VARS;
  if ( !is_object( $G_TABLE ) ) die ("Table object is required!");


  $G_TABLE->GetSource();

  //will use the templatePower class to render the table
 // G::LoadClass ('templatePower');
	$tpl = new TemplatePower( PATH_TPL . 'paged-table.html' );
	$tpl->prepare();
  $tpl->assign( "STYLE_CSS" , STYLE_CSS );
  $tpl->assign( "title" , $G_TABLE->title );

  $orderpag = $HTTP_GET_VARS['order'];
  if($orderpag=="") {
    $orcad="";
  }
  else   {
    $orcad=$orderpag;
  }

  $curpage = $HTTP_GET_VARS['page'];
  if( $curpage == "" ) $curpage = 1;
  $res=(round(($curpage)/20)*20)+1;
  if ($res>$curpage)
    $HTTP_SESSION_VARS['COMIENZO'] = $res-20;
  else
    $HTTP_SESSION_VARS['COMIENZO']=$res;

  $HTTP_SESSION_VARS['FINAL']=$HTTP_SESSION_VARS['COMIENZO']+20;

  $totpages = 0;

  $HTTP_SESSION_VARS['TP'] = $G_TABLE->TotalCount();
  $tcount = $HTTP_SESSION_VARS['TP'];
  $totpages = $HTTP_SESSION_VARS['TP'];

  $totpages = $totpages / $G_TABLE->rows_per_page;
  $totpages = round( $totpages, 0);

  if( ($totpages * $G_TABLE->rows_per_page) < $tcount ) $totpages++;
  if ($totpages == 0 ) $curpage = 0;
  $ntotwidth="0";
  for( $ncount=0 ; $ncount < $G_TABLE->ColumnCount() ; $ncount++ )
  {
    $ntotwidth += $G_TABLE->Columns[$ncount]['Width'];
  }

  $firstrow = (($curpage-1) * $G_TABLE->rows_per_page) + 1;
  $lastrow = $firstrow + $G_TABLE->rows_per_page - 1;

  $cuenta=$G_TABLE->Count();

  if( $lastrow > $cuenta ) $lastrow = $cuenta;

  for( $ncount=0 ; $ncount < $G_TABLE->ColumnCount() ; $ncount++ )
  {
  	$pa =1 ;$intPos=$ncount; $strClass = "tblHeader";
    $col = $G_TABLE->Columns[$intPos];
    $order = !($col["Type"] == "image" || $col["Type"] == "jsimglink");



	  $tpl->newBlock( "headers" );
	  $tpl->assign( "width" , 	( $col["Width"] > 0 ? " style=\"width:" . $col["Width"] . ";\"" : '' ) );
	  $tpl->assign( "header" , 	( $G_TABLE->Labels[$intPos]  ) );
    if( $G_TABLE->_ordered == true && $order )
    {
      $res = "<th ";
      if ( $col["Width"] > 0) $res .= " style=\"width:" . $col["Width"] . ";\"";
      $res .= ">";
      $res .= "<a href=\"";
      $res .= (ENABLE_ENCRYPT=='yes'?str_replace(G::encrypt('sys' . SYS_SYS, URL_KEY), SYS_SYS, G::encryptUrl(urldecode(SYS_CURRENT_URI), URL_KEY)):SYS_CURRENT_URI) . "?order=" . $G_TABLE->Columns[$intPos]['Name']."&page=".$pa."&label=true";
      $res .= "\">" . $G_TABLE->Labels[$intPos] . "</a>";
      $res .= "</th>\n";
  	  $tpl->assign( "href" , 	(ENABLE_ENCRYPT=='yes'?str_replace(G::encrypt('sys' . SYS_SYS, URL_KEY), SYS_SYS, G::encryptUrl(urldecode(SYS_CURRENT_URI), URL_KEY)):SYS_CURRENT_URI) . "?order=" . $G_TABLE->Columns[$intPos]['Name']."&page=".$pa."&label=true" );
      $Fields['headers'][] = $header;
    }
    else
    {
  	  $tpl->assign( "href" , 	'' );
      $res = "<th ";
      if ( $col["Width"] > 0) $res .= " style=\"width:" . $col["Width"] . ";\"";
      $res .= ">";
      $res .= $G_TABLE->Labels[$intPos] . "</th>\n";
    }
  }
  //end grid titles

  if ( $G_TABLE->rows_per_page == '' ) $G_TABLE->rows_per_page = 25;
  if ($cuenta >= $G_TABLE->rows_per_page)
    $lastrow = $G_TABLE->rows_per_page;
  else
    $lastrow = $cuenta;

  while ( $G_TABLE->CurRow() < $lastrow )
  {
    $G_TABLE->Read();
    $class = ( $G_TABLE->CurRow() % 2 == 0 ? "Row1" : "Row2" );

    $tpl->newBlock( "row" );
	  $tpl->assign( "class" , $class );
    for ( $ncount=0 ; $ncount < $G_TABLE->ColumnCount() ; $ncount++ )
    {
      $tpl->newBlock( "field" );
      $col = $G_TABLE->Columns[$ncount]["Align"];
  	  $tpl->assign( "align" , ( $col <> '' ? " align=\"$col\"" : 'x' ) );
  	  $tpl->assign( "value" , $G_TABLE->RenderColumn( $ncount, '', '',0, 0) );
    }
  }

  if( $curpage > 1 ) {
    $firstUrl = SYS_CURRENT_URI . '?order=' . $orcad . '&page=1';
    $prevpage = $curpage - 1;
    $prevUrl  = SYS_CURRENT_URI . '?order=' . $orcad . '&page=' . $prevpage;
    $tpl->assign( "firstUrl" , $firstUrl );
    $tpl->assign( "prevUrl" ,  $prevUrl );
    $first = "<a href=" . $firstUrl . ">&lt;&lt;</a>";
    $prev  = "<a href=" . $prevUrl . ">&lt;</a>";
  }
  else
  {
    $tpl->assign( "firstOff" , 1 );
    $tpl->assign( "prevOff" ,  1 );
  	$first = "&lt;&lt;";
  	$prev  = "&lt;";
  }


  $tpl->gotoBlock( "_ROOT" );
  if ( $totpages !=1 )  $tpl->newBlock( "paging" );
  $tpl->assign( "first" , $first );
  $tpl->assign( "prev" ,  $prev );

  $tpl->assign( "curPage" , '<b>' . G::LoadMessageXml('ID_PAGE') . ' ' . $curpage . ' ' . G::LoadMessageXml('ID_OF') . ' ' . $totpages . '&nbsp' . '</b>');

  if( $curpage < $totpages ) {
    $lastUrl = SYS_CURRENT_URI . '?order=' . $orcad . '&page=' . $totpages;
    $nextpage = $curpage + 1;
    $nextUrl  = SYS_CURRENT_URI . '?order=' . $orcad . '&page=' . $nextpage;
    $next = "<a href=" . $nextUrl . ">></a>";
    $last = "<a href=" . $lastUrl . ">>></a>";
  }
  else
  {
  	$next = ">";
  	$last = ">>";
  }
  $tpl->assign( "next" , $next );
  $tpl->assign( "last" , $last );
  $tpl->assign( "columnCount", $G_TABLE->ColumnCount() );

  if( $totpages > 1 )
  {
    // --------- codigo para colocar numeros de paginas ---------
    $ncount = 1;
    $inicio=$HTTP_SESSION_VARS['COMIENZO'];
    $fin=$HTTP_SESSION_VARS['FINAL'];
     // echo "Inicio:".$inicio." fin".$fin;

    for( $ncount = $inicio; $ncount <= $fin-1; $ncount++ )
    {
     if($ncount>=1 && $ncount<=$totpages)
     {
      if( $ncount <= $totpages ) $pagesEnum .=  " &nbsp ";
      $pagesEnum .= "<a href=\"" . SYS_CURRENT_URI . "?order=".$orcad."&page=" . $ncount . "\">";
      $pagesEnum .= $ncount;
      $pagesEnum .= "</a>";
     }
    }
    $pagesEnum .=" &nbsp ";
  $tpl->assign("pagesEnum", $pagesEnum);
  }

  if ( $lastrow == 0 ) {
    $tpl->gotoBlock( "_ROOT" );
    $tpl->newBlock(  'norecords' );
    $tpl->assign( "noRecordsFound", G::LoadMessageXml('ID_NO_RECORDS_FOUND') );
    $tpl->assign( "columnCount", $G_TABLE->ColumnCount());
  }

  if (  $lastrow > $G_TABLE->rows_per_page * 0.66 ) {
    $tpl->gotoBlock( "_ROOT" );
    $tpl->newBlock( "bottomFooter" );
    $tpl->assign( "columnCount", $G_TABLE->ColumnCount() + 1);
    $tpl->assign( "first" , $first );
    $tpl->assign( "prev" ,  $prev );
    $tpl->assign( "next" , $next );
    $tpl->assign( "last" , $last );
    $tpl->assign("pagesEnum", $pagesEnum);
  }
  $tpl->gotoBlock( "_ROOT" );
	$tpl->printToScreen();
  ?>