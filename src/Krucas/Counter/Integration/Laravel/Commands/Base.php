<?php namespace Krucas\Counter\Integration\Laravel\Commands;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Krucas\Counter\Counter;

abstract class Base implements ShouldBeQueued, SelfHandling
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Counter key.
     *
     * @var string
     */
    protected $key;

    /**
     * Value to use.
     *
     * @var float|int
     */
    protected $value;

    /**
     * Date period for value.
     *
     * @var \DatePeriod|null
     */
    protected $period = null;

    /**
     * Queue name to push command to.
     *
     * @var string|null
     */
    protected $queue = null;

    /**
     * Method name to be called.
     * Available values: set, increment, decrement.
     *
     * @var null
     */
    protected $method = null;

    /**
     * Array of available methods.
     *
     * @var array
     */
    private $availableMethods = array('set', 'increment', 'decrement');

    /**
     * @param string $key
     * @param float|int $value
     * @param \DatePeriod $period
     */
    public function __construct($key, $value, \DatePeriod $period = null)
    {
        $this->key = $key;
        $this->value = $value;
        $this->period = $period;
    }

    /**
     * Set queue name to dispatch commands to.
     *
     * @param string $queue Queue name.
     * @return \Krucas\Counter\Integration\Laravel\Commands\Base
     */
    public function setQueue($queue)
    {
        $this->queue = $queue;

        return $this;
    }

    /**
     * Execute the command.
     *
     * @param \Krucas\Counter\Counter $counter
     * @return void
     */
    public function handle(Counter $counter)
    {
        if (!is_null($this->method) && $this->isMethodAvailable($this->method)) {
            call_user_func_array(array($counter, $this->method), array($this->key, $this->value, $this->period));
        }
    }

    /**
     * Push command to queue.
     *
     * @param \Illuminate\Contracts\Queue\Queue $queue
     * @param \Krucas\Counter\Integration\Laravel\Commands\Base $command
     * @return void
     */
    public function queue(Queue $queue, Base $command)
    {
        if (!is_null($this->queue)) {
            $queue->pushOn($this->queue, $command);
        } else {
            $queue->push($command);
        }
    }

    /**
     * Determine if method is available.
     *
     * @param string $method name.
     * @return bool
     */
    private function isMethodAvailable($method)
    {
        return in_array($method, $this->availableMethods);
    }
}