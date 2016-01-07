<?php
namespace Nodes\Bugsnag;

use Bugsnag_Client;
use Nodes\AbstractServiceProvider as NodesAbstractServiceProvider;
use Nodes\Bugsnag\Exceptions\Handler as BugsnagHandler;

/**
 * Class ServiceProvider
 *
 * @package Nodes\Bugsnag
 */
class ServiceProvider extends NodesAbstractServiceProvider
{
    /**
     * Nodes Bugsnag version
     *
     * @const string
     */
    const VERSION = '0.1.0';

    /**
     * Bootstrap the application service
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return void
     */
    public function boot()
    {
        // If current environment is in the array of "notify release stages",
        // we'll re-bind the default Exception Handler to use our Bugsnag Handler
        // so exceptions will be reported to Bugsnag.
        if (in_array($this->app->environment(), config('nodes.bugsnag.notify_release_stages', []))) {
            $this->app->singleton('Illuminate\Contracts\Debug\ExceptionHandler', function($app) {
                return new BugsnagHandler($app['log']);
            });
        }
    }

    /**
     * Register the service provider
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return void
     */
    public function register()
    {
        parent::register();
        $this->registerBugsnag();
    }

    /**
     * Register Bugsnag instance
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access proteected
     * @return void
     */
    protected function registerBugsnag()
    {
        $this->app->singleton('nodes.bugsnag', function ($app) {
            // Retrieve bugsnag settings
            $config = config('nodes.bugsnag');

            // Initiate Bugsnag client
            $bugsnag = new Bugsnag_Client($config['api_key']);
            $bugsnag->setStripPath(base_path())
                    ->setProjectRoot(app_path())
                    ->setAutoNotify(false)
                    ->setBatchSending(false)
                    ->setReleaseStage($app->environment())
                    ->setNotifier([
                        'name' => 'Nodes Bugsnag Laravel',
                        'version' => self::VERSION,
                        'url' => 'http://packagist.com/nodes/bugsnag'
                    ]);

            // Set notify release stages
            if (!empty($config['notify_release_stages'])) {
                $bugsnag->setNotifyReleaseStages((array) $config['notify_release_stages']);
            }

            // Set endpoint
            if (!empty($config['endpoint'])) {
                $bugsnag->setEndpoint($config['endpoint']);
            }

            // Set filters
            if (!empty($config['filters'])) {
                $bugsnag->setFilters((array) $config['filters']);
            }

            // Set proxy settings
            if (!empty($config['proxy'])) {
                $bugsnag->setProxySettings((array) $config['proxy']);
            }

            return $bugsnag;
        });
    }


    /**
     * Get the services provided by the provider
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @access public
     * @return array
     */
    public function provides()
    {
        return ['nodes.bugsnag'];
    }
}