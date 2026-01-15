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

namespace Hyperf\XxlJob;

use Hyperf\XxlJob\Requests\RunRequest;

class JobPipeMessage
{
    public ?RunRequest $runRequest = null;
    public int $killJobId = 0;
    public int $fromWorkerId = -1;

    public function __construct(?RunRequest $runRequest = null, int $killJobId = 0, int $fromWorkerId = -1)
    {
        $this->runRequest = $runRequest;
        $this->killJobId = $killJobId;
        $this->fromWorkerId = $fromWorkerId;
    }
}
