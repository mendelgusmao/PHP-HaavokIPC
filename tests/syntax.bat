for each %%a in (..\*.php) do @echo PHP 4
php4 -l $filename
@echo PHP 5.2.17
php52 -l $filename
@echo PHP 5.3.6
php53 -l $filename
done;