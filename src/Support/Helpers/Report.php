<?php
if (!function_exists('bugsnag_report')) {
    /**
     * Report exception to Bugsnag
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
