<?php

namespace LivePersonInc\LiveEngageLaravel\Collections;

use Illuminate\Support\Collection;
use LivePersonInc\LiveEngageLaravel\LiveEngageLaravel;
use LivePersonInc\LiveEngageLaravel\Models\Engagement;
use LivePersonInc\LiveEngageLaravel\Models\Info;
use LivePersonInc\LiveEngageLaravel\Models\Visitor;
use LivePersonInc\LiveEngageLaravel\Models\Campaign;

class EngagementHistory extends Collection
{
    private $instance;

    public function __construct(array $models = [], LiveEngageLaravel $instance = null)
    {
        $this->instance = $instance;

        parent::__construct($models);
    }
    
    public function find($engagementID)
    {
	    $result = $this->filter(function ($value, $key) use ($engagementID) {
		    return $value->info->sessionId == $engagementID;
	    });
	    
	    return $result->first();
    }

    public function next()
    {
        if (! $this->instance) {
            return false;
        }

        $instance = $this->instance;

        $next = $instance->retrieveHistory($instance->start, $instance->end, $instance->next);
        if (property_exists($next->_metadata, 'next')) {
            $instance->next = $next->_metadata->next->href;

            $history = [];
            foreach ($next->interactionHistoryRecords as $item) {
	            
                if (property_exists($item, 'info')) {
	                $item->info = new Info((array) $item->info);
	            }
	
	            if (property_exists($item, 'visitorInfo')) {
	                $item->visitorInfo = new Visitor((array) $item->visitorInfo);
	            }
	
	            if (property_exists($item, 'campaign')) {
	                $item->campaign = new Campaign((array) $item->campaign);
	            }
	
	            $history[] = new Engagement((array) $item);
            }

            return $this->merge(new EngagementHistory($history));
        } else {
            return false;
        }
    }

    public function prev()
    {
        if (! $this->instance) {
            return false;
        }

        $instance = $this->instance;

        $prev = $instance->retrieveHistory($instance->start, $instance->end, $instance->prev);
        if (property_exists($prev->_metadata, 'prev')) {
            $instance->prev = $prev->_metadata->prev->href;

            $history = [];
            foreach ($next->interactionHistoryRecords as $item) {
	            
                if (property_exists($item, 'info')) {
	                $item->info = new Info((array) $item->info);
	            }
	
	            if (property_exists($item, 'visitorInfo')) {
	                $item->visitorInfo = new Visitor((array) $item->visitorInfo);
	            }
	
	            if (property_exists($item, 'campaign')) {
	                $item->campaign = new Campaign((array) $item->campaign);
	            }
	
	            $history[] = new Engagement((array) $item);
            }

            return $this->merge(new self($history));
        } else {
            return false;
        }
    }
    
    public function getMetaDataAttribute()
    {
	    return $this->attributes['_metaData'];
    }
    
    public function setMetaDataAttribute($value)
    {
	    $this->attributes['_metaData'] = $value;
    }
}
