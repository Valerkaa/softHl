<?php
$file_path = '/var/www/hell_leads_w_usr/data/www/hell-leads.win/soft/log.txt'; // Укажите полный путь к файлу, который нужно очистить

file_put_contents($file_path, '');
?>