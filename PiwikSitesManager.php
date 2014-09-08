<?php namespace Piwik;

use \Config;
use \Session;

/**
 * Class Piwik\SitesManager
 * @module SitesManager
 */
class SitesManager extends API
{
    public function getSiteFromId($idSite, $format = null)
    {
        return $this->getResponse([
            'method' => 'SitesManager.getSiteFromId',
            'idSite' => $idSite,
            'format' => $format,
        ]);
    }

    public function getSiteUrlsFromId($idSite, $format = null)
    {
        return $this->getResponse([
            'method' => 'SitesManager.getSiteUrlsFromId',
            'idSite' => $idSite,
            'format' => $format,
        ]);
    }

    public function getAllSites($format = null)
    {
        return $this->getResponse([
            'method' => 'SitesManager.getAllSites',
            'format' => $format,
        ]);
    }

    public function getAllSitesId($format = null)
    {
        return $this->getResponse([
            'method' => 'SitesManager.getAllSitesId',
            'format' => $format,
        ]);
    }

    public function getSitesIdWithVisits($format = null)
    {
        return $this->getResponse([
            'method' => 'SitesManager.getSitesIdWithVisits',
            'timestamp' => '',
            'format' => $format,
        ]);
    }

    public function getSitesIdFromSiteUrl($url, $format = null)
    {
        return $this->getResponse([
            'method' => 'SitesManager.getSitesIdFromSiteUrl',
            'url' => $url,
            'format' => $format,
        ]);
    }

    public function addSite(
        $siteName,
        $urls,
        $ecommerce = false,
        $siteSearch = true,
        $searchKeywordParameters = '',
        $searchCategoryParameters = '',
        $excludedIps = '',
        $excludedQueryParameters = '',
        $timezone = 'UTC-5',
        $currency = 'USD',
        $group = '',
        $startDate = 'today',
        $excludedUserAgents = '',
        $keepURLFragments = true,
        $type = 'website',
        $format = null)
    {
        return $this->getResponse([
            'method' => 'SitesManager.addSite',
            'siteName' => $siteName,
            'urls' => $urls,
            'ecommerce' => $ecommerce,
            'siteSearch' => $siteSearch,
            'searchKeywordParameters' => $searchKeywordParameters,
            'searchCategoryParameters' => $searchCategoryParameters,
            'excludedIps' => $excludedIps,
            'excludedQueryParameters' => $excludedQueryParameters,
            'timezone' => $timezone,
            'currency' => $currency,
            'group' => $group,
            'startDate' => $startDate,
            'excludedUserAgents' => $excludedUserAgents,
            'keepURLFragments' => $keepURLFragments,
            'type' => $type,
            'format' => $format,
        ]);
    }

    public function deleteSite($idSite, $format = null)
    {
        return $this->getResponse([
            'method' => 'SitesManager.deleteSite',
            'idSite' => $idSite,
            'format' => $format,
        ]);
    }

    public function addSiteAliasUrls($idSite, $urls, $format = null)
    {
        return $this->getResponse([
            'method' => 'SitesManager.addSiteAliasUrls',
            'idSite' => $idSite,
            'urls' => $urls,
            'format' => $format,
        ]);
    }

    public function setSiteAliasUrls($idSite, array $urls, $format = null)
    {
        return $this->getResponse([
            'method' => 'SitesManager.setSiteAliasUrls',
            'idSite' => $idSite,
            'urls' => $urls,
            'format' => $format,
        ]);
    }

}