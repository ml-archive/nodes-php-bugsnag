<?php

if (!function_exists('bugsnag_report')) {
    /**
     * Report exception to Bugsnag.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     *
     * @param       $exception
     * @param array $meta
     * @param null  $severity
     *
     * @return void
     */
    function bugsnag_report($exception, $meta = [], $severity = null)
    {
        // Do not continue if release stage is not set
        if (!in_array(app()->environment(), config('nodes.bugsnag.notify_release_stages', []))) {
            return;
        }

        // Do not continue if exception is ignored
        if (in_array(get_class($exception), config('nodes.bugsnag.ignore_exceptions', []))) {
            return;
        }

        // Report exception to Bugsnag
        if ($exception instanceof \Nodes\Exceptions\Exception) {
            app('nodes.bugsnag')->notifyException($exception,
                function (\Bugsnag\Report $report) use ($exception, $meta, $severity) {
                    $report->setMetaData($exception->getMeta(), true);
                    $report->setMetaData($meta, true);

                    if (!$severity) {
                        $report->setSeverity($exception->getSeverity());
                    } else {
                        $report->setSeverity($severity);
                    }
                });
        } else {
            app('nodes.bugsnag')->notifyException($exception,
                function (\Bugsnag\Report $report) use ($meta, $severity) {
                    if (!$severity) {
                        $report->setSeverity('error');
                    } else {
                        $report->setSeverity($severity);
                    }

                    $report->setMetaData($meta, true);
                });
        }
    }
}

if (!function_exists('leave_breadcrumb')) {
    /**
     * Leave a breadcrumb for Bugsnag.
     *
     * @author Rasmus Ebbesen <re@nodes.dk>
     *
     * @param string $name
     * @param string $type
     * @param array  $metaData
     *
     * @see    https://docs.bugsnag.com/platforms/php/laravel/#logging-breadcrumbs
     */
    function leave_breadcrumb($name, $type = \Bugsnag\Breadcrumbs\Breadcrumb::ERROR_TYPE, array $metaData = [])
    {
        if (!in_array(app()->environment(), config('nodes.bugsnag.notify_release_stages', []))) {
            return;
        }

        // Retrieve bugsnag instance
        $bugsnag = app('nodes.bugsnag');

        // leave breadcrumb
        $bugsnag->leaveBreadcrumb($name, $type, $metaData);
    }
}
