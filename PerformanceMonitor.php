<?php namespace exface\PerformanceMonitor;

use Symfony\Component\Stopwatch\Stopwatch;
use exface\Core\CommonLogic\UxonObject;
use exface\Core\Events\ActionEvent;
use exface\Core\Events\DataConnectionEvent;
use exface\Core\Interfaces\Actions\ActionInterface;

class PerformanceMonitor {
	private $stopwatch = null;
	private $offset = 0;
	
	public function __construct(){
		$this->stopwatch = new Stopwatch();
		$this->start();
	}
	
	public function start_action(ActionEvent $event){
		$this->stopwatch->start($event->get_action()->get_id());
	}
	
	public function stop_action(ActionEvent $event){
		try {
			$this->stopwatch->stop($event->get_action()->get_id());
		} catch (\Exception $e){
			// Do nothing if stopping goes wrong...
		}
	}
	
	public function start_data_source_query(DataConnectionEvent $event){
		$category = new UxonObject();
		$category->set_property('query', $event->get_current_query());
		$this->stopwatch->start($event->get_current_query(), $category->to_json());
	}
	
	public function stop_data_source_query(DataConnectionEvent $event){
		$this->stopwatch->stop($event->get_current_query());
	}
	
	public function export_uxon_object(){
		$uxon = new UxonObject();
		$uxon->set_property('TOTAL', $this->stopwatch->getEvent('TOTAL')->getDuration());
		return $uxon;
	}
		
	public function start(){
		$this->stopwatch->start('TOTAL');
	}
	
	public function stop(){
		$this->stopwatch->stop('TOTAL');
	}
	
	public function get_offset() {
		return $this->offset;
	}
	
	/**
	 * 
	 * @param integer $milliseconds
	 */
	public function set_offset($milliseconds) {
		$this->offset = $milliseconds;
		return $this;
	}
	
	public function get_action_duration(ActionInterface $action){
		try {
			$result = $this->stopwatch->getEvent($action->get_id())->getDuration();
		} catch (\Exception $e){
			$result = NULL;
		}
		return $result;
	}
  
}
?>