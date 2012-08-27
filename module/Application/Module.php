<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Doctrine\Common\Annotations\AnnotationRegistry;

class Module
{	
    public function onBootstrap($e)
    {
		$this->initDoctrine($e);
				
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
					
        $application = $e->getApplication();
        $sm = $application->getServiceManager();

        $controllerLoader = $sm->get('ControllerLoader');

        // Add initializer to Controller Service Manager that check if controllers needs entity manager injection
        $controllerLoader->addInitializer(function ($instance) use ($sm) {
			if (method_exists($instance, 'setEntityManager')) {
				$instance->setEntityManager($sm->get('doctrine.entitymanager.orm_default'));
			}
		});
    }
	
	/**
	 *
	 * @param type $e 
	 */
	public function initDoctrine($e)
	{		
        // Register the Gedmo plugin to Doctrine
    	$namespace = 'Gedmo\Mapping\Annotation';
		$lib = 'vendor/gedmo/doctrine-extensions/lib';
		AnnotationRegistry::registerAutoloadNamespace($namespace, $lib);		
        
        // Get the Doctrine Event Manager to attach the Gedmo plugins
		$evm = $e->getApplication()->getServiceManager()->get('doctrine.eventmanager.orm_default');		
		
		// Enable sluggable
		$sluggableListener = new \Gedmo\Sluggable\SluggableListener();
		$evm->addEventSubscriber($sluggableListener);
		
		// Enable timestampable
		$timestampableListener = new \Gedmo\Timestampable\TimestampableListener();
		$evm->addEventSubscriber($timestampableListener);
		
		// Enable tree
		$treeListener = new \Gedmo\Tree\TreeListener;
		$evm->addEventSubscriber($treeListener);
	}

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    'Gedmo' => 'vendor/gedmo/doctrine-extensions/lib/Gedmo'
                ),
            ),
        );
    }
}
