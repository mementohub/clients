<?php

namespace iMemento\Clients\Handlers;

use GuzzleHttp\HandlerStack as GuzzleHandlerStack;

/**
 * We will bind this class so that we can resolve it
 * with our custom handlers
 */
class HandlerStack extends GuzzleHandlerStack
{

}
