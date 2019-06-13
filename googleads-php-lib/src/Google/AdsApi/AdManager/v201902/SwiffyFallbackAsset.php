<?php

namespace Google\AdsApi\AdManager\v201902;


/**
 * This file was generated from WSDL. DO NOT EDIT.
 */
class SwiffyFallbackAsset
{

    /**
     * @var \Google\AdsApi\AdManager\v201902\CreativeAsset $asset
     */
    protected $asset = null;

    /**
     * @var string[] $html5Features
     */
    protected $html5Features = null;

    /**
     * @var string[] $localizedInfoMessages
     */
    protected $localizedInfoMessages = null;

    /**
     * @param \Google\AdsApi\AdManager\v201902\CreativeAsset $asset
     * @param string[] $html5Features
     * @param string[] $localizedInfoMessages
     */
    public function __construct($asset = null, array $html5Features = null, array $localizedInfoMessages = null)
    {
      $this->asset = $asset;
      $this->html5Features = $html5Features;
      $this->localizedInfoMessages = $localizedInfoMessages;
    }

    /**
     * @return \Google\AdsApi\AdManager\v201902\CreativeAsset
     */
    public function getAsset()
    {
      return $this->asset;
    }

    /**
     * @param \Google\AdsApi\AdManager\v201902\CreativeAsset $asset
     * @return \Google\AdsApi\AdManager\v201902\SwiffyFallbackAsset
     */
    public function setAsset($asset)
    {
      $this->asset = $asset;
      return $this;
    }

    /**
     * @return string[]
     */
    public function getHtml5Features()
    {
      return $this->html5Features;
    }

    /**
     * @param string[]|null $html5Features
     * @return \Google\AdsApi\AdManager\v201902\SwiffyFallbackAsset
     */
    public function setHtml5Features(array $html5Features = null)
    {
      $this->html5Features = $html5Features;
      return $this;
    }

    /**
     * @return string[]
     */
    public function getLocalizedInfoMessages()
    {
      return $this->localizedInfoMessages;
    }

    /**
     * @param string[]|null $localizedInfoMessages
     * @return \Google\AdsApi\AdManager\v201902\SwiffyFallbackAsset
     */
    public function setLocalizedInfoMessages(array $localizedInfoMessages = null)
    {
      $this->localizedInfoMessages = $localizedInfoMessages;
      return $this;
    }

}
