<?php

namespace RatePAY\Frontend;

use RatePAY\Exception\FrontendException;

class DeviceFingerprintBuilder
{

    /**
     * DFP Snippet Id (vendor)
     *
     * @var string
     */
    private $snippetId;

    /**
     * Location of DFP snippet
     *
     * @var string
     */
    private $location = "checkout";

    /**
     * DFP token
     *
     * @var string
     */
    private $token;

    /**
     * DFP URI
     *
     * @var string
     */
    private $uri = "//d.ratepay.com";


    /**
     * DeviceFingerprintBuilder constructor.
     *
     * @param $uniqueIdentifier
     */
    public function __construct($snippetId = "", $uniqueIdentifier = "")
    {
        if (!empty($snippetId)) {
            $this->setSnippetId($snippetId);
        } else {
            throw new FrontendException("DeviceFingerprintBuilder: Snippet id must be set");
        }

        if (!empty($uniqueIdentifier)) {
            $this->createToken($uniqueIdentifier);
        } else {
            throw new FrontendException("DeviceFingerprintBuilder: Transaction identifier must be set");
        }
    }

    /**
     * @param string $snippetId
     */
    public function setSnippetId($snippetId)
    {
        $this->snippetId = $snippetId;
    }

    /**
     * Sets DFP token
     *
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Returns DFP token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Returns DFP snippet code (JS code)
     * @return string
     */
    public function getDfpSnippetCode($smarty = false)
    {
        $snippet = sprintf(
            (!$smarty) ? '<script language="JavaScript">var di = %s;</script>' : '<script language="JavaScript">{literal}var di = %s;{/literal}</script>',
            json_encode([
                't' => $this->token,
                'v' => $this->snippetId,
                'l' => $this->location
            ])
        );

        $snippet .= sprintf(
            '<script type="text/javascript" src="%s/%s/di.js"></script>',
            $this->uri,
            $this->snippetId
        );

        $snippet .= sprintf(
            '<noscript><link rel="stylesheet" type="text/css" href="%s/di.css?t=%s&v=%s&l=%s"></noscript>',
            $this->uri,
            $this->token,
            $this->snippetId,
            $this->location
        );

        return $snippet;
    }

    /**
     * Creates unique token
     *
     * @param $uniqueIdentifier
     */
    private function createToken($uniqueIdentifier) {
        $this->token = md5($this->snippetId . "_" . $uniqueIdentifier . "_" . microtime());
    }

}