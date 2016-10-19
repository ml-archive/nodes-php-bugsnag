<?php

if (! function_exists('bugsnag_report')) {
    /**
     * Report exception to Bugsnag.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @param  \Exception|\Throwable $exception
     * @return void
     */
    function bugsnag_report($exception)
    {
        // Retrieve Bugsnag instance
        $bugsnag = app('nodes.bugsnag');

        // Report exception to Bugsnag
        if ($exception instanceof \Nodes\Exceptions\Exception) {
            $bugsnag->notifyException($exception, $exception->getMeta(), $exception->getSeverity());
        } else {
            $bugsnag->notifyException($exception, null, 'error');
        }
    }
}

if (! function_exists('leave_breadcrumb')) {
    /**
     * Leave a breadcrumb for Bugsnag
     *
     * @author Rasmus Ebbesen <re@nodes.dk>
     *
     * @param string $name
     * @param string $type
     * @param array $metaData
     * @see https://docs.bugsnag.com/platforms/php/laravel/#logging-breadcrumbs
     */
    function leave_breadcrumb($name, $type = \Bugsnag\Breadcrumbs\Breadcrumb::ERROR_TYPE, array $metaData = [])
    {
        // Retrieve bugsnag instance
        $bugsnag = app('nodes.bugsnag');

        // leave breadcrumb
        $bugsnag->leaveBreadcrumb($name, $type, $metaData);
    }
}
