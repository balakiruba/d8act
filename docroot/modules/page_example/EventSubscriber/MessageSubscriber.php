<?php

namespace Drupal\page_example\EventSubscriber;

use Drupal\page_example\DemoEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class MessageSubscriber implements EventSubscriberInterface {

  public function onMessageDisplay(DemoEvent $event) {
    $event->setMessage('This event was subscribed');
  }

  public function onAllResponses(FilterResponseEvent $event) {
    $response= $event->getResponse();
    $response->headers->set('Access-Control-Allow-Origin', '*');
  }

  static function getSubscribedEvents() {
    $events['custom_message_display'][] = array('onMessageDisplay',0);
    $events[ KernelEvents::RESPONSE][] = array('onAllResponses', 0);
    return $events;
  }
}
