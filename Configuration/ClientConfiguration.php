<?php

namespace KrzysztofMoskalik\ApiClient\Configuration;

class ClientConfiguration
{

    public function __construct(
        private ?GlobalConfiguration $globalConfiguration = null
    )
    {
        if ($globalConfiguration === null) {
            $this->globalConfiguration = new GlobalConfiguration();
        }
    }

    public function getGlobalConfiguration(): GlobalConfiguration
    {
        return $this->globalConfiguration;
    }

    public function setGlobalConfiguration(GlobalConfiguration $globalConfiguration): void
    {
        $this->globalConfiguration = $globalConfiguration;
    }


}