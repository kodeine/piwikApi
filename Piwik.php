<?php namespace Piwik;

use \Config;
use \Session;

class API
{
    /**
     * @var string
     */
    private $piwik_url = '';
    /**
     * @var string
     */
    private $site_id = '';
    /**
     * @var string
     */
    private $apikey = '';
    /**
     * @var string
     */
    private $username = '';
    /**
     * @var string
     */
    private $password = '';
    /**
     * @var string
     */
    private $format = '';
    /**
     * @var string
     */
    private $period = '';

    /**
     * @var bool
     */
    private $constructed = false;
    private $args = [];


    /**
     * @param $piwik_url
     * @param $site_id
     * @param $apikey
     * @param $username
     * @param $password
     * @param $format
     * @param $period
     */
    public function __construct(array $args = array())
    {
        if (!empty($args)) {
            $this->piwik_url = $args['piwik_url'];
            $this->site_id = $args['site_id'];
            $this->apikey = $args['apikey'];
            $this->username = $args['username'];
            $this->password = $args['password'];
            $this->format = $args['format'];
            $this->period = $args['period'];
            $this->constructed = true;
            $this->args = $args;
        }

    }

    /**
     * setSiteId
     * Sets site id.
     * @param $id
     */
    public function setSiteId( int $id )
    {
        $this->site_id = $id;
    }

    public function module($call = null)
    {
        // cache module.
        if (isset($this->module[$call]))
            return $this->module[$call];

        $module = '\Piwik\\'.$call;

        if (is_null($call) || !class_exists($module))
            return false;

        return $this->module[$call] = new $module($this->args);
    }

    /**
     * getResponse
     * send request to api and return parsed response.
     *
     * @access public
     * @return format (json)
     */

    public function getResponse(array $params = array())
    {
        $params = array_merge($params, [
            'module' => 'API',
            'format' => $this->getApiFormat($params['format']),
            'token_auth' => $this->getApiKey(),
        ]);

        $url = $this->getApiUrl() . '/?' . http_build_query($params);
        $response = $this->getDecoded($url, $params['format']);
        if (is_array($response) && sizeof($response)==1) {
            return $response[0];
        }else{
            return $response;
        }
    }
    
    /**
     * getPeriod
     * Read config for the period to make API queries about, and translate it into URL-friendly strings
     *
     * @access  public static
     * @return  string
     */

    public function getPeriod()
    {
        $this->period = ($this->constructed) ? $this->period : Config::get('piwik::period');
        switch ($this->period) {
            case 'today':
                return [
                    'period' => 'day',
                    'date' => 'today'
                ];
                break;

            case 'yesterday':
                return [
                    'period' => 'day',
                    'date' => 'yesterday'
                ];
                break;

            case 'previous7':
                return [
                    'period' => 'range',
                    'date' => 'previous7'
                ];
                break;

            case 'previous30':
                return [
                    'period' => 'range',
                    'date' => 'previous30'
                ];
                break;

            case 'last7':
                return [
                    'period' => 'range',
                    'date' => 'last7'
                ];
                break;

            case 'last30':
                return [
                    'period' => 'range',
                    'date' => 'last30'
                ];
                break;

            case 'currentweek':
                return [
                    'period' => 'week',
                    'date' => 'today'
                ];
                break;

            case 'currentmonth':
                return [
                    'period' => 'month',
                    'date' => 'today'
                ];
                break;

            case 'currentyear':
                return [
                    'period' => 'year',
                    'date' => 'today'
                ];
                break;

            default:
                return [
                    'period' => 'day',
                    'date' => 'yesterday'
                ];
                break;
        }
    }

    /**
     * toHttps
     * Convert http:// to https:// for tag generation
     *
     * @access  public static
     * @return  string
     */

    public function toHttps()
    {
        $url = $this->getApiUrl();
        if (preg_match('/http:/', $url)) {
            return str_replace('http', 'https', $url);
        } else if (preg_match('/https:/', $url)) {
            return $url;
        }
    }

    /**
     * toHttp
     * Check that the URL is http://
     *
     * @access  public static
     * @return  string
     */

    public function toHttp()
    {
        $url = $this->getApiUrl();
        if (preg_match('/https:/', $url)) {
            return str_replace('https', 'http', $url);
        } else if (preg_match('/http:/', $url)) {
            return $url;
        }
    }

    /**
     * getApiFormat
     * Check the format as defined in config, and default to json if it is not on the list
     *
     * @access  public static
     * @param   string $override Override string for the format of the API Query to be returned as
     * @return  string
     */

    public function getApiFormat($override = null)
    {
        if ($override !== null) {
            $this->format = $override;
        } else {
            $this->format = ($this->constructed) ? $this->format : Config::get('piwik::format');
        }
        switch ($this->format) {
            case 'json':
                return 'json';
                break;
            case 'php':
                return 'php';
                break;

            case 'xml':
                return 'xml';
                break;

            case 'html':
                return 'html';
                break;

            case 'rss':
                return 'rss';
                break;

            case 'original':
                return 'original';
                break;

            default:
                return 'json';
                break;
        }

    }

    /**
     * getSiteId
     * Allows access to site_id from all functions
     *
     * @access  public static
     * @return  string
     */

    public function getSiteId($id = null)
    {
        $this->site_id = ($this->constructed) ? $this->site_id : Config::get('piwik::site_id');
        if (isset($id)) {
            $this->site_id = $id;
            return $this->site_id;
        } else {
            return $this->site_id;
        }
    }

    /**
     * getApiKey
     * Allows access to apikey from all functions
     *
     * @access  public static
     * @return  string
     */

    public function getApiKey()
    {
        $this->apikey = ($this->constructed) ? $this->apikey : Config::get('piwik::api_key');
        $this->username = ($this->constructed) ? $this->username : Config::get('piwik::username');
        $this->password = ($this->constructed) ? $this->password : Config::get('piwik::password');

        if(empty($this->apikey) && !empty($this->username) && !empty($this->password)){
            $url = $this->getApiUrl().'/index.php?module=API&method=UsersManager.getTokenAuth&userLogin='.$this->username.'&md5Password='.$this->password.'&format='.$this->getApiFormat();
            if(!Session::has('apikey')) Session::put('apikey', $this->getDecoded($url));
            $this->apikey = Session::get('apikey');
            return $this->apikey->value;
        } else if(!empty($this->apikey)) {
            return $this->apikey;
        } else {
            echo '<strong style="color:red">You must enter your API Key or Username/Password combination to use this bundle!</strong><br/>';
        }
    }

    /**
     * getApiUrl
     * Allows access to piwik_url from all functions
     *
     * @access  public
     * @return  string
     */

    public function getApiUrl()
    {
        $this->piwik_url = ($this->constructed) ? $this->piwik_url : Config::get('piwik::piwik_url');
        return $this->piwik_url;
    }

    /**
     * _get
     * Curl private method.
     * @access private
     * @param $url
     * @return mixed
     */

    private function _get($url)
    {
        //echo $url."\n";
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * getDecoded
     * Decode the format to usable PHP arrays/objects
     *
     * @access  public static
     * @param   string $url URL to decode (declared within other functions)
     * @return  array
     */

    public function getDecoded($url, $format = null)
    {
        switch ($this->getApiFormat($format)) {
            case 'json':
                return json_decode($this->_get($url));
                break;
            case 'php':
                return unserialize($this->_get($url));
                break;

            case 'xml':
                //$xml = unserialize(file_get_contents($url));
                return 'Not Supported as of yet';
                break;

            case 'html':
                return $this->_get($url);
                break;

            case 'rss':
                return 'Not supported as of yet';
                break;

            case 'original':
                return file_get_contents($url);
                break;

            default:
                return file_get_contents($url);
                break;
        }
    }

    /**
     * urlFromId
     * Fetches the URL from Site ID
     *
     * @access  public static
     * @param   string $id Override for ID, so you can specify one rather than fetching it from config
     * @return  string
     */

    public function urlFromId($id = null)
    {
        return $this->getResponse(array_merge([
            'method' => 'SitesManager.getSiteUrlsFromId',
            'idSite' => $this->getSiteId($id),
            'format' => 'php',
        ], $this->getPeriod()))[0][0];
    }

}