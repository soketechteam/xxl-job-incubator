<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Hyperf\XxlJob\Service\Executor;

use Hyperf\Engine\Channel;
use Hyperf\XxlJob\Requests\RunRequest;

class JobRunContent
{
    /**
     * @var RunRequest[]
     */
    protected static array $content = [];

    protected static array $channels = [];

    public static function getId(int $jobId): ?RunRequest
    {
        return self::$content[$jobId] ?? null;
    }

    public static function has(int $jobId): bool
    {
        return isset(self::$content[$jobId]);
    }

    public static function setJobId(int $jobId, RunRequest $runRequest): void
    {
        self::$content[$jobId] = $runRequest;
    }

    public static function remove(int $jobId, int $logId = 0): void
    {
        $channel = static::getChannel($logId);
        unset(self::$channels[$logId], self::$content[$jobId]);
        $channel->push(true);
    }

    public static function yield(int $logId, int $timeout = -1): bool
    {
        $result = static::getChannel($logId)->pop($timeout);
        return $result !== false;
    }

    private static function getChannel(int $logId): Channel
    {
        if (! isset(static::$channels[$logId])) {
            static::$channels[$logId] = new Channel(1);
        }

        return static::$channels[$logId];
    }
}
