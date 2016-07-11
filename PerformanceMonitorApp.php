<?php namespace exface\PerformanceMonitor;

use exface\Core\CommonLogic\AbstractApp;

class PerformanceMonitorApp extends AbstractApp {
	private $monitor = null;
	
	protected function init(){
		$this->monitor = new PerformanceMonitor();
		$this->register_listeners();
	}
	
	/**
	 * @return PerformanceMonitor
	 */
	public function get_monitor(){
		return $this->monitor;
	}
	
	/**
	 * 
	 * @return PerformanceMonitorApp
	 */
	public function register_listeners(){
		$event_manager = $this->exface()->event_manager();
		$event_manager->add_listener('#.Action.Perform.Before', array($this->monitor, 'start_action'));
		$event_manager->add_listener('#.Action.Perform.After', array($this->monitor, 'stop_action'));
		return $this;
	}
	
}
?>