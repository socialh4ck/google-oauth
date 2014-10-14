<?php namespace Socialh4ck\Google;

/**
 * Class Google
 * @package Socialh4ck\Google
 */
class Google {
	
    /**
     * @var Client
     */
    protected $client;
    
	/**
     * @var Service
     */
    protected $service;
	
	/**
     * @var Site ids
     */
	private $site_ids = array();
	
    /**
     * @param Google Client
     */
	public function __construct(\Google_Client $client) 
	{
		$this->setClient($client);
		$this->setService($client);
	}
	
	/**
     * GET Client
     */
	public function getClient() 
	{
		return $this->client;
	}

	/**
     * SET Client
     */
	public function setClient(\Google_Client $client) 
	{
		$this->client = $client;

		return $this;
	}
	
	/**
     * GET Service
     */
	public function getService() 
	{
		return $this->service;
	}

	/**
     * SET Service
     */
	public function setService(\Google_Client $client) 
	{
		$this->service = new \Google_Service_Analytics($client);

		return $this;
	}
	
	/**
	 * Generates the OAuth login URL
	 *
	 * @return string Google OAuth login URL
	 */
	public function getLoginUrl() {
        return $this->client->createAuthUrl();
    }
	
	/**
	 * Access Token Setter
	 * 
	 * @param object|string $access_token
	 * @return void
	 */
	public function setAccessToken($access_token) 
	{
		$this->client->setAccessToken($access_token);
	}
	
	/**
	 * Access Token Setter
	 * 
	 * @param object|string $access_token
	 * @return void
	 */
	public function setRefreshToken($access_refresh) 
	{
		// Refresh Token
		$this->client->refreshToken($access_refresh);
		
		// Return New Access Token
		return $this->client->getAccessToken();
	}
	
	/**
	 * Get the access token. If the current access token from session manager exists,
	 * then we will use them, otherwise we get from redirected facebook login.
	 * 
	 * @return mixed 
	 */
	public function getAccessToken()
	{
		return $this->client->getAccessToken();
	}

	/**
     * SET GET Profile
     */
	public function getProfile() 
	{
		$oauth = new \Google_Service_Oauth2($this->client);
		return $oauth->userinfo->get();
	}
	
	/**
     * Service Plus
     */
	public function servicePlus() 
	{
		return new \Google_Service_Plus($this->client);
	}
	
	/**
	 * Determine whether the "google.access_token".
	 * 
	 * @return boolean
	 */
	public function hasSessionToken()
	{
		return \Session::has('google.access_token');
	}

	/**
	 * Get the google access token via Session laravel.
	 * 
	 * @return string 
	 */
	public function getSessionToken()
	{
		return \Session::get('google.access_token');
	}

	/**
	 * Put the access token to the laravel session manager.
	 * 
	 * @param  string $token 
	 * @return void        
	 */
	public function putSessionToken($token)
	{
		\Session::put('google.access_token', $token);
	}

	/**
	 * Get callback from facebook.
	 * 
	 * @return boolean 
	 */
	public function getCallback()
	{
		$this->client->authenticate($_GET['code']);
		$token = $this->client->getAccessToken();
		
		if( ! empty($token) )
		{
			$this->putSessionToken($token);
			return true;
		}
		
		return false;
	}

	/**
	 * Get google session from laravel session manager.
	 * 
	 * @return string|mixed 
	 */
	public function getGoogleSession()
	{
		return \Session::get('google.session');
	}

	/**
	 * Destroy all google session.
	 * 
	 * @return void 
	 */
	public function destroy()
	{
		\Session::forget('google.session');
		\Session::forget('google.access_token');
	}

	/**
	 * Logout the current user.
	 * 
	 * @return void
	 */
	public function logout()
	{
		$this->client->revokeToken();
	 	$this->destroy();
	}
	
	/**
	 * Get user Query.
	 * 	$site_id = Google::getSiteIdByUrl('http://github.com/'); // return something like 'ga:11111111'
	 *	$stats   = Google::query($site_id, '7daysAgo', 'yesterday', 'ga:visits,ga:pageviews');
	 * @return mixed 
	 */
	public function query($id, $start_date, $end_date, $metrics, $others = array()) 
	{
		return $this->service->data_ga->get($id, $start_date, $end_date, $metrics, $others);
	}

    /**
     * Runs analytics query calls in batch mode
     * It accepts an array of queries as specified by the parameters of the Analytics::query function
     * With an additional optional parameter named key, which is used to identify the results for a specific object
     *
     * Returns an array with object keys as response-KEY where KEY is the key you specified or a random key returned
     * from analytics.
     * @param array $queries
     * @return array|null
     */
    public function batchQueries(array $queries) 
	{
        /*
         * Set the client to use batch mode
         * When batch mode is activated calls to Analytics::query will return
         * the request object instead of the resulting data
         */
        $this->client->setUseBatch(true);

        $batch = new \Google_Http_Batch($this->client);
        foreach ($queries as $query) 
		{
            // pull the key from the array if specified so we can later identify our result
            $key = array_pull($query, 'key');

            // call the original query method to get the request object
            $req = call_user_func_array(__NAMESPACE__ .'\Google::query', $query);

            $batch->add($req, $key);
        }

        $results = $batch->execute();

        // Set the client back to normal mode
        $this->client->setUseBatch(false);

        return $results;
    }

	/**
	 * Get user Segments.
	 * 
	 * @return mixed 
	 */
	public function segments() 
	{
		return $this->service->management_segments;
	}

	/**
	 * Get user Accounts.
	 * 
	 * @return mixed 
	 */
	public function accounts() 
	{
		return $this->service->management_accounts->listManagementAccounts();
	}

	/**
	 * Get user Goals.
	 * 
	 * @return mixed 
	 */
	public function goals() 
	{
		return $this->service->management_goals;
	}
	
	/**
	 * Get user profile.
	 * 
	 * @return mixed 
	 */
	public function profiles() 
	{
		return $this->service->management_profiles;
	}

	public function webproperties() 
	{
		return $this->service->management_webproperties;
	}

	/**
	 * Get user All Site IDS.
	 * 
	 * @return mixed 
	 */
	public function getAllSitesIds() 
	{
		if (empty($this->site_ids)) 
		{
			$sites = $this->service->management_profiles->listManagementProfiles("~all", "~all");
			foreach($sites['items'] as $site) 
			{
				$this->site_ids[$site['websiteUrl']] = 'ga:' . $site['id'];
			}
		}

		return $this->site_ids;
	}

	/**
	 * Get user Site URL.
	 * 
	 * @return mixed 
	 */
	public function getSiteIdByUrl($url) 
	{
		if (!isset($this->site_ids[$url])) 
		{
			$this->getAllSitesIds();
		}

		if (isset($this->site_ids[$url])) 
		{
			return $this->site_ids[$url];
		}

		throw new \Exception("Site $url is not present in your Analytics account.");
	}
	
}
