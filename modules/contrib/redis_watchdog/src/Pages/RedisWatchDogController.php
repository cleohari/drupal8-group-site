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