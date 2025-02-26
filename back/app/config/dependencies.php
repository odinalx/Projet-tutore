<?php

use Psr\Container\ContainerInterface;

use slv\core\repositoryInterfaces\lieu\LieuRepositoryInterface;
use slv\core\services\auth\ServiceAuthInterface;
use slv\core\services\auth\ServiceAuth;
use app\providers\JWTManager;
use slv\core\services\lieu\ServiceLieuInterface;
use slv\infrastructure\PDO\auth\PdoAuthRepository;
use slv\core\repositoryInterfaces\auth\AuthRepositoryInterface;
use slv\core\repositoryInterfaces\encadrants\EncadrantRepositoryInterface;
use slv\core\repositoryInterfaces\formulaire\FormulaireRepositoryInterface;
use slv\core\repositoryInterfaces\organisme\OrganismeRepostitoryInterface;
use slv\core\repositoryInterfaces\sections\SectionRepositoryInterface;
use slv\core\services\organisme\ServiceOrganismeInterface;
use slv\infrastructure\PDO\organisme\PdoOrganismeRepository;
use slv\core\services\organisme\ServiceOrganisme;
use slv\core\services\authorization\AuthrzServiceInterface;
use slv\core\services\authorization\AuthrzService;
use slv\core\services\encadrants\ServiceEncadrant;
use slv\core\services\encadrants\ServiceEncadrantInterface;
use slv\core\services\formulaire\ServiceFormulaireInterface;
use slv\core\services\sections\ServiceSectionInterface;
use slv\infrastructure\PDO\sections\PdoSectionRepository;
use slv\core\services\sections\ServiceSection;
use slv\infrastructure\PDO\encadrants\PdoEncadrantRepository;
use slv\infrastructure\PDO\formulaire\PdoFormulaireRepository;
use slv\core\services\formulaire\ServiceFormulaire;
use slv\core\services\activite\ServiceActiviteInterface;
use slv\core\services\activite\ServiceActivite;
use slv\core\repositoryInterfaces\activite\ActiviteRepositoryInterface;
use slv\infrastructure\PDO\activite\PdoActiviteRepository;
use slv\core\services\lieu\ServiceLieu;
use slv\infrastructure\PDO\lieu\PdoLieuRepository;


return [
    
    'slv.pdo' => function (ContainerInterface $container) {
        $configPath = $container->get('slv.db.config'); // Récupère le chemin défini dans settings.php
        $config = parse_ini_file($configPath);
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },

    AuthrzServiceInterface::class => function (ContainerInterface $container) {
        return new AuthrzService($container->get(AuthRepositoryInterface::class), $container->get(SectionRepositoryInterface::class), $container->get(FormulaireRepositoryInterface::class));
    },

    AuthRepositoryInterface::class => function (ContainerInterface $container) {
        return new PdoAuthRepository($container->get('slv.pdo'));
    },

    OrganismeRepostitoryInterface::class => function (ContainerInterface $container) {
        return new PdoOrganismeRepository($container->get('slv.pdo'));
    },

    SectionRepositoryInterface::class => function (ContainerInterface $container) {
        return new PdoSectionRepository($container->get('slv.pdo'), $container->get(OrganismeRepostitoryInterface::class), $container->get(AuthRepositoryInterface::class));
    },

    EncadrantRepositoryInterface::class => function (ContainerInterface $container) {
        return new PdoEncadrantRepository($container->get('slv.pdo'));
    },

    FormulaireRepositoryInterface::class => function (ContainerInterface $container) {
        return new PdoFormulaireRepository($container->get('slv.pdo'));
    },

    ActiviteRepositoryInterface::class => function (ContainerInterface $container) {
        return new PdoActiviteRepository($container->get('slv.pdo'));
    },

    LieuRepositoryInterface::class => function (ContainerInterface $container) {
        return new PdoLieuRepository($container->get('slv.pdo'));
    },

    //////////////////////////////////////////
    // Services
    //////////////////////////////////////////

    ServiceAuthInterface::class => function (ContainerInterface $container) {
        $jwtManager = $container->get(JWTManager::class);
        return new ServiceAuth($container->get(AuthRepositoryInterface::class), $jwtManager);
    },

    ServiceOrganismeInterface::class => function (ContainerInterface $container) {
        return new ServiceOrganisme($container->get(OrganismeRepostitoryInterface::class));
    },

    ServiceSectionInterface::class => function (ContainerInterface $container) {
        return new ServiceSection($container->get(SectionRepositoryInterface::class));
    },

    ServiceEncadrantInterface::class => function (ContainerInterface $container) {
        return new ServiceEncadrant($container->get(EncadrantRepositoryInterface::class));
    },

    ServiceFormulaireInterface::class => function (ContainerInterface $container) {
        return new ServiceFormulaire($container->get(FormulaireRepositoryInterface::class));
    },

    ServiceActiviteInterface::class => function(ContainerInterface $container) {
        return new ServiceActivite($container->get(ActiviteRepositoryInterface::class));
    },

    ServiceLieuInterface::class => function (ContainerInterface $container) {
        return new ServiceLieu($container->get(LieuRepositoryInterface::class));
    }
    
];

   