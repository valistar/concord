<?php
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

$container->setDefinition('TrionDumpParser', new Definition(
'Concord\ImportBundle\TrionDumpParser', array(new Reference('logger'), new Reference('doctrine.orm.entity_manager'))
));