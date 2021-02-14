<?php

return [
    'local' => [
        'supervisor-1' => [
            'connection' => "redis",
            'queue' => "notifications,emails",
            'maxProcesses' => 10,
            'minProcesses' => 5,
            'delay' => 0,
            'memory' => 128,
            'timeout' => 60,
            'sleep' => 3,
            'maxTries' => 0,
            'balance' => "simple",
            'force' => false,
        ],
    ]
];
