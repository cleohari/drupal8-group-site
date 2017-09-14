<?php
/**
 * @file Redis Watchdog Admin Pages
 *
 * @author Brady Owens
 * @email info@fastglass.net
 */

namespace Drupal\redis_watchdog;

use Drupal\Core\Controller\ControllerBase;

class RedisWatchDogController extends ControllerBase {

  /**
   * Redis overview page.
   *
   * @return mixed
   */
  public function redisWatchdogOverview() {

    $rows = [];
    $classes = [
      WATCHDOG_DEBUG => 'redis_watchdog-debug',
      WATCHDOG_INFO => 'redis_watchdog-info',
      WATCHDOG_NOTICE => 'redis_watchdog-notice',
      WATCHDOG_WARNING => 'redis_watchdog-warning',
      WATCHDOG_ERROR => 'redis_watchdog-error',
      WATCHDOG_CRITICAL => 'redis_watchdog-critical',
      WATCHDOG_ALERT => 'redis_watchdog-alert',
      WATCHDOG_EMERGENCY => 'redis_watchdog-emerg',
    ];

    $header = [
      '', // Icon column.
      ['data' => t('Type'), 'field' => 'w.type'],
      ['data' => t('Date'), 'field' => 'w.wid', 'sort' => 'desc'],
      t('Message'),
      ['data' => t('User'), 'field' => 'u.name'],
      ['data' => t('Operations')],
    ];
    $log = redis_watchdog_client();
    $result = $log->getRecentLogs();
    foreach ($result as $log) {
      $rows[] = [
        'data' =>
          [
            // Cells
            ['class' => 'icon'],
            t($log->type),
            format_date($log->timestamp, 'short'),
            theme('redis_watchdog_message', ['event' => $log, 'link' => TRUE]),
            theme('username', ['account' => $log]),
            filter_xss($log->link),
          ],
        // Attributes for tr
        'class' => [
          drupal_html_class('dblog-' . $log->type),
          $classes[$log->severity],
        ],
      ];
    }

    // Log type selector menu.
    $build['redis_watchdog_filter_form'] = drupal_get_form('redis_watchdog_filter_form');
    // Clear log form.
    $build['redis_watchdog_clear_log_form'] = drupal_get_form('redis_watchdog_clear_log_form');

    // Summary of log types stored and the number of items in the log.
    $build['redis_watchdog_type_count_table'] = redis_watchdog_log_type_count_table();

    if (isset($_SESSION['redis_watchdog_overview_filter']['type']) && !empty($_SESSION['redis_watchdog_overview_filter']['type'])) {
      $typeid = check_plain(array_pop($_SESSION['redis_watchdog_overview_filter']['type']));
      $build['redis_watchdog_table'] = redis_watchdog_type($typeid);
    }
    else {
      $build['redis_watchdog_table'] = [
        '#theme' => 'table',
        '#header' => $header,
        '#rows' => $rows,
        '#attributes' => ['id' => 'admin-redis_watchdog'],
        '#empty' => t('No log messages available.'),
      ];
      $build['redis_watchdog_pager'] = ['#theme' => 'pager'];
    }

    return $build;
  }

