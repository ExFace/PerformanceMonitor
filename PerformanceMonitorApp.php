<?php
namespace exface\PerformanceMonitor;

use exface\Core\CommonLogic\AbstractApp;

class PerformanceMonitorApp extends AbstractApp
{

    private $monitor = null;

    protected function init()
    {
        $this->monitor = new PerformanceMonitor();
        $this->registerListeners();
    }

    /**
     *
     * @return PerformanceMonitor
     */
    public function getMonitor()
    {
        return $this->monitor;
    }

    /**
     *
     * @return PerformanceMonitorApp
     */
    public function registerListeners()
    {
        $event_manager = $this->getWorkbench()->eventManager();
        $event_manager->addListener('#.Action.Perform.Before', array(
            $this->monitor,
            'startAction'
        ));
        $event_manager->addListener('#.Action.Perform.After', array(
            $this->monitor,
            'stopAction'
        ));
        return $this;
    }
}
?>