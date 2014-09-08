<?php namespace Piwik;

use \Config;
use \Session;

/**
 * Class Piwik\Dashboard
 * @module Dashboard
 */
class Dashboard extends API
{
    /**
     * getActions
     * Get actions (hits) for the specific time period
     *
     * @access  public
     * @param   string $format Override string for the format of the API Query to be returned as
     * @return  object
     */
    public function getActions($format = null)
    {
        return $this->getResponse(array_merge([
            'method' => 'VisitsSummary.getActions',
            'idSite' => $this->getSiteId(),
            'format' => $format,
        ], $this->getPeriod()));
    }

    /**
     * getDownloads
     * Get file downloads for the specific time period
     *
     * @access  public
     * @param   string $format Override string for the format of the API Query to be returned as
     * @return  array
     */
    public function getDownloads($format = null)
    {
        return $this->getResponse(array_merge([
            'method' => 'Actions.getDownloads',
            'idSite' => $this->getSiteId(),
            'format' => $format,
        ], $this->getPeriod()));
    }

    /**
     * getKeywords
     * Get search keywords for the specific time period
     *
     * @access  public
     * @param   string $format Override string for the format of the API Query to be returned as
     * @return  array
     */
    public function getKeywords($format = null)
    {
        return $this->getResponse(array_merge([
            'method' => 'Referers.getKeywords',
            'idSite' => $this->getSiteId(),
            'format' => $format,
        ], $this->getPeriod()));
    }

    /**
     * getLastVisitsDetails
     * Get information about last 10 visits (ip, time, country, pages, etc.)
     *
     * @access  public
     * @param   int $count Limit the number of visits returned by $count
     * @param   string $format Override string for the format of the API Query to be returned as
     * @return  array
     */
    public function getLastVisitsDetails($count, $format = null)
    {
        return $this->getResponse(array_merge([
            'method' => 'Live.getLastVisitsDetails',
            'idSite' => $this->getSiteId(),
            'filter_limit' => $count,
            'format' => $format,
        ], $this->getPeriod()));
    }

    /**
     * getLastVisitsParsed
     * Get information about last 10 visits (ip, time, country, pages, etc.) in a formatted array with GeoIP information if enabled
     *
     * @access  public
     * @param   int $count Limit the number of visits returned by $count
     * @param   string $format Override string for the format of the API Query to be returned as
     * @return  array
     */
    public function getLastVisitsParsed($count, $format = null)
    {
        if (in_array($format, array('xml', 'rss', 'html', 'original'))) {
            echo "Sorry, 'xml', 'rss', 'html' and 'original' are not yet supported.";
            return false;
        }

        $visits = $this->getLastVisitsDetails($count, $format);

        $data = array();
        foreach ($visits as $v) {
            // Get the last array element which has information of the last page the visitor accessed
            switch ($this->getApiFormat($format)) {
                case 'json':
                    $count = count($v->actionDetails) - 1;
                    $page_link = $v->actionDetails[$count]->url;
                    $page_title = $v->actionDetails[$count]->pageTitle;

                    // Get just the image names (API returns path to icons in piwik install)
                    $flag = explode('/', $v->countryFlag);
                    $flag_icon = end($flag);

                    $os = explode('/', $v->operatingSystemIcon);
                    $os_icon = end($os);

                    $browser = explode('/', $v->browserIcon);
                    $browser_icon = end($browser);

                    $data[] = array(
                        'time' => date("M j Y, g:i a", $v->lastActionTimestamp),
                        'title' => $page_title,
                        'link' => $page_link,
                        'ip_address' => $v->visitIp,
                        'provider' => $v->provider,
                        'country' => $v->country,
                        'country_icon' => $flag_icon,
                        'os' => $v->operatingSystem,
                        'os_icon' => $os_icon,
                        'browser' => $v->browserName,
                        'browser_icon' => $browser_icon,
                    );
                    break;
                case 'php':
                    $count = count($v['actionDetails']) - 1;
                    $page_link = $v['actionDetails'][$count]['url'];
                    $page_title = $v['actionDetails'][$count]['pageTitle'];

                    // Get just the image names (API returns path to icons in piwik install)
                    $flag = explode('/', $v['countryFlag']);
                    $flag_icon = end($flag);

                    $os = explode('/', $v['operatingSystemIcon']);
                    $os_icon = end($os);

                    $browser = explode('/', $v['browserIcon']);
                    $browser_icon = end($browser);

                    $data[] = array(
                        'time' => date("M j Y, g:i a", $v['lastActionTimestamp']),
                        'title' => $page_title,
                        'link' => $page_link,
                        'ip_address' => $v['visitIp'],
                        'provider' => $v['provider'],
                        'country' => $v['country'],
                        'country_icon' => $flag_icon,
                        'os' => $v['operatingSystem'],
                        'os_icon' => $os_icon,
                        'browser' => $v['browserName'],
                        'browser_icon' => $browser_icon,
                    );
                    break;

                case 'xml':

                    break;

                case 'html':

                    break;

                case 'rss':

                    break;

                case 'original':

                    break;

                default:
                    $count = count($v->actionDetails) - 1;
                    $page_link = $v->actionDetails[$count]->url;
                    $page_title = $v->actionDetails[$count]->pageTitle;

                    // Get just the image names (API returns path to icons in piwik install)
                    $flag = explode('/', $v->countryFlag);
                    $flag_icon = end($flag);

                    $os = explode('/', $v->operatingSystemIcon);
                    $os_icon = end($os);

                    $browser = explode('/', $v->browserIcon);
                    $browser_icon = end($browser);

                    $data[] = array(
                        'time' => date("M j Y, g:i a", $v->lastActionTimestamp),
                        'title' => $page_title,
                        'link' => $page_link,
                        'ip_address' => $v->visitIp,
                        'provider' => $v->provider,
                        'country' => $v->country,
                        'country_icon' => $flag_icon,
                        'os' => $v->operatingSystem,
                        'os_icon' => $os_icon,
                        'browser' => $v->browserName,
                        'browser_icon' => $browser_icon,
                    );
                    break;
            }

        }
        return $data;
    }

