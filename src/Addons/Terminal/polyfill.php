<?php

namespace Lia\Addons\Terminal
{
    function escapeshellarg($argument)
    {
        return ProcessUtils::escapeArgument($argument);
    }
}

namespace SebastianBergmann\Environment
{
    function escapeshellarg($input)
    {
        return \Lia\Addons\Terminal\escapeshellarg($input);
    }
}

namespace Symfony\Component\Console\Input
{
    function escapeshellarg($input)
    {
        return \Lia\Addons\Terminal\escapeshellarg($input);
    }
}

namespace Symfony\Component\HttpFoundation\File\MimeType
{
    function escapeshellarg($input)
    {
        return \Lia\Addons\Terminal\escapeshellarg($input);
    }
}

namespace Symfony\Component\Process
{
    function escapeshellarg($input)
    {
        return \Lia\Addons\Terminal\escapeshellarg($input);
    }
}
