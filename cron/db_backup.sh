#!/bin/sh
# This script backs up the ecommerce database each night
# date command to the mkdir command# resulting in creating a directory of todays date
Today="`date +%Y%m%d`"
mysqldump -umkephart_ocdev3 -pdkj32p3m mkephart_opencart  > /home/mkephart/public_html/db_backups/$Today.sql
chmod 0775 /home/mkephart/public_html/db_backups/$Today.sql
chown mkephart:mkephart  /home/mkephart/public_html/db_backups/$Today.sql