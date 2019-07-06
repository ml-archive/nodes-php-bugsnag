<?php

namespace Nodes\Bugsnag\Exceptions;

use App\Exceptions\Handler as AppHandler;
use Bugsnag\Report;
use Exception;
use Nodes\Exceptions\Exception as NodesException;

/**
 * Class Handler.
 */
class Handler extends AppHandler
{
    /**
     * Report exception to bugsnag.
     *
     * @param \Exception $e
     * @return void
     * @author Rasmus Ebbesen <re@nodes.dk>
     * @author Casper Rasmussen <cr@nodes.dk>
     * @author Morten Rugaard <moru@nodes.dk>
     */
    public function report(Exception $e)
    {
        try {
            // NodesExceptions have extra attributes
            if ($e instanceof NodesException) {

                // Check if exception is marked to be reported
                if (!$e->getReport()) {
                    return;
                }

                app('nodes.bugsnag')->notifyException($e, function (Report $report) use ($e) {
                    $report->setMetaData($e->getMeta(), true);
                    $report->setSeverity($e->getSeverity());
                });

                return;
            }

            // Check if the exception should be reported at all!
            if (!$this->shouldReport($e)) {
                return;
            }

            app('nodes.bugsnag')->notifyException($e, function (Report $report) {
                $report->setSeverity('error');
            });
        } catch (Exception $e) {
            // Do nothing
        }
    }
}