  /**
   * Form for the Event ID.
   *
   * @param string $id
   *
   * @return string
   */
  public function event(string $id) {
    $severity = watchdog_severity_levels();
    $log = redis_watchdog_client();
    $result = $log->getSingle($id);
    if ($log = $result) {
      $rows = [
        [
          ['data' => t('Type'), 'header' => TRUE],
          t($log->type),
        ],
        [
          ['data' => t('Date'), 'header' => TRUE],
          format_date($log->timestamp, 'long'),
        ],
        [
          ['data' => t('User'), 'header' => TRUE],
          theme('username', ['account' => $log]),
        ],
        [
          ['data' => t('Location'), 'header' => TRUE],
          l($log->location, $log->location),
        ],
        [
          ['data' => t('Referrer'), 'header' => TRUE],
          l($log->referer, $log->referer),
        ],
        [
          ['data' => t('Message'), 'header' => TRUE],
          theme('redis_watchdog_message', ['event' => $log]),
        ],
        [
          ['data' => t('Severity'), 'header' => TRUE],
          $severity[$log->severity],
        ],
        [
          ['data' => t('Hostname'), 'header' => TRUE],
          check_plain($log->hostname),
        ],
        [
          ['data' => t('Operations'), 'header' => TRUE],
          $log->link,
        ],
      ];
      $build['redis_watchdog_table'] = [
        '#theme' => 'table',
        '#rows' => $rows,
        '#attributes' => ['class' => ['redis_watchdog-event']],
      ];
      return $build;
    }
    else {
      return '';
    }
  }

  /**
   * Form builder for the event type reports.
   *
   * @param int $tid
   *  Type id to return.
   *
   * @param int $page
   *  Current page number.
   *
   * @return array
   */
  public function type(int $tid, int $page = 0) {
    $rows = [];
    $pagesize = 50;
    $classes = [
      WATCHDOG_DEBUG => 'redis_watchdog-debug',
      WATCHDOG_INFO => 'redis_watchdog-info',
      WATCHDOG_NOTICE => 'redis_watchdog-notice',
      WATCHDOG_WARNING => 'redis_watchdog-warning',
      WATCHDOG_ERROR => 'redis_watchdog-error',
      WATCHDOG_CRITICAL => 'redis_watchdog-critical',
      WATCHDOG_ALERT => 'redis_watchdog-alert',
      WATCHDOG_EMERGENCY => 'redis_watchdog-emerg',
    ];

    $header = [
      '', // Icon column.
      ['data' => t('Type'), 'field' => 'w.type'],
      ['data' => t('Date'), 'field' => 'w.wid', 'sort' => 'desc'],
      t('Message'),
      ['data' => t('User'), 'field' => 'u.name'],
      ['data' => t('Operations')],
    ];
    $log = redis_watchdog_client();
    // @todo pagination needed
    $result = $log->getMultipleByType($pagesize, $tid);
    foreach ($result as $log) {
      $rows[] = [
        'data' =>
          [
            // Cells
            ['class' => 'icon'],
            t($log->type),
            format_date($log->timestamp, 'short'),
            theme('redis_watchdog_message', ['event' => $log, 'link' => TRUE]),
            theme('username', ['account' => $log]),
            filter_xss($log->link),
          ],
        // Attributes for tr
        'class' => [
          drupal_html_class('dblog-' . $log->type),
          $classes[$log->severity],
        ],
      ];
    }

    // Table of log items.
    $build['redis_watchdog_table'] = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#attributes' => ['id' => 'admin-redis_watchdog'],
      '#empty' => t('No log messages available.'),
    ];
    $build['redis_watchdog_pager'] = ['#theme' => 'pager'];

    return $build;
  }

  /**
   * Menu callback; display operations for exporting logs to a CSV.
   */
  public function export() {
    $form['redis_watchdog_export'] = [
      '#type' => 'fieldset',
      '#title' => t('Download Logs'),
      '#description' => t('Click the link below to export all of the logs in Redis to a CSV file.'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
    ];
    $form['redis_watchdog_export']['clear'] = [
      '#type' => 'submit',
      '#value' => t('Download log messages'),
      '#submit' => ['redis_watchdog_export_submit'],
    ];

    return $form;
  }

  /**
   * Menu callback; display operations for exporting logs to a CSV.
   */

  public function download() {
    $prefix = variable_get('redis_watchdogprefix', '');
    if (empty($prefix)) {
      $prefix = '-';
    }
    else {
      $prefix = '-' . $prefix . '-';
    }
    redis_watchdog_download_send_headers('drupal-redis-watchdog' . $prefix . 'export.csv');
    echo redis_watchdog_csv_export();
    die();
  }

}