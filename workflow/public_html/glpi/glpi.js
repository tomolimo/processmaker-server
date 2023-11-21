/*
-------------------------------------------------------------------------
ProcessMaker plugin for GLPI
Copyright (C) 2014-2023 by Raynet SAS a company of A.Raymond Network.

https://www.araymond.com/
-------------------------------------------------------------------------

LICENSE

This file is part of ProcessMaker plugin for GLPI.

This file is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this plugin. If not, see <http://www.gnu.org/licenses/>.
--------------------------------------------------------------------------
 */

pm_glpi = {

   case_tasks_resize:  function (entries, observer) {
      // current height
      let myHtml = document.querySelector('html');
      let height = Math.ceil(parseFloat(getComputedStyle(myHtml).getPropertyValue('height')))
      if (height > 0) {
         window.parent.postMessage({
            message: 'iframeresize',
            height: height,
            glpi_data: GLPI_DATA
         }, GLPI_DATA.glpi_url);
      }
   },


   case_map_resize: function (mutationList, observer) {
      let locElt = undefined;
      if (GLPI_DATA.glpi_isbpmn) {
         locElt = document.querySelectorAll('div.pmui-pmpool')[0];
      } else {
         locElt = document.querySelectorAll('div.panel_containerWindow___processmaker')[0];
         locElt2 = document.getElementById('pm_target');
         locElt2.style.height = locElt.clientHeight + 'px';
      }
      if (locElt) {
         //debugger;
         let newHeight = undefined;
         if (GLPI_DATA.glpi_isbpmn) {
            locElt.offsetParent.style.top = 0;
            locElt.offsetParent.style.width = locElt.offsetWidth + 10 + locElt.offsetLeft + 'px';
            locElt.offsetParent.style.height = locElt.offsetHeight + locElt.offsetTop + 'px';
            newHeight = (locElt.offsetHeight < 400 ? 400 : locElt.offsetHeight) + locElt.offsetParent.offsetTop + 30;
         } else {
            newHeight = (locElt.offsetHeight < 400 ? 400 : locElt.offsetHeight);
         }
         // trick to force scrollbar to be shown
         locElt.offsetParent.style.overflow = 'visible';
         locElt.offsetParent.style.overflow = 'hidden';
         if (locElt.scrollHeight && locElt.scrollHeight > newHeight) {
            newHeight = locElt.scrollHeight;
         }
         if (window.document.body.scrollHeight > newHeight) {
            newHeight = window.document.body.scrollHeight;
         }
         newHeight = Math.ceil(newHeight);

         window.parent.postMessage({
            message: 'iframeresize',
            height: newHeight,
            glpi_data: GLPI_DATA
         }, GLPI_DATA.glpi_url);

         //if (observer) {
         //   observer.disconnect();
         //}
      }
   },


   case_history_resize: function (mutationList, observer) {
      let locElt = document.getElementsByTagName(GLPI_DATA.glpi_elttagname)[0];
      if (locElt) {
         locElt.className = '';
         //      observer.disconnect();
         let height = locElt.scrollHeight;
         height = height < 400 ? 400 : height;
         window.parent.postMessage({
            message: 'iframeresize',
            height: height,
            glpi_data: GLPI_DATA
         }, GLPI_DATA.glpi_url);

      }
   },


   case_historydynaformgridpreview_resize: function (mutationList, observer) {
      let locElt = document.querySelector('form');
      if (locElt) {
         //debugger;
         observer.disconnect();

         // observe resize of html element
         let resize_ob = new ResizeObserver(pm_glpi.case_tasks_resize);
         // start observing for resize
         resize_ob.observe(document.querySelector("form")); // html"));

         // add submit event handler to prevent a real submit when viewing a dynaform
         $('form').setOnSubmit((e) => {
            return false;
         });
      }
   }, 


   case_historydynaform_resize: function (mutationList, observer) {
      let locElt = document.querySelector('button.button_menu_ext');
      if (locElt) {
         //debugger;
         //observer.disconnect();
         let obj = document.getElementsByTagName(GLPI_DATA.glpi_elttagname)[0];
         if (obj && obj.className != '') {
            obj.className = '';
         }

         let newHeight = 400;
         window.parent.postMessage({
            message: 'iframeresize',
            height: newHeight,
            glpi_data: GLPI_DATA
         }, GLPI_DATA.glpi_url);

         if (!locElt.glpi_set) {
            locElt.glpi_set = true;
            locElt.addEventListener('mousedown', (e) => {
               //debugger;
               e.stopPropagation();
            });
            locElt.addEventListener('click', (e) => {
               e.stopPropagation();
               let PRO_UID = document.querySelector('div.x-grid3-row.x-grid3-row-selected div.x-grid3-cell-inner.x-grid3-col-PRO_UID').textContent;
               let APP_UID = document.querySelector('div.x-grid3-row.x-grid3-row-selected div.x-grid3-cell-inner.x-grid3-col-APP_UID').textContent;
               let TAS_UID = document.querySelector('div.x-grid3-row.x-grid3-row-selected div.x-grid3-cell-inner.x-grid3-col-TAS_UID').textContent;
               let DYN_UID = document.querySelector('div.x-grid3-row.x-grid3-row-selected div.x-grid3-cell-inner.x-grid3-col-DYN_UID').textContent;
               let DYN_TITLE = document.querySelector('div.x-grid3-row.x-grid3-row-selected div.x-grid3-cell-inner.x-grid3-col-4').textContent;
               // here we postMessage of this dynaform view request
               window.parent.postMessage({
                  message: 'historydynaformgridpreview',
                  PRO_UID: PRO_UID,
                  APP_UID: APP_UID,
                  TAS_UID: TAS_UID,
                  DYN_UID: DYN_UID,
                  DYN_TITLE: DYN_TITLE,
                  glpi_data: GLPI_DATA
               }, GLPI_DATA.glpi_url);
            });
         }
      }
   },


   case_changeloghistory_grid_resize: function (entries) {
      // since we are observing only a single element, so we access the first element in entries array
      // current height
      let height = entries[0].contentRect.height;
      height = height < 400 ? 400 : height;
      window.parent.postMessage({
         message: 'iframeresize',
         height: height + 24,
         glpi_data: GLPI_DATA
      }, GLPI_DATA.glpi_url);
   },


   case_changeloghistory_resize: function (mutationList, observer) {
      let locElt = document.querySelectorAll('div.x-grid-group-hd');
      if (locElt.length) {
         //debugger;
         observer.disconnect();

         // observe resize of div.x-grid3-body element
         let resize_ob = new ResizeObserver(pm_glpi.case_changeloghistory_grid_resize);
         // start observing for resize
         resize_ob.observe(document.querySelector("div.x-grid3-body"));

         // we need to colapse the elements with class .x-grid-group-hd
         let simMousedownEvent = new MouseEvent('mousedown', { 'view': window, 'bubbles': true, 'cancelable': true });
         locElt.forEach((e) => { e.dispatchEvent(simMousedownEvent) });

      }
   },


   tasks_observer: function (mutationList, observer) {

      // hide dyn_forward_assign when it is the last step in the task
      let dyn_forward_assign = document.querySelector('a[id*="dyn_forward" i][href="cases_Step?TYPE=ASSIGN_TASK&UID=-1&POSITION=10000&ACTION=ASSIGN"]');
      if (dyn_forward_assign && dyn_forward_assign.style.display != 'none') {
         dyn_forward_assign.style.display = 'none';
      }

      if (dyn_forward_assign && document.querySelector('html').postmessage.data.message == 'parentready') {
         let myForm = document.querySelector('form');
         if (myForm && !myForm.setOnSubmitDone) {
            myForm.setOnSubmitDone = true;
            $('form').setOnSubmit(pm_glpi.case_validate_form);
         }
      }

      let myForms = document.querySelectorAll('form');
      if (myForms.length) {
         let glpi_data = document.querySelector('input#glpi_data');
         if (!glpi_data) {
            // add to the forms the glpi_data as field in the form
            myForms.forEach((e) => {
               e.insertAdjacentHTML('beforeend', "<input id='sid' type='hidden' name='sid' value='" + GLPI_DATA.glpi_sid + "'></input>");
               e.insertAdjacentHTML('beforeend', "<input id='DEL_INDEX' type='hidden' name='DEL_INDEX' value='" + GLPI_DATA.glpi_del_index + "'></input>");
               e.insertAdjacentHTML('beforeend', "<input id='POSITION' type='hidden' name='POSITION' value='" + GLPI_DATA.pm_current_step_position + "'></input>");

               // add glpi_data
               e.insertAdjacentHTML('beforeend', "<input id='glpi_data' type='hidden' name='glpi_data' value='" + JSON.stringify(GLPI_DATA) + "'></input>");

               // add sid to the post url
               const addr = new URL(e.action);
               addr.searchParams.set('sid', GLPI_DATA.glpi_sid);
               e.action = addr.toString();
            });

            // observe resize of form elements
            let resize_ob = new ResizeObserver(pm_glpi.case_tasks_resize);
            resize_ob.observe(document.querySelector("html"));
         }
      }

      let append = '&sid=' + GLPI_DATA.glpi_sid +
                   '&APP_UID=' + GLPI_DATA.glpi_app_uid +
                   '&DEL_INDEX=' + GLPI_DATA.glpi_del_index +
                   '&glpi_data=' + encodeURIComponent(JSON.stringify(GLPI_DATA));

      let dyn_backward = document.querySelector('a[id*="dyn_backward" i][href*="cases_Step?TYPE="]');
      if (dyn_backward && dyn_backward.href.indexOf('&glpi_data=') == -1) {
         dyn_backward.href += append;
      }

      let dyn_forward = document.querySelector('a[id*="dyn_forward" i][href*="cases_Step?TYPE="]');
      if (dyn_forward && dyn_forward.href.indexOf('&glpi_data=') == -1) {
         dyn_forward.href += append;
      }

      // hide Next Step button, this button is displayed by Output Document form
      let next_step = document.getElementById('form[NEXT_STEP]');
      if (next_step && next_step.style.display != 'none') {
         next_step.style.display = 'none';
      }

      let cancelButton = document.getElementById('form[BTN_CANCEL]');
      if (cancelButton && cancelButton.style.display != 'none') {
         cancelButton.style.display = 'none';
         let claimButton = document.getElementById('form[BTN_CATCH]');
         if (claimButton
             && claimButton.style.display != 'none'
             && GLPI_DATA.glpi_hide_claim_button) {
            claimButton.style.display = 'none';
         }
      }

      // this is used by input document list
      let docs = document.querySelectorAll('a[href*="{skin}/cases/cases_ShowDocument?a="].fa.fa-download');
      docs.forEach((el) => {
         if (el.href.indexOf('&glpi_data=') == -1) {
            el.href += append;
         }
      });

      // this a[href] is displayed for example by Output Document form
      // normally, there is only one document link
      let outputdoc = document.getElementById('form[APP_DOC_FILENAME2]');
      if (outputdoc && outputdoc.href.indexOf('&glpi_data=') == -1) {
         outputdoc.href += append;
      }

   },


   case_tasks: function (e) {
      switch (e.data.message) {
         case 'parentready':
            let targetNode = document.querySelector('html');
            targetNode.postmessage = {};
            targetNode.postmessage.data = e.data;
            let config = { childList: true, subtree: true };
            let observer = new MutationObserver(pm_glpi.tasks_observer);
            observer.observe(targetNode, config);
            // try to do an immediat observation
            pm_glpi.tasks_observer();
            break;
         case 'dosubmitdynaform':
            // this time, the parent has saved and has validated the data, then we can submit the dynaform
            // but before, we must delete pm_glpi.case_validate_form from this.submit array
            let indexofhandler = window.getFormById($('form')[0].id).submit.indexOf(pm_glpi.case_validate_form);
            if (indexofhandler > -1) {
               window.getFormById($('form')[0].id).submit.splice(indexofhandler, 1);
            }
            $('#' + $('form')[0].id).submitForm();
            break;
      }
   },


   case_others: function (e) {
      //debugger;
      switch (e.data.message) {
         case 'parentready':
            // here we must observe DOM mutation on 'glpi_elttagname'
            let targetNode = document.querySelector(e.data.glpi_data.glpi_elttagname);
            let config = {
               childList: true,
               subtree: true
            };
            let fctname = 'case_' + e.data.glpi_data.glpi_tabtype + '_resize';
            let fn = window.pm_glpi[fctname];
            let observer = new MutationObserver(fn);
            observer.observe(targetNode, config);

            // tries to do an immediate resize.
            fn.apply(null, [null, observer]);
            break;
      }
   },


   case_message: function (e) {
      // to block any unwanted messages
      if (e.origin !== GLPI_DATA.glpi_url) {
         console.warn('pm_glpi.case_message: sender is ' + e.origin + ', when it should be ' + GLPI_DATA.glpi_url + '!');
         return;
      }

      //debugger;
      switch (e.data.glpi_data.glpi_tabtype) {
         case 'task':
            pm_glpi.case_tasks(e);
            break;
         default:
            pm_glpi.case_others(e);
            break;
      }

      window.addEventListener('click', (e) => {
         // and then we must send the data to the parent window
         window.parent.postMessage({
            message: 'click',
            glpi_data: GLPI_DATA
         }, GLPI_DATA.glpi_url);

      })
   },


   case_onload: function () {
      window.addEventListener("message", pm_glpi.case_message);

      // iframe is ready then inform parent window
      // if window.pm_glpi_action_submitform is true then it will trigger the form submit
      window.parent.postMessage({
         message: 'iframeready',
         submitform: window.pm_glpi_action_submitform ? true : false,
         glpi_data: GLPI_DATA
      }, GLPI_DATA.glpi_url);
   },


   case_validate_form: function () {
      let txt2parent = '_';
      let textareaUserRequestSumUp = document.querySelector('[id="form[UserRequestSumUp]"]');
      if (textareaUserRequestSumUp) {
         txt2parent = textareaUserRequestSumUp.value;
      }

      // and then we must send the data to the parent form
      window.parent.postMessage({
         message: 'dovalidateparentform',
         userrequestsumup: txt2parent,
         glpi_data: GLPI_DATA
      }, GLPI_DATA.glpi_url);

      // and cancel default form submit
      return false;
   }

}

window.addEventListener('load', pm_glpi.case_onload);