<?php namespace Socialh4ck\Google;

use Illuminate\Support\ServiceProvider;

class GoogleServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Boot the package.
	 * 
	 * @return void 
	 */
	public function boot()
	{
		$this->package('socialh4ck/google');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('Socialh4ck\Google\Google', function ($app)
		{
			if(!\File::exists($app['config']->get('google::certificate_path')))
			{
				throw new \Exception("Can't find the .p12 certificate in: " . $app['config']->get('google::certificate_path'));
			}
			
			$client = new \Google_Client();
			$client->setApplicationName($app['config']->get('google::clientId'));
            $client->setClientId($app['config']->get('google::clientId'));
            $client->setClientSecret($app['config']->get('google::clientSecret'));
            $client->setRedirectUri($app['config']->get('google::redirectUri'));
			$client->setDeveloperKey($app['config']->get('google::developerKey'));
            $client->setScopes($app['config']->get('google::scopes'));
            $client->setAccessType($app['config']->get('google::access_type', 'offline'));
			
			$client->setAssertionCredentials(
				new \Google_Auth_AssertionCredentials(
					$app['config']->get('google::service_email'),
					array(
						'https://www.googleapis.com/auth/analytics',
						'https://www.googleapis.com/auth/analytics.edit',
						'https://www.googleapis.com/auth/analytics.manage.users',
						'https://www.googleapis.com/auth/analytics.provision',
						'https://www.googleapis.com/auth/analytics.readonly'
					),
					file_get_contents($app['config']->get('google::certificate_path'))
				)
			);
			
            return new Google($client);
		});
		
		$this->app->singleton('google', 'Socialh4ck\Google\Google');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('google');
	}

}
