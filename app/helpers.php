<?php

/**
 * Start a clockwork event
 *
 * @param $name
 * @param $description
 */
function cs($name, $description)
{
	Clockwork::startEvent($name, $description);
}

/**
 * End a clockwork event
 *
 * @param $name
 */
function ce($name)
{
	Clockwork::endEvent($name);
}

/**
 * Log a message to clockwork
 *
 * @param $message
 */
function cl($message)
{
	Clockwork::info($message);
}
