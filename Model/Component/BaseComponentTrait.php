<?php

namespace Cleargo\Integrationframeworks\Model\Component;

trait BaseComponentTrait
{
    protected $relationParams;

    protected $websiteId;

    protected $storeId;

    protected $scheduleLogLevel;
    
    public function setRelationParams($params)
    {
        $this->relationParams = json_decode($params);
        return $this;
    }

    public function setWebsiteId($websiteId)
    {
        $this->websiteId = $websiteId;
        return $this;
    }

    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
        return $this;
    }

    public function setScheduleLogLevel($scheduleLogLevel)
    {
        $this->scheduleLogLevel = $scheduleLogLevel;
        return $this;
    }
}