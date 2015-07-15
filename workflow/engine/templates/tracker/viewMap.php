<?php
$url = "../designer?prj_uid=" . $_SESSION["PROCESS"] . "&prj_readonly=true&app_uid=" . $_SESSION["APP_UID"] . "&tracker_designer=1";
?>

<script type="text/javascript">
    var winTracker;

    if ((navigator.userAgent.indexOf("MSIE") != -1) || (navigator.userAgent.indexOf("Trident") != -1)) {
        var li1 = document.getElementById("MAP");
        var a1 = li1.getElementsByTagName("a");
        a1[0].href = "javascript:;";
        a1[0].onclick = function () { winTracker = window.open("<?php echo $url; ?>", "winTracker"); return false; };

        var li2 = document.getElementById("DYNADOC");
        var a2= li2.getElementsByTagName("a");
        a2[0].onclick = function ()
        {
            if (winTracker) {
                winTracker.close();
            }
        };

        var li3 = document.getElementById("HISTORY");
        var a3 = li3.getElementsByTagName("a");
        a3[0].onclick = function ()
        {
            if (winTracker) {
                winTracker.close();
            }
        };

        var li4 = document.getElementById("MESSAGES");
        var a4 = li4.getElementsByTagName("a");
        a4[0].onclick = function ()
        {
            if (winTracker) {
                winTracker.close();
            }
        };
    } else {
        document.write("<iframe name=\"casesFrame\" id=\"casesFrame\" src=\"<?php echo $url; ?>\" width=\"99%\" height=\"768\" frameborder=\"0\">");
        document.write("<p>Your browser does not support iframes.</p>");
        document.write("</iframe>");
    }
</script>
