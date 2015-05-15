<?php namespace Krucas\Counter\Integration\Laravel;

use Illuminate\Database\ConnectionInterface;
use Krucas\Counter\Contracts\Repository;

class DatabaseRepository implements Repository
{
    /**
     * Database connection.
     *
     * @var \Illuminate\Database\ConnectionInterface
     */
    protected $connection;

    /**
     * Database table to store values.
     *
     * @var string
     */
    protected $table;

    /**
     * @param \Illuminate\Database\ConnectionInterface $connection Database connection.
     * @param string $table Database table.
     */
    public function __construct(ConnectionInterface $connection, $table)
    {
        $this->connection = $connection;
        $this->table = $table;
    }

    /**
     * Set value for a key.
     *
     * @param string $key Counter key.
     * @param float|int $value Value to set.
     * @return void
     */
    public function set($key, $value)
    {
        if ($this->has($key)) {
            $this->connection
                ->table($this->table)
                ->where('key', $key)
                ->where('start', null)
                ->where('end', null)
                ->update(['value' => $value]);
        } else {
            $this->connection
                ->table($this->table)
                ->insert(array(
                    'key' => $key,
                    'value' => $value,
                    'start' => null,
                    'end' => null,
                ));
        }
    }

    /**
     * Set value for a key for a given date.
     *
     * @param string $key Counter key.
     * @param \DateTime $date Date.
     * @param float|int $value Value to set.
     * @return void
     */
    public function setFor($key, \DateTime $date, $value)
    {
        if ($this->hasFor($key, $date)) {
            $this->connection
                ->table($this->table)
                ->where('key', $key)
                ->where('start', $date)
                ->where('end', null)
                ->update(['value' => $value]);
        } else {
            $this->connection
                ->table($this->table)
                ->insert(array(
                    'key' => $key,
                    'value' => $value,
                    'start' => $date,
                    'end' => null,
                ));
        }
    }

    /**
     * Set value for a key for a given date range.
     *
     * @param string $key Counter key.
     * @param \DateTime $start Start date.
     * @param \DateTime $end End date.
     * @param float|int $value Value to set.
     * @return void
     */
    public function setForRange($key, \DateTime $start, \DateTime $end, $value)
    {
        if ($this->hasForRange($key, $start, $end)) {
            $this->connection
                ->table($this->table)
                ->where('key', $key)
                ->where('start', $start)
                ->where('end', $end)
                ->update(['value' => $value]);
        } else {
            $this->connection
                ->table($this->table)
                ->insert(array(
                    'key' => $key,
                    'value' => $value,
                    'start' => $start,
                    'end' => $end,
                ));
        }
    }

    /**
     * Get value for a key.
     *
     * @param string $key Counter key.
     * @return float|int|null
     */
    public function get($key)
    {
        return $this->connection
            ->table($this->table)
            ->where('key', $key)
            ->where('start', null)
            ->where('end', null)
            ->pluck('value');
    }

    /**
     * Get value for a key for a given date.
     *
     * @param string $key Counter key.
     * @param \DateTime $date Date.
     * @return float|int|null
     */
    public function getFor($key, \DateTime $date)
    {
        return $this->connection
            ->table($this->table)
            ->where('key', $key)
            ->where('start', $date)
            ->where('end', null)
            ->pluck('value');
    }

    /**
     * Get value for a key for a given date range.
     *
     * @param string $key Counter key.
     * @param \DateTime $start Start date.
     * @param \DateTime $end End date.
     * @return float|int|null
     */
    public function getForRange($key, \DateTime $start, \DateTime $end)
    {
        return $this->connection
            ->table($this->table)
            ->where('key', $key)
            ->where('start', $start)
            ->where('end', $end)
            ->pluck('value');
    }

    /**
     * Determine if value exists.
     *
     * @param string $key Counter key.
     * @return bool
     */
    public function has($key)
    {
        return $this->connection
            ->table($this->table)
            ->where('key', $key)
            ->where('start', null)
            ->where('end', null)
            ->count() == 1 ? true : false;
    }

    /**
     * Determine if value exists for a given date.
     *
     * @param string $key Counter key.
     * @param \DateTime $date Date.
     * @return bool
     */
    public function hasFor($key, \DateTime $date)
    {
        return $this->connection
            ->table($this->table)
            ->where('key', $key)
            ->where('start', $date)
            ->where('end', null)
            ->count() == 1 ? true : false;
    }

