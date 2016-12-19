<?php

namespace A5sys\MonitorBundle\Listener;

use A5sys\MonitorBundle\Converter\DataTypeConverter;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 *
 *
 */
class RequestListener implements EventSubscriberInterface
{
    protected $enabled;
    protected $typesEnabled;
    protected $requestId;
    protected $requestNumber;
    protected $stopWatch;
    protected $tokenStorage;
    protected $logger;

    /**
     *
     * @param TokenStorage $tokenStorage
     * @param Logger       $logger
     * @param boolean      $enabled
     * @param []           $typesEnabled
     */
    public function __construct(TokenStorage $tokenStorage, Logger $logger, $enabled, $typesEnabled)
    {
        $this->requestNumber = 0;
        $this->stopWatch = new Stopwatch();
        $this->tokenStorage = $tokenStorage;
        $this->logger = $logger;
        $this->enabled = $enabled;
        $this->typesEnabled = $typesEnabled;
    }

    /**
     *
     * @param GetResponseForControllerResultEvent $event A GetResponseForControllerResultEvent instance
     *
     * @return Response The json response
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $this->requestNumber++;

        if ($this->requestNumber === 1) {
            $this->requestId = uniqid();
            $this->stopWatch->start('request');
            $this->logRequest(DataTypeConverter::START_TYPE, date('U'));
            $this->logRequest(DataTypeConverter::URL_TYPE, $request->getUri());
            $this->logRequest(DataTypeConverter::USER_TYPE, $this->getUserId());
        }
    }

    /**
     *
     * @param FinishRequestEvent $event
     */
    public function onFinishRequest(FinishRequestEvent $event)
    {
        $this->requestNumber--;
        if ($this->requestNumber === 0) {
            $this->stopWatch->stop('request');
            $event = $this->stopWatch->getEvent('request');
            $this->logRequest(DataTypeConverter::STOP_TYPE, date('U'));
            $this->logRequest(DataTypeConverter::DURATION_TYPE, $event->getDuration());
            $this->logRequest(DataTypeConverter::MEMORY_TYPE, $event->getMemory() / 1024);
        }
    }

    /**
     * List the subscribed events
     *
     * @return multitype:string multitype:string number
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => 'onKernelRequest',
            KernelEvents::FINISH_REQUEST => 'onFinishRequest',
        );
    }

    /**
     *
     * @param string $type
     * @param string $value
     */
    protected function logRequest($type, $value)
    {
        if ($this->typesEnabled[$type]) {
            $str = '[request]|['.$this->requestId.']|['.$type.']|['.$value.']';
            $this->logger->info($str);
        }
    }

    /**
     * @return string The username
     */
    protected function getUserId()
    {
        $token = $this->tokenStorage->getToken();
        if ($token) {
            $user = $token->getUser();
            if ($user instanceof \Symfony\Component\Security\Core\User\User) {
                $userId = $user->getUsername();
            } else {
                $userId = $user;
            }
        } else {
            $userId = 'NULL';
        }

        return $userId;
    }
}
