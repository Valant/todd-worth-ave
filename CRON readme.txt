*/2 * * * * wget -O /dev/null -o /dev/null http://52.25.37.163/wp-content/plugins/yatco/run-yatco-cron.php?action=acm_execute_task\&task=yatco_cron_recheck_vassel\&args=10

0 */12 * * * wget -O /dev/null -o /dev/null http://52.25.37.163/wp-content/plugins/yatco/run-yatco-cron.php?action=acm_execute_task\&task=yatco_cron_update_vassel

*/5 * * * * wget -O /dev/null -o /dev/null http://52.25.37.163/wp-content/plugins/yatco/run-yatco-cron.php?action=acm_execute_task\&task=salesforce_synchronize_products\&args=prod,1,20