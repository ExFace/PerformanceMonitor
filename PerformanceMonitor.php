<?php
namespace exface\PerformanceMonitor;

use Symfony\Component\Stopwatch\Stopwatch;
use exface\Core\CommonLogic\UxonObject;
use exface\Core\Events\ActionEvent;
use exface\Core\Events\DataConnectionEvent;
use exface\Core\Interfaces\Actions\ActionInterface;

class PerformanceMonitor
{

    private $stopwatch = null;

    private $offset = 0;

    public function __construct()
    {
        $this->stopwatch = new Stopwatch();
        $this->start();
    }

    public function startAction(ActionEvent $event)
    {
        $this->stopwatch->start($event->getAction()->getId());
    }

    public function stopAction(ActionEvent $event)
    {
        try {
            $this->stopwatch->stop($event->getAction()->getId());
        } catch (\Exception $e) {
            // Do nothing if stopping goes wrong...
        }
    }

    public function startDataSourceQuery(DataConnectionEvent $event)
    {
        $category = new UxonObject();
        $category->setProperty('query', $event->getCurrentQuery());
        $this->stopwatch->start($event->getCurrentQuery(), $category->toJson());
    }

    public function stopDataSourceQuery(DataConnectionEvent $event)
    {
        $this->stopwatch->stop($event->getCurrentQuery());
    }

    public function exportUxonObject()
    {
        $uxon = new UxonObject();
        $uxon->setProperty('TOTAL', $this->stopwatch->getEvent('TOTAL')->getDuration());
        return $uxon;
    }

    public function start()
    {
        $this->stopwatch->start('TOTAL');
    }

    public function stop()
    {
        $this->stopwatch->stop('TOTAL');
    }

    public function getOffset()
    {
        return $this->offset;
    }

    /**
     *
     * @param integer $milliseconds            
     */
    public function setOffset($milliseconds)
    {
        $this->offset = $milliseconds;
        return $this;
    }

    public function getActionDuration(ActionInterface $action)
    {
        try {
            $result = $this->stopwatch->getEvent($action->getId())->getDuration();
        } catch (\Exception $e) {
            $result = NULL;
        }
        return $result;
    }
}
?>