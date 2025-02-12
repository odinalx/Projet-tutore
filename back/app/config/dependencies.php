<?php

use Psr\Container\ContainerInterface;
use slv\core\services\auth\ServiceAuthInterface;
use slv\core\services\auth\ServiceAuth;
use app\providers\JWTManager;
use slv\infrastructure\PDO\auth\PdoAuthRepository;
use slv\core\repositoryInterfaces\auth\AuthRepositoryInterface;
use slv\core\repositoryInterfaces\organisme\OrganismeRepostitoryInterface;
use slv\core\services\organisme\ServiceOrganismeInterface;
use slv\infrastructure\PDO\organisme\PdoOrganismeRepository;
use slv\core\services\organisme\ServiceOrganisme;

return [
    
    'slv.pdo' => function (ContainerInterface $container) {
        $configPath = $container->get('slv.db.config'); // Récupère le chemin défini dans settings.php
        $config = parse_ini_file($configPath);
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },

    AuthRepositoryInterface::class => function (ContainerInterface $container) {
        return new PdoAuthRepository($container->get('slv.pdo'));
    },

    OrganismeRepostitoryInterface::class => function (ContainerInterface $container) {
        return new PdoOrganismeRepository($container->get('slv.pdo'));
    },

    ServiceAuthInterface::class => function (ContainerInterface $container) {
        $jwtManager = $container->get(JWTManager::class);
        return new ServiceAuth($container->get(AuthRepositoryInterface::class), $jwtManager);
    },

    ServiceOrganismeInterface::class => function (ContainerInterface $container) {
        return new ServiceOrganisme($container->get(OrganismeRepostitoryInterface::class));
    }
    
];

   