<?php

use Psr\Container\ContainerInterface;
return [
    
    'slv.pdo' => function (ContainerInterface $container) {
        $configPath = $container->get('slv.db.config'); // Récupère le chemin défini dans settings.php
        $config = parse_ini_file($configPath);
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },
    
];

   