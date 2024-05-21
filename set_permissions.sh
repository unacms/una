#!/bin/bash
# set_permissions.sh
sudo chown -R www-data:www-data .
sudo find ./ -type d -exec chmod 775 {} \;
sudo find ./ -type f -exec chmod 644 {} \;

# Set execute permissions for ffmpeg.exe
chmod +x ./plugins/ffmpeg/ffmpeg.exe
chmod +x ./periodic/cron.php
chmod +x ./image_transcoder.php
# Set permissions for the specified directories
chmod 777 ./inc
chmod 777 ./cache
chmod 777 ./cache_public
chmod 777 ./logs
chmod 777 ./tmp
chmod 777 ./storage
chmod 777 ./periodic
