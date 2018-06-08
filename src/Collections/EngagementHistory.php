<?php

namespace LivePersonNY\LiveEngageLaravel\Collections;

use Illuminate\Support\Collection;
use LivePersonNY\LiveEngageLaravel\Models\Engagement;
use LivePersonNY\LiveEngageLaravel\LiveEngageLaravel;

class EngagementHistory extends Collection {
	
	private $instance;
	
	public function __construct(array $models = [], LiveEngageLaravel $instance = null) {
		
		$this->instance = $instance;
		return parent::__construct($models);
		
	}
	
	public function next() {
		
		if (!$this->instance) return false;
		
		$instance = $this->instance;
		
		$next = $instance->retrieveHistory($instance->start, $instance->end, $instance->next);
		if (property_exists($next->_metadata, 'next')) {
			$instance->next = $next->_metadata->next->href;
			
			$history = [];
			foreach ($next->interactionHistoryRecords as $item) {
				$record = new Engagement();
				$record->fill((array) $item);
				$history[] = $record;
			}
			
			return $this->merge(new EngagementHistory($history));
			
		} else {
			return false;
		}
		
	}
	
	public function prev() {
		
		if (!$this->instance) return false;
		
		$instance = $this->instance;
		
		$prev = $instance->retrieveHistory($instance->start, $instance->end, $instance->prev);
		if (property_exists($prev->_metadata, 'prev')) {
			$instance->prev = $prev->_metadata->prev->href;
			
			$history = [];
			foreach ($prev->interactionHistoryRecords as $item) {
				$record = new Engagement();
				$record->fill((array) $item);
				$history[] = $record;
			}
			
			return $this->merge(new EngagementHistory($history));
			
		} else {
			return false;
		}
		
	}
	
}