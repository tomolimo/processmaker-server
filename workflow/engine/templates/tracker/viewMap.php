<?php
$urlTrackerProcessMap = "../designer?prj_uid=" . $_SESSION["PROCESS"] . "&prj_readonly=true&app_uid=" . $_SESSION["APPLICATION"] . "&tracker_designer=1";
?>

<script type="text/javascript">
    var winTracker;

    if (!(navigator.userAgent.indexOf("MSIE") != -1 || navigator.userAgent.indexOf("Trident") != -1)) {
        document.write("<iframe name=\"casesFrame\" id=\"casesFrame\" src=\"<?php echo $urlTrackerProcessMap; ?>\" width=\"99%\" height=\"768\" frameborder=\"0\">");
        document.write("<p>Your browser does not support iframes.</p>");
        document.write("</iframe>");
    }
</script>
