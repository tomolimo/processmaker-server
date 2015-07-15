<?php
if (isset($_GET['l'])) {
    $data = (array) json_decode(base64_decode($_GET['l']));
}
?>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
    <tr>
        <td width="100%" style="height:25px"></td>
    </tr>
    <tr>
        <td width="100%" align="center">
            <table width="100%" cellspacing="0" cellpadding="0" border="0" style="padding-top: 3px">
                <tbody><tr>
                    <td align="center">
                        <div align="center" style="; margin:0px;" id="publisherContent[0]">
                            <form  style="margin:0px;" enctype="multipart/form-data" method="post" class="formDefault" action="#" name="authorizeForm" id="authorizeForm">
                                <div style="width:400px; padding-left:0; padding-right:0; border-width:1;" class="borderForm">
                                    <div class="boxTop"><div class="a"></div><div class="b"></div><div class="c"></div></div>
                                    <div style="height:100%;" class="content">
                                        <table width="99%">
                                            <tbody><tr>
                                                <td valign="top">
                                                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                                        <tbody>
                                                        <tr>
                                                            <td align="" colspan="2" class="FormTitle">
                                                                <span name="form[TITLE]" id="form[TITLE]">
                                                                    <strong>Registration Success</strong>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="" colspan="2" class="FormSubTitle1">
                                                                <span name="form[TITLE]" id="form[TITLE]">

                                                                    <p>Your application <b>"<?php echo $data['name']?>"</b> was registered successfully!</p>
                                                                    <h3>Application Credentials</h3>
                                                                    <ul>
                                                                        <li><b>Client ID:</b> <?php echo $data['clientId']?></li>
                                                                        <li><b>Client Secret:</b> <?php echo $data['secret']?></li>
                                                                    </ul>
                                                                    <h4>Next Steps</h4>
                                                                    <ul>
                                                                        <li>Make authorize requests</li>
                                                                        <li>Get access tokens</li>
                                                                    </ul>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="boxBottom"><div class="a"></div><div class="b"></div><div class="c"></div></div>
                                </div>
                            </form>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