    /**
     * getOutlinks
     * Get outlinks for the specific time period
     *
     * @access  public
     * @param   string $format Override string for the format of the API Query to be returned as
     * @return  array
     */
    public function getOutlinks($format = null)
    {
        return $this->getResponse(array_merge([
            'method' => 'Actions.getOutlinks',
            'idSite' => $this->getSiteId(),
            'format' => $format,
        ], $this->getPeriod()));
    }

    /**
     * getPageTitles
     * Get page visit information for the specific time period
     *
     * @access  public
     * @param   string $format Override string for the format of the API Query to be returned as
     * @return  array
     */
    public function getPageTitles($format = null)
    {
        return $this->getResponse(array_merge([
            'method' => 'Actions.getPageTitles',
            'idSite' => $this->getSiteId(),
            'format' => $format,
        ], $this->getPeriod()));
    }

    /**
     * getSearchEngines
     * Get search engine referer information for the specific time period
     *
     * @access  public
     * @param   string $format Override string for the format of the API Query to be returned as
     * @return  array
     */
    public function getSearchEngines($format = null)
    {
        return $this->getResponse(array_merge([
            'method' => 'Referers.getSearchEngines',
            'idSite' => $this->getSiteId(),
            'format' => $format,
        ], $this->getPeriod()));
    }

    /**
     * getUniqueVisitors
     * Get unique visitors for the specific time period
     *
     * @access  public
     * @param   string $format Override string for the format of the API Query to be returned as
     * @return  array
     */
    public function getUniqueVisitors($format = null)
    {
        return $this->getResponse(array_merge([
            'method' => 'VisitsSummary.getUniqueVisitors',
            'idSite' => $this->getSiteId(),
            'format' => $format,
        ], $this->getPeriod()));
    }

    /**
     * getVisits
     * Get all visits for the specific time period
     *
     * @access  public
     * @param   string $format Override string for the format of the API Query to be returned as
     * @return  array
     */
    public function getVisits($format = null)
    {
        return $this->getResponse(array_merge([
            'method' => 'VisitsSummary.getVisits',
            'idSite' => $this->getSiteId(),
            'format' => $format,
        ], $this->getPeriod()));
    }

    /**
     * getWebsites
     * Get refering websites (traffic sources) for the specific time period
     *
     * @access  public
     * @param   string $format Override string for the format of the API Query to be returned as
     * @return  array
     */
    public function getWebsites($format = null)
    {
        return $this->getResponse(array_merge([
            'method' => 'Referers.getWebsites',
            'idSite' => $this->getSiteId(),
            'format' => $format,
        ], $this->getPeriod()));
    }

    /**
     * getTag
     * Get javascript tag for use in tracking the website
     *
     * Note: Works best when using PHP as the format
     *
     * @access  public
     * @param   string $format Override string for the format of the API Query to be returned as
     * @return  string
     */

    public function getTag()
    {
        $tag =
            '<!-- Piwik -->
            <script type="text/javascript">
            var _paq = _paq || [];
            (function(){ var u=(("https:" == document.location.protocol) ? "' . $this->toHttps() . '/" : "' . $this->toHttp() . '/");
            _paq.push([\'setSiteId\', ' . $this->getSiteId() . ']);
            _paq.push([\'setTrackerUrl\', u+\'piwik.php\']);
            _paq.push([\'trackPageView\']);
            _paq.push([\'enableLinkTracking\']);
            var d=document, g=d.createElement(\'script\'), s=d.getElementsByTagName(\'script\')[0]; g.type=\'text/javascript\'; g.defer=true; g.async=true; g.src=u+\'piwik.js\';
            s.parentNode.insertBefore(g,s); })();
            </script>
            <!-- End Piwik Code -->';

        return $tag;
    }

    /**
     * getRank
     * Get SEO Rank for the website
     *
     * @access  public
     * @param   string $format Override string for the format of the API Query to be returned as
     * @return  array
     */

    public function getRank($id, $format = 'json')
    {
        // PHP doesn't seem to work with this, so defaults to JSON
        return $this->getResponse([
            'method' => 'SEO.getRank',
            'url' => $this->urlFromId($id),
            'format' => $format,
        ]);
    }

    /**
     * getVersion
     * Get Version of the Piwik Server
     *
     * @access  public
     * @param   string $format Override string for the format of the API Query to be returned as
     * @return  array
     */

    public function getVersion($format = null)
    {
        return $this->getResponse([
            'method' => 'API.getPiwikVersion',
            'format' => $format,
        ]);
    }

    /**
     * @param $method
     * @param array $arguments
     * @param bool $id
     * @param bool $period
     * @param null $format
     * @return array
     */
    public function custom($method, $arguments = array(), $id = false, $period = false, $format = null)
    {
        if ($arguments == null) {
            $arguments = array();
        }
        if (isset($method)) {
            $url = $this->getApiUrl() . '/index.php?module=API&method=' . $method;
            foreach ($arguments as $key => $value) {
                $url .= '&' . $key . '=' . $value;
            }
            if ($id) {
                $url .= '&idSite=' . $this->getSiteId($id);
            }
            if ($period === true) {
                $url .= $this->getPeriod();
            }
            $url .= '&format=' . $this->getApiFormat($format) . '&token_auth=' . $this->getApiKey();
            return $this->getDecoded($url, $format);
        }
    }

}