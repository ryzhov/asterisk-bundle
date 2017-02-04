<?php
/**
 * @author Aleksandr N. Ryzhov <a.n.ryzhov@gmail.com>
 */

namespace Ryzhov\Bundle\AsteriskBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class EventHandlerPass implements CompilerPassInterface
{
    const EVENT_HANDLER_TAG = 'asterisk.ami_event_handler';

    public function process(ContainerBuilder $container)
    {
        $handlers = $container->findTaggedServiceIds(self::EVENT_HANDLER_TAG);

        foreach ($handlers as $id => $tags) {
            foreach ($tags as $attributes) {
                if (isset($attributes['client'])) {
                    $events = isset($attributes['events']) ? $attributes['events'] : [];
                    $client = $container->findDefinition($attributes['client']);
                    $client->addMethodCall('registerEventListener', [new Reference($id), $events]);
                }
            }
        }
    }
}
