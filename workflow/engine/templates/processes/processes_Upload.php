<html>
    <head>
        <style>
            li {list-style-type: none;margin: 2;	padding: 0;	}
            body{
                background:#fff;
            }
            #uxfiles{
                font-size:10px;border-width: 1px;  border-style: solid; border-color: #000;
                padding:2px;
                padding-left:4px;
                padding-right:4px;
                width:284px;
            }
        </style>
        <script src="/js/jquery/jquery-1.3.2.min.js" type="text/javascript"></script>
        <script src="/js/jquery/jquery-ui-1.7.2.custom.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="/js/jquery/ajaxupload.3.6.js"></script>
        <script type= "text/javascript">
            var cClient = window.parent.getBrowserClient();
            $(document).ready(function () {
                var button = $('#button1'), interval;
                new AjaxUpload(button, {
                    action: 'processes_doUpload', // I disabled uploads in this example for security reasons
                    name: 'form',
                    onSubmit: function (file, ext) {
                        if (cClient.browser != 'msie') {
                            $("#uxmsg").html('Uploading...');
                            $("#uxmsg").fadeIn(2000);
                            $('#button1').attr('disabled', true);
                        } else {
                            document.getElementById("uxfiles").innerHTML = 'Uploading...';
                        }
                        if (cClient.browser != 'msie') {
                            interval = window.setInterval(function () {
                                var text = button.text();
                                if (text.length < 13) {
                                    button.text(text + '.');
                                } else {
                                    button.text('Uploading');
                                }
                            }, 200);
                        }

                    },
                    onComplete: function (file, response) {
                        parent.xReaload();
                        if (file.length > 24) {
                            file = file.substr(0, 24) + '..';
                        }
                        resp = eval("(" + response + ")");
                        xcolor = resp.result ? 'green' : 'red';
                        if (cClient.browser != 'msie') {
                            $("#uxmsg").fadeOut(1500, function () {
                                $("#uxmsg").fadeIn(1100);
                                $("#uxmsg").html('<font color=black>' + file + '</font> <font color=' + xcolor + '>' + resp.msg + '<font>');
                            });
                        } else {
                            document.getElementById("uxfiles").innerHTML = '<font color=black>' + file + '</font> <font color=' + xcolor + '>' + resp.msg + '<font>';
                        }
                        button.text('Upload++');
                        window.clearInterval(interval);
                        document.getElementById('button1').disabled = false;
                        $('#button1').attr('disabled', false);
                    }
                });
            });
            var xclear = function () {
                if (cClient.browser != 'msie') {
                    $("#uxmsg").fadeOut(1500);
                } else {
                    $("#uxfiles").html('');
                }
            }
        </script>
    </head>
    <body>
        <table width="99%">
            <tbody>
                <tr>
                    <td valign="top">
            <li id="e" class="e">
                <div class="wrapper">
                    <table><tr>
                            <td><input type="button" id="button1" class="button" value="Browse"/></td>
                            <td><div id="uxfiles">&nbsp;<span id="uxmsg" style="display:none">Select your file</span></div> </td>
                        </tr></table>
                </div>
            </li>
        </td>
    </tr>
</tbody>
</table>

</div>
</body>
</html>















