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

namespace Hyperf\XxlJob\Glue\Handlers;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\XxlJob\Config;
use Hyperf\XxlJob\JobHandlerManager;
use Hyperf\XxlJob\Logger\JobExecutorLoggerInterface;
use Hyperf\XxlJob\Service\Executor\JobExecutorCoroutine;
use Hyperf\XxlJob\Service\Executor\JobExecutorProcess;
use Psr\Container\ContainerInterface;

abstract class AbstractGlueHandler implements GlueHandlerInterface
{
    protected Config $config;
    protected ContainerInterface $container;
    protected JobHandlerManager $jobHandlerManager;
    protected JobExecutorLoggerInterface $jobExecutorLogger;
    protected StdoutLoggerInterface $stdoutLogger;
    protected JobExecutorProcess $jobExecutorProcess;
    protected JobExecutorCoroutine $jobExecutorCoroutine;

    public function __construct(
        ContainerInterface $container,
        JobHandlerManager $jobHandlerManager,
        JobExecutorLoggerInterface $jobExecutorLogger,
        StdoutLoggerInterface $stdoutLogger,
        JobExecutorProcess $jobExecutorProcess,
        JobExecutorCoroutine $jobExecutorCoroutine
    ) {
        $this->container = $container;
        $this->jobHandlerManager = $jobHandlerManager;
        $this->jobExecutorLogger = $jobExecutorLogger;
        $this->stdoutLogger = $stdoutLogger;
        $this->jobExecutorProcess = $jobExecutorProcess;
        $this->jobExecutorCoroutine = $jobExecutorCoroutine;
        $this->config = $container->get(Config::class);
    }
}