    /**
     * Determine if value exists for a given date range.
     *
     * @param string $key Counter key.
     * @param \DateTime $start Start date.
     * @param \DateTime $end End date.
     * @return bool
     */
    public function hasForRange($key, \DateTime $start, \DateTime $end)
    {
        return $this->connection
            ->table($this->table)
            ->where('key', $key)
            ->where('start', $start)
            ->where('end', $end)
            ->count() == 1 ? true : false;
    }

    /**
     * @param string $key Counter key.
     * @param float|int $value Value to increment.
     * @return void
     */
    public function increment($key, $value)
    {
        if ($this->has($key)) {
            $this->connection
                ->table($this->table)
                ->where('key', $key)
                ->where('start', null)
                ->where('end', null)
                ->increment('value', $value);
        } else {
            $this->set($key, $value);
        }
    }

    /**
     * @param string $key Counter key.
     * @param \DateTime $date Date.
     * @param float|int $value Value to increment.
     * @return void
     */
    public function incrementFor($key, \DateTime $date, $value)
    {
        if ($this->hasFor($key, $date)) {
            $this->connection
                ->table($this->table)
                ->where('key', $key)
                ->where('start', $date)
                ->where('end', null)
                ->increment('value', $value);
        } else {
            $this->setFor($key, $date, $value);
        }
    }

    /**
     * @param string $key Counter key.
     * @param \DateTime $start Start date.
     * @param \DateTime $end End date.
     * @param float|int $value Value to increment.
     * @return void
     */
    public function incrementForRange($key, \DateTime $start, \DateTime $end, $value)
    {
        if ($this->hasForRange($key, $start, $end)) {
            $this->connection
                ->table($this->table)
                ->where('key', $key)
                ->where('start', $start)
                ->where('end', $end)
                ->increment('value', $value);
        } else {
            $this->setForRange($key, $start, $end, $value);
        }
    }

    /**
     * @param string $key Counter key.
     * @param float|int $value Value to decrement.
     * @return void
     */
    public function decrement($key, $value)
    {
        if ($this->has($key)) {
            $this->connection
                ->table($this->table)
                ->where('key', $key)
                ->where('start', null)
                ->where('end', null)
                ->decrement('value', $value);
        } else {
            $this->set($key, -$value);
        }
    }

    /**
     * @param string $key Counter key.
     * @param \DateTime $date Date.
     * @param float|int $value Value to decrement.
     * @return void
     */
    public function decrementFor($key, \DateTime $date, $value)
    {
        if ($this->hasFor($key, $date)) {
            $this->connection
                ->table($this->table)
                ->where('key', $key)
                ->where('start', $date)
                ->where('end', null)
                ->decrement('value', $value);
        } else {
            $this->setFor($key, $date, -$value);
        }
    }

    /**
     * @param string $key Counter key.
     * @param \DateTime $start Start date.
     * @param \DateTime $end End date.
     * @param float|int $value Value to decrement.
     * @return void
     */
    public function decrementForRange($key, \DateTime $start, \DateTime $end, $value)
    {
        if ($this->hasForRange($key, $start, $end)) {
            $this->connection
                ->table($this->table)
                ->where('key', $key)
                ->where('start', $start)
                ->where('end', $end)
                ->decrement('value', $value);
        } else {
            $this->setForRange($key, $start, $end, -$value);
        }
    }

    /**
     * Remove value for a key.
     *
     * @param string $key Counter key.
     * @return void
     */
    public function remove($key)
    {
        $this->connection
            ->table($this->table)
            ->where('key', $key)
            ->where('start', null)
            ->where('end', null)
            ->delete();
    }

    /**
     * Remove value for a key for a given date.
     *
     * @param string $key Counter key.
     * @param \DateTime $date Date.
     * @return void
     */
    public function removeFor($key, \DateTime $date)
    {
        $this->connection
            ->table($this->table)
            ->where('key', $key)
            ->where('start', $date)
            ->where('end', null)
            ->delete();
    }

    /**
     * Remove value for a key for a given date range.
     *
     * @param string $key Counter key.
     * @param \DateTime $start Start date.
     * @param \DateTime $end End date.
     * @return void
     */
    public function removeForRange($key, \DateTime $start, \DateTime $end)
    {
        $this->connection
            ->table($this->table)
            ->where('key', $key)
            ->where('start', $start)
            ->where('end', $end)
            ->delete();
    }
}
