<?php


namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Psr\Log\LoggerInterface;

class PasswordHashSubscriber implements EventSubscriberInterface
{
    /**
     * @param UserPasswordEncoderInterface
     */
    private $passwordEncoder;
	private $logger;
	
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, LoggerInterface $logger)
    {
        $this->passwordEncoder = $passwordEncoder;
		$this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            kernelEvents::VIEW => ['hashpassword', EventPriorities::PRE_WRITE]
        ];
    }

    public function hashPassword(GetResponseForControllerResultEvent $event)
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
		$this->logger->info($event->getRequest());
        if (!$user instanceof User || !in_array($method, [Request::METHOD_POST, Request::METHOD_PUT])) {
            return;
        }
		if (strpos($user->getPassword(), '$2y$13$') !== false){return;}
        $user->setPassword( 
            $this->passwordEncoder->encodePassword($user, $user->getPassword())
        );
    }

}