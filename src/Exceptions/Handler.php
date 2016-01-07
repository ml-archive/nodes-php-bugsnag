<?php
namespace Nodes\Bugsnag\Exceptions;

use Exception;
use Bugsnag_Error;
use App\Exceptions\Handler as AppHandler;
use Nodes\Exceptions\Exception as NodesException;

/**
 * Class Handler
 *
 * @package Nodes\Bugsnag\Exceptions
 */
class Handler extends AppHandler
{
    /**
     * Report exception to bugsnag
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        try {
            if ($e instanceof NodesException && $e->getReport()) {
                app('nodes.bugsnag')->notifyException($e, $e->getMeta(), $e->getSeverity());
            } elseif (!$e instanceof NodesException) {
                app('nodes.bugsnag')->notifyException($e, null, 'error');
            }
        } catch (Exception $e) {
            // Do nothing
        }

        return parent::report($e);
    }
}