<?php

namespace iMemento\Clients\Handlers;

use GuzzleHttp\Handler\CurlMultiHandler;

/**
 * We will define this handler as a singleton so that
 * we will use the same instance when placing calls.
 * This will lead to simultaneous async calls being
 * handled together rather than independent
 */
class MultiHandler extends CurlMultiHandler
{

}
