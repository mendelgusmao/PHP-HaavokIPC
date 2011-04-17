export PHP4_BIN="/usr/local/php4/bin/php"
export PHP5_BIN="/usr/local/php4/bin/php"

for filename in ./*.php
do
PHP 4 :: `$PHP4_BIN -l $filename`
PHP 5 :: `$PHP5_BIN -l $filename`
done;