<?php

namespace eZ\Publish\Profiler\Executor;

use eZ\Publish\Profiler\Executor;
use eZ\Publish\Profiler\Field;
use eZ\Publish\Profiler\Actor;
use eZ\Publish\Profiler\Logger;
use eZ\Publish\Profiler\Aborter;

use eZ\Publish\SPI\Persistence;
use eZ\Publish\API\Repository\FieldTypeService;

class SPI extends Executor
{
    public function __construct( Persistence\Handler $handler, FieldTypeService $fieldTypeService, Logger $logger )
    {
        $this->createActorVisitor = new SPI\CreateActorVisitor( $handler, $fieldTypeService );
        $this->subtreeActorVisitor = new SPI\SubtreeActorVisitor( $handler );
        $this->logger = $logger;
    }

    public function visitActor( Actor $actor )
    {
        switch ( true )
        {
            case $actor instanceof Actor\Create:
                return $this->createActorVisitor->visit( $actor );

            case $actor instanceof Actor\SubtreeView:
                return $this->subtreeActorVisitor->visit( $actor );

            default:
                throw new \RuntimeException(
                    "No visitor for: " . get_class( $actor )
                );
        }
    }

}

