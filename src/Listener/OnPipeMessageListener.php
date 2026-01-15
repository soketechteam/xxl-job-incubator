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

namespace Hyperf\XxlJob\Listener;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Utils\Coroutine;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Process\Event\PipeMessage as UserProcessPipeMessage;
use Hyperf\XxlJob\JobPipeMessage;
use Hyperf\XxlJob\Service\JobSerialExecutionService;

class OnPipeMessageListener implements ListenerInterface
{
    protected JobSerialExecutionService $jobSerialExecutionService;
    protected ConfigInterface $config;
    protected StdoutLoggerInterface $logger;

    public function __construct(
        JobSerialExecutionService $jobSerialExecutionService,
        ConfigInterface $config,
        StdoutLoggerInterface $logger
    ) {
        $this->jobSerialExecutionService = $jobSerialExecutionService;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * @return string[] returns the events that you want to listen
     */
    public function listen(): array
    {
        return [
            UserProcessPipeMessage::class,
        ];
    }

    /**
     * Handle the Event when the event is triggered, all listeners will
     * complete before the event is returned to the EventDispatcher.
     */
    public function process(object $event): void
    {
        if ($event instanceof UserProcessPipeMessage) {
            if ($event->data instanceof JobPipeMessage) {
                Coroutine::create(function () use ($event) {
                    $this->jobSerialExecutionService->handle($event->data->runRequest, $event->data->killJobId);
                });
            }
        }
    }
}
