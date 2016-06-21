<?php
namespace LearningContextClientModuleDoctrineORM;

use LearningContextClient\Storage\StorageInterface as LCStorage;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
return array(
	'service_manager' => array(
		'invokables' => array(
		),
		'factories' => array(
			LCStorage::class => Service\DoctrineStorage::class,
			Storage\DoctrineStorage::class => Service\DoctrineStorage::class,
		),
	),
	
    'doctrine' => array(
        'driver' => array(
            'lcclient_entity' => array(
                'class' => XmlDriver::class,
                'paths' => __DIR__ . '/xml/lcclient'
            ),

            'orm_default' => array(
                'drivers' => array(
                    'LearningContextClient\Token'  => 'lcclient_entity'
                )
            )
        )
    ),
);