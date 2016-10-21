<?php

namespace Nodes\Bugsnag\Exceptions;

use Bugsnag\Report;
use Exception;
use App\Exceptions\Handler as AppHandler;
use Nodes\Exceptions\Exception as NodesException;

/**
 * Class Handler.
 */
class Handler extends AppHandler
{
    /**
     * Report exception to bugsnag.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @author Morten Rugaard <moru@nodes.dk>
     * @author Rasmus Ebbesen <re@nodes.dk>
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        try {
            if ($e instanceof NodesException && $e->getReport()) {
                app('nodes.bugsnag')->notifyException($e, function(Report $report) use ($e) {
                    $report->setMetaData($e->getMeta(), true);
                    $report->setSeverity($e->getSeverity());
                });
            } elseif (! $e instanceof NodesException && $this->shouldReport($e)) {
                app('nodes.bugsnag')->notifyException($e, function(Report $report) {
                    $report->setSeverity('error');
                });
            }
        } catch (Exception $e) {
            // Do nothing
        }
    }
}
