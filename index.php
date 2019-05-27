<? header("Content-Security-Policy: script-src 'none'"); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Markdown Viewer</title>
        <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1">
        <style>
            * { margin: 0; }
            html, body { height: 100%; margin: 0, 0, 0, 10px; padding: 0; background: #ccc; color: black}
            .wrapper { min-height: 100%; height: auto !important; height: 100%; margin: 0 auto -1.6em; }
            #content{
                font-family: BlinkMacSystemFont,Segoe UI,Helvetica,Arial,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol;
                line-height: 1.4; background-color: #fff; color: black; font-size: 16px;
                text-align: left; padding: 5px 40px 30px 40px; max-width: 800px; margin: 0 auto; }
            h1, h2, h3, h4, h5, h6 { font-weight: 600; }
            h1, h2 { border-bottom: 1px solid #eaecef; padding-bottom: .3em; }
            h1 { font-size: 2em; margin: .3em 0 .67em 0; }
            h2 { font-size: 1.5em; margin-top: 18px; margin-bottom: 16px; }
            h3 { font-size: 1.25em; margin-top: 16px; margin-bottom: 12px; }
            h4 { font-size: 1.18em; margin-top: 12px; margin-bottom: 10px; }
            h5 { font-size: 1.10em; margin-top: 10px; margin-bottom: 8px; }
            h6 { font-size: 1.00em; margin-top: 8px; margin-bottom: 8px; color: #666; }
            p { padding-bottom: .5em; }
            table { margin-top: 8px; border-collapse: collapse; border-spacing: 0; border: 1px solid lightgrey; }
            table th { background-color: #EEE; border: 1px solid lightgrey; padding: 6px 13px; }
            table td { border: 1px solid lightgrey; padding: 6px 13px; }
            code, pre, tt { font-family: SFMono-Regular,Consolas,Liberation Mono,Menlo,Courier,monospace; font-size: 85%;
                        background-color: #F0F0F0; border-radius: 3px; padding: .2em .4em; }
            pre { margin-left: 20px;  margin-top: 12px; margin-bottom:20px }
            blockquote { border-left: .25em solid #dfe2e5; color: #6a737d; padding: 0 1em; margin: 16px 0; }
            hr { background-color: lightgrey; border: 0; height: .25em; margin-top: 14px; margin-bottom:20px }
            div.footer {
                font-family: HelveticaNeue-Bold, "Helvetica Neue", Arial, sans-serif;
                font-weight: bold; text-align: center; line-height: 1.2em; color: #606060;
                font-size: 8pt; width: 98% }
            div.push, div.footer { height: 1.6em; }
        </style>
    </head>
    <body>
     <div class="wrapper">
      <div id="content">


<?
require 'Parsedown.php';
$Parsedown = new Parsedown();
$Parsedown->setSafeMode(true);
$md = file_get_contents('php://input');
if (strlen($md) < 2) {
    echo <<<EOL
<h1>Welcome</h1><p>Come across a Markdown document while on the command line?
That's too bad, because it'd look better rendered in a browser. Here, put this in your .bashrc:</p>
<pre>
md() {
    declare -r sys_name=$(uname -s)
    if [[ \$sys_name == Darwin* ]]; then
        declare -r T=$(mktemp \$TMPDIR$(uuidgen).html)
        curl -s -X POST --data-binary @"$1" https://md.dlma.com/ > \$T
        open \$T
    elif [[ \$sys_name == CYGWIN* ]]; then
        declare -r T=$(mktemp --suffix=.html)
        curl -s -X POST --data-binary @"$1" https://md.dlma.com/ > \$T
        cygstart \$T
    else
        declare -r T=$(mktemp --suffix=.html)
        curl -s -X POST --data-binary @"$1" https://md.dlma.com/ > \$T
        xdg-open \$T
        echo "rm \"\$T\" >/dev/null 2>&1" | at now + 2 minutes
    fi
}
</pre>
</p>
<p>And then you have an <tt>md</tt> command to view the document in a browser, which you can use like:<br />
<pre>
md README.md
</pre>
</p>
EOL;
} else {
    echo $Parsedown->text(file_get_contents('php://input'));
}
?>
      </div>
      <div class="push"></div>
     </div>
     <div class="footer"><p style="color: gray;">&copy; 2019 <a href="http://dblu.me">David Blume</a>. <a href="https://md.dlma.com/">md.dlma.com</a> doesn't store content. Markdown you send is returned as HTML. Thanks to <a href="https://github.com/erusev/parsedown">Parsedown</a>!</p></div>
    </body>
</html>

