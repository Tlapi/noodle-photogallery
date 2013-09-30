<?php

namespace NoodlePhotogallery;

return array(
	'doctrine' => array(
        'driver' => array(
            // overriding zfc-user-doctrine-orm's config
            'noodle_photogallery_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => __DIR__ . '/../src/NoodlePhotogallery/Entity',
            ),

            'orm_default' => array(
                'drivers' => array(
                    'NoodlePhotogallery\Entity' => 'noodle_photogallery_entity',
                ),
            ),
        ),
    ),
);
