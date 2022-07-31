<?php

declare(strict_types=1);

namespace App\Queues\Socket;

use App\Exceptions\OwinException;
use App\Utils\Code;
use Illuminate\Support\Facades\Log;

class Socket
{
    protected string $ip;
    protected int $port;

    public function send(string $parameter): string
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($socket, SOL_SOCKET, SO_LINGER, ['l_linger' => 0, 'l_onoff' => 1]);
        socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, ['sec' => 10, 'usec' => 0]);
        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, ['sec' => 10, 'usec' => 0]);

        socket_connect($socket, $this->ip, $this->port) or throw new OwinException(
            socket_strerror(socket_last_error())
        );

        $error = socket_last_error();
//        if ($error != SOCKET_EINPROGRESS && $error != SOCKET_EALREADY) throw new OwinException(sprintf(Code::message('S0001'), socket_strerror($error)));
        if (socket_write($socket, $parameter, strlen($parameter)) === false) {
            throw new OwinException(sprintf(Code::message('S0002'), socket_strerror(socket_last_error($socket))));
        }
        if (($response = socket_read($socket, 1024)) === false) {
            throw new OwinException(sprintf(Code::message('S0003'), socket_strerror(socket_last_error($socket))));
        }
        socket_close($socket);

        Log::channel('response')->info(sprintf('IP: %s, arkserver response: %s', $this->ip, $response));

        return $response;
    }
}
