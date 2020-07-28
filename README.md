[![License](https://img.shields.io/badge/license-MIT_license-blue.svg)](https://raw.githubusercontent.com/dblume/md-reader/master/LICENSE.txt)
![PHP5.3](https://img.shields.io/badge/php-%3E=5.3-blue.svg)

## Render Markdown in a Browser from the Command-line

With this toolset, you can issue a command from the terminal to render markdown
in a browser window like so:

    md README.md

## Installation

There are two parts to this tool. The server side accepts requests with Markdown
data and responds with its rendered HTML. The client side is a command that sends
the request with Markdown data and opens a browser with the HTML.

The server side is already implemented at [md.dlma.com](https://md.dlma.com).
You just need to add the client snippet.

### At the local client

Copy the following text and add it to your .bashrc:

    md() {
        declare -r sys_name=$(uname -s)
        if [[ $sys_name == Darwin* ]]; then
            declare -r T=$(mktemp $TMPDIR$(uuidgen).html)
            curl -s -X POST --data-binary @"$1" https://md.dlma.com/ > $T
            open $T
        elif [[ $sys_name == CYGWIN* ]]; then
            declare -r T=$(mktemp --suffix=.html)
            curl -s -X POST --data-binary @"$1" https://md.dlma.com/ > $T
            cygstart $T
        else
            declare -r T=$(mktemp --suffix=.html)
            curl -s -X POST --data-binary @"$1" https://md.dlma.com/ > $T
            xdg-open $T
            echo "rm \"$T\" >/dev/null 2>&1" | at now + 2 minutes
            if [[ -z "${WSL_DISTRO_NAME}" ]]; then
                xdg-open $T
                echo "rm \"$T\" >/dev/null 2>&1" | at now + 2 minutes
            else
                # Set BROWSER to your web browser's path
                "$BROWSER" $(realpath --relative-to=$PWD $T)
            fi
        fi
    }

And then you have an `md` command to view the document in a browser, which you can use like:

    md README.md

### At the server

My implementation of this Markdown tool relies on
[a small change to Parsedown](https://github.com/dblume/parsedown/commit/b9409c58075b74119f7626824fbf9585e9e59633#diff-ed0d3da57330712681e64f838087ea47).
The change allows HTML entity decoding to work. E.g., &amp;theta; becomes &theta;.

At your web server, make a directory that has [Parsedown.php (from the dblume fork)](https://github.com/dblume/parsedown)
and index.php from this repo.

## Is it any good?

[Yes](https://news.ycombinator.com/item?id=3067434).

## Licence

This software uses the [MIT license](https://raw.githubusercontent.com/dblume/md-reader/master/LICENSE.txt)
