<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Interface Processmaker</title>
    <script type="text/javascript" src="/lib/js/jquery-1.10.2.min.js"></script>
    <link rel="stylesheet" href="/lib/pmUI/pmui.min.css">

    <script type="text/javascript" >
        jQuery(document).ready(function() {

            var tid;

            var addLoading = function (host, panel, message) {
                panel = panel || 'PMContent';
                message = message || 'Loading...';

                var divLoading = document.createElement('div');
                var span = document.createElement('span');
                var text = document.createTextNode(message);

                divLoading.setAttribute('id', 'PMGmailLoad');
                divLoading.setAttribute('style', 'position:absolute; left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url('+host+'../../../lib/img/loading.gif) 50% 50% no-repeat #f9f9f9;');

                span.setAttribute('style', ' margin-top: 50%; margin-left: 44%; position: absolute; font-weight: bold; font-size: 12px; margin-right: auto;');
                span.appendChild(text);
                divLoading.appendChild(span);
                document.getElementById(panel).appendChild(divLoading);
            };

            var removeLoading = function () {
                document.getElementById('PMGmailLoad').remove();
            };

            var resizePMDynadorm = function () {
                var iframe = document.getElementById('iframePM');
                var content = iframe.contentDocument;
                if (content != null){
                    clearInterval(tid);
                }
            };

            jQuery('.pmui-tab-ref').on('click', function(e)  {
                var currentAttrValue = jQuery(this).attr('href');

                jQuery(this).parent('li').addClass('pmui-active').siblings().removeClass('pmui-active');

                //url iframe
                currentAttrValue = jQuery(this).attr('linkPM');
                addLoading(jQuery(this).attr('PMServer'));
                $('#iframePM').attr('src', currentAttrValue);
                e.preventDefault();
            });
            $('#iframePM').load( function () {
                tid = setInterval(function(){ resizePMDynadorm() }, 500);
                removeLoading();
            }); 

            var currentAttrValue = jQuery('.pmui-tab-ref');
            addLoading(currentAttrValue[0].attributes[3].value);
            $('#iframePM').attr('src',currentAttrValue[0].attributes[2].value);
        });

        var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
        var eventer = window[eventMethod];
        var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
        eventer(messageEvent,function(e) {
            parent.parent.postMessage(e.data, 'https://mail.google.com');
        },false);
    </script>
</head>

<body>
<?php
session_start();
?>
<div class="pmui-tabpanel-tabs_container" style="display: block; height: 40px;">
    <ul class="pmui-tabpanel-tabs" style="display:block; float:left;">
        <li class="pmui pmui-tabitem pmui-active" style="left: 0px; top: 0px; width: auto; height: auto; position: relative; z-index: auto; display: inline-block;">
            <i class="pmui-tab-icon"> </i>
            <a class="pmui-tab-ref"  href="#PMCases" linkPM="<?php   echo $_SESSION['server'] .  '../../../pmGmail/lostSession.php?form=1' ?>" PMServer="<?php   echo $_SESSION['server'] ?>">
                Form
            </a>
        </li>
        <li class="pmui pmui-tabitem" style="left: 0px; top: 0px; width: auto; height: auto; position: relative; z-index: auto; display: inline-block;">
            <i class="pmui-tab-icon"> </i>
            <a class="pmui-tab-ref"  href="#PMProcessmap" linkPM="<?php   echo $_SESSION['server'] .  '../../../pmGmail/lostSession.php?processmap=1'  ?>" PMServer="<?php   echo $_SESSION['server'] ?>">
                Processmap
            </a>
        </li>
        <li class="pmui pmui-tabitem" style="left: 0px; top: 0px; width: auto; height: auto; position: relative; z-index: auto; display: inline-block;">
            <i class="pmui-tab-icon"> </i>
            <a class="pmui-tab-ref"  href="#PMUploadedDocuments" linkPM="<?php   echo $_SESSION['server'] .  '../../../pmGmail/lostSession.php?uploaded=1' ?>" PMServer="<?php   echo $_SESSION['server'] ?>">
                Uploaded
            </a>
        </li>
        <li class="pmui pmui-tabitem" style="left: 0px; top: 0px; width: auto; height: auto; position: relative; z-index: auto; display: inline-block;">
            <i class="pmui-tab-icon"> </i>
            <a class="pmui-tab-ref"  href="#PMGeneratedDocuments" linkPM="<?php   echo $_SESSION['server'] .  '../../../pmGmail/lostSession.php?generated=1' ?>" PMServer="<?php   echo $_SESSION['server'] ?>">
                Generated
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab active" id="PMContent">
            <iframe id="iframePM" src="" width="100%" height="530" style="overflow:hidden;"></iframe>
        </div>
    </div>
</div>

</body>
</html>
