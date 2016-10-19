<?php

namespace Nodes\Bugsnag;

use Bugsnag\Client;
use Bugsnag\Configuration;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Nodes\Bugsnag\Exceptions\Handler as BugsnagHandler;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Nodes Bugsnag version.
     *
     * @const string
     */
    const VERSION = '2.0';

    /**
     * Bootstrap the application service.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return void
     */
    public function boot()
    {
        // If current environment is in the array of "notify release stages",
        // we'll re-bind the default Exception Handler to use our Bugsnag Handler
        // so exceptions will be reported to Bugsnag.
        if (in_array($this->app->environment(), config('nodes.bugsnag.notify_release_stages', []))) {
            $this->app->singleton('Illuminate\Contracts\Debug\ExceptionHandler', function ($app) {
                return app(BugsnagHandler::class);
            });
        }

        // Register publish groups
        $this->publishGroups();
    }

    /**
     * Register the service provider.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return void
     */
    public function register()
    {
        $this->registerBugsnag();
    }

    /**
     * Register publish groups.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return void
     */
    protected function publishGroups()
    {
        // Config files
        $this->publishes([
            __DIR__.'/../config/bugsnag.php' => config_path('nodes/bugsnag.php'),
        ], 'config');
    }

    /**
     * Register Bugsnag instance.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return void
     */
    protected function registerBugsnag()
    {
        $this->app->singleton('nodes.bugsnag', function ($app) {
            // Retrieve bugsnag settings
            $config = config('nodes.bugsnag');

            // Initiate Bugsnag client
            if (! empty($config['proxy'])) {
                $guzzleClient = new \GuzzleHttp\Client([
                    'base_uri' => $config['endpoint'],
                    'proxy' => $config['proxy'],
                ]);

                $bugsnag = new Client(new Configuration($config['api_key']), null, $guzzleClient);
            } else {
                $bugsnag = Client::make();
            }

            $bugsnag->getConfig()->setStripPath(base_path());
            $bugsnag->getConfig()->setProjectRoot(app_path());
            $bugsnag->getConfig()->setReleaseStage(env('APP_ENVIRONMENT'));
            $bugsnag->getConfig()->setBatchSending(false);
            $bugsnag->getConfig()->setNotifier([
                'name' => 'Nodes Bugsnag Laravel',
                'version' => self::VERSION,
                'url' => 'http://packagist.com/nodes/bugsnag',
            ]);

            // Set notify release stages
            if (! empty($config['notify_release_stages'])) {
                $bugsnag->getConfig()->setNotifyReleaseStages((array) $config['notify_release_stages']);
            }

            // Set filters
            if (! empty($config['filters'])) {
                $bugsnag->getConfig()->setFilters((array) $config['filters']);
            }

            // Attach user agent data to all exceptions
            $bugsnag->getConfig()->setMetaData(['User Agent' => $this->gatherUserAgentData()]);

            return $bugsnag;
        });
    }

    /**
     * Gather user agent data.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return array
     */
    protected function gatherUserAgentData()
    {
        // User agent container
        $userAgents = ['original' => null, 'nodes' => null];

        // Retrieve original user agent
        $originalUserAgent = user_agent();
        if (! empty($originalUserAgent)) {
            $userAgents['original'] = [
                'browser' => $originalUserAgent->getBrowserWithVersion(),
                'platform' => $originalUserAgent->getPlatform(),
                'device' => $originalUserAgent->getDevice(),
                'isMobile' => $originalUserAgent->isMobile(),
                'isTablet' => $originalUserAgent->isTablet(),
            ];
        }

        // Retrieve nodes user agent
        $nodesUserAgent = nodes_user_agent();
        if (! empty($nodesUserAgent)) {
            $userAgents['nodes'] = [
                'version' => $nodesUserAgent->getVersion(),
                'platform' => $nodesUserAgent->getPlatform(),
                'device' => $nodesUserAgent->getDevice(),
                'debug' => $nodesUserAgent->getDebug(),
            ];
        }

        return $userAgents;
    }

    /**
     * Get the services provided by the provider.
     *
     * @author Morten Rugaard <moru@nodes.dk>
     *
     * @return array
     */
    public function provides()
    {
        return ['nodes.bugsnag'];
    }
}
