<?php

    if (strlen($_POST[phpinfo]))
    {
        phpinfo();
    }

    if (strlen($_POST[gdinfo]))
    {
        gd_info();
    }


    if (strlen($_POST[execc]))
    {
        $execc = $_POST[execc];
        echo "<b>executing: $execc </b><br>";
        $exec_name = split(" ",$execc);
        $exec_name = $exec_name[0];
        echo "<b>exist: ".file_exists($exec_name)." </b><br><br>";

        $fp = popen ( $execc, "r");
        if ( $fp ) {

            echo "<pre>";
            while(!feof($fp)) {
                echo fgets($fp, 1024);
            }
            echo "</pre>";
            //fclose($fp);

        }
    }

?>
    <center>
    <hr>

<SCRIPT language="JavaScript">

    function setVal ( name, val )
    {
        fform.elements[name].value = val;
    }


</SCRIPT>


    <form method=post name=fform>
        <input id=execc name=execc> <br>
        [<a href="javascript:void(0)"
            onClick="javascript:setVal('execc','/usr/bin/mogrify')">/usr/bin/mogrify</a>] &nbsp; | &nbsp;
        [<a href="javascript:void(0)"
            onClick="javascript:setVal('execc','/usr/local/bin/mogrify')">/usr/local/bin/mogrify </a>] &nbsp; | &nbsp;
        [<a href="javascript:void(0)"
            onClick="javascript:setVal('execc','/usr/X11R6/bin/mogrify')">/usr/X11R6/bin/mogrify </a>] &nbsp; | &nbsp;
        [<a href="javascript:void(0)"
            onClick="javascript:setVal('execc','/usr/local/X11R6/bin/mogrify')">/usr/local/X11R6/bin/mogrify</a>] <br>
        [<a href="javascript:void(0)"
            onClick="javascript:setVal('execc','/usr/bin/convert')">/usr/bin/convert</a>] &nbsp; | &nbsp;
        [<a href="javascript:void(0)"
            onClick="javascript:setVal('execc','/usr/local/bin/convert')">/usr/local/bin/convert</a>] &nbsp; | &nbsp;
        [<a href="javascript:void(0)"
            onClick="javascript:setVal('execc','/usr/X11R6/bin/convert')">/usr/X11R6/bin/convert</a>] &nbsp; | &nbsp;
        [<a href="javascript:void(0)"
            onClick="javascript:setVal('execc','/usr/local/X11R6/bin/convert')">/usr/local/X11R6/bin/convert</a>] <br>
        [<a href="javascript:void(0)"
            onClick="javascript:setVal('execc','/usr/bin/sox')"> /usr/bin/sox</a>] &nbsp; | &nbsp;
        [<a href="javascript:void(0)"
            onClick="javascript:setVal('execc','/usr/local/bin/sox')"> /usr/local/bin/sox</a>]
        <br><br>
        <input type=submit value=Enter>
    </form>
    <form method=post>
        <input type=submit name=phpinfo value="PHP Info">
    </form>
   <form method=post>
        <input type=submit name=gdinfo value="GD Info">
    </form>

    <center>
