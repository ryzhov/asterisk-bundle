<?php

namespace Ryzhov\Bundle\AsteriskBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Parameter;

use PAMI\Client\Impl\ClientImpl;

class AsteriskExtension extends Extension
{
    private $container;

    private $config = array();

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $this->config = $this->processConfiguration($configuration, $configs);
        $this->container = $container;

        $this->loadConnections();
        $this->loadClients();
    }

    protected function loadConnections()
    {
        foreach ($this->config['connections'] as $key => $params) {
            $this->container->setParameter(sprintf('asterisk.ami_connection.%s', $key), $params);
        }
    }

    protected function loadClients()
    {
        foreach ($this->config['clients'] as $key => $client) {
            
            $definition = new Definition(
                ClientImpl::class,
                ['options' => new Parameter(sprintf('asterisk.ami_connection.%s', $client['connection']))]
            );
            
            if ($client['logger_channel']) {
                $this->injectLogger($definition, $client['logger_channel']);
            }
            
            $clientServiceName = sprintf('asterisk.%s_client', $key);
            $this->container->setDefinition($clientServiceName, $definition);
        }
    }

    private function injectLogger(Definition $definition, $channel)
    {
        $definition->addTag('monolog.logger', array(
            'channel' => $channel
        ));
        $definition->addMethodCall('setLogger', array(new Reference('logger', ContainerInterface::IGNORE_ON_INVALID_REFERENCE)));
    }
}
