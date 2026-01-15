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

namespace Hyperf\XxlJob\Service;

use Hyperf\XxlJob\ApiRequest;
use Hyperf\XxlJob\Config;
use Hyperf\XxlJob\Glue\GlueHandlerManager;
use Hyperf\XxlJob\JobHandlerManager;
use Hyperf\XxlJob\Listener\BootAppRouteListener;
use Hyperf\XxlJob\Service\Executor\JobExecutorCoroutine;
use Hyperf\XxlJob\Service\Executor\JobExecutorInterface;
use Hyperf\XxlJob\Service\Executor\JobExecutorProcess;
use Psr\Container\ContainerInterface;

class BaseService
{
    protected ContainerInterface $container;
    protected GlueHandlerManager $glueHandlerManager;
    protected JobHandlerManager $jobHandlerManager;
    protected ApiRequest $apiRequest;
    protected Config $config;

    public function __construct(
        ContainerInterface $container,
        GlueHandlerManager $glueHandlerManager,
        JobHandlerManager $jobHandlerManager,
        ApiRequest $apiRequest,
        Config $config
    ) {
        $this->container = $container;
        $this->glueHandlerManager = $glueHandlerManager;
        $this->jobHandlerManager = $jobHandlerManager;
        $this->apiRequest = $apiRequest;
        $this->config = $config;
    }

    public function kill(int $jobId, int $logId = 0, string $msg = ''): bool
    {
        $class = $this->getJobExecutor($jobId);
        return $class->kill($jobId, $logId, $msg);
    }

    public function isRun(int $jobId): bool
    {
        $class = $this->getJobExecutor($jobId);
        return $class->isRun($jobId);
    }

    protected function getJobExecutor(int $jobId): JobExecutorInterface
    {
        $filename = $this->config->getLogFileDir() . sprintf('jobId_%s.info', $jobId);
        if (file_exists($filename) && BootAppRouteListener::$AppStartTime <= filectime($filename)) {
            $classname = JobExecutorProcess::class;
        } else {
            $classname = JobExecutorCoroutine::class;
        }
        return $this->container->get($classname);
    }
}
