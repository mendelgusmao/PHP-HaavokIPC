PHP4="/usr/bin/php4/bin/php"
PHP5="/usr/bin/php"

function test {
    $1 -v
    for filename in ./*.php; do
        echo -e "\t" `$1 -l $filename`
    done
}

if [ -x "$PHP4" ]; then test $PHP4; fi
if [ -x "$PHP5" ]; then test $PHP5; fi