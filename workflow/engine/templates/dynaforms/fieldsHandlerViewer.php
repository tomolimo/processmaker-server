<?php 
/** 
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

 /*
  * @Author Erik Amaru Ortiz <erik@colosa.com>
  * @Date Aug 26th, 2009 
  */
  
?>
<style>
body {
 overflow:hidden;
}

#loadPage{
  position: absolute;
  top: 200px;
  left: 200px;
}

.overlay{
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  width: 101%;
  height: 100%;
  background: #fff;
  z-index:1001;
  padding: 0px;
}

.modal {
  display: block;
  position: absolute;
  top: 25%;
  left: 42%;
  background: #000;
  padding: 0px;
  z-index:1002;
  overflow: hidden;
  border: solid 1px #808080;
  border-width: 1px 0px;
}

.progress {
    display: block;
    position: absolute;
    padding: 2px 3px;
}

.container
{

}
.header
{
  background: url(/images/onmouseSilver.jpg) #ECECEC repeat-x 0px 0px;
  border-color: #808080 #808080 #ccc;
  border-style: solid;
  border-width: 0px 1px 1px;
  padding: 0px 10px;
  color: #000000;
  font-size: 9pt;
  font-weight: bold;
  line-height: 1.9;
  font-family: arial,helvetica,clean,sans-serif;
}

.body
{
  background-color: #f2f2f2;
  border-color: #808080;
  border-style: solid;
  border-width: 0px 1px;
  padding: 10px;
}
</style>

<div id="fade" class="overlay">

</div>
<div class="modal" id="light">
  <div class="header"><?php echo G::LoadTranslation('ID_LOADING')?></div>
  <div class="body">
    <img src="/images/activity.gif" />
  </div>
</div>

<iframe name="dynaframe" id="dynaframe" src ="fieldsHandlerViewer" width="100%" height="400" frameborder="0">
  <p>Your browser does not support iframes.</p>
</iframe>
