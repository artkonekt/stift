<?php

function duration_secs_to_human_readable(int $seconds, $withSeconds = false)
{
    return app('stift.duration_humanizer')->secondsToHumanReadable($seconds, $withSeconds);
}
