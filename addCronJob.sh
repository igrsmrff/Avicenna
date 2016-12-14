var_path=`pwd`
task=$var_path'/yii sms/daily'
log=$var_path'/cron_sms_send.log 2>&1'

crontab -l > mycron
echo "00 11,18 * * *   $task >> $log" >> mycron
crontab mycron
rm mycron
