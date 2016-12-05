<?php

/**
 * @file
 * Contains \Drupal\hook_init_replace\HookInitReplaceSubscriber.
 */

namespace Drupal\hook_init_replace\EventSubscriber;

use Drupal\Core\Session\AccountProxy;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Subscribes to the kernel request event to completely obliterate the default content.
 *
 * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
 *   The event to process.
 */
class HookInitReplaceSubscriber implements EventSubscriberInterface {

  /**
   * @var AccountProxy
   */
  protected $currentUser;

  /**
   * HookInitReplaceSubscriber constructor.
   *
   * @param AccountProxy $currentUser
   */
  public function __construct(AccountProxy $currentUser) {
    $this->currentUser = $currentUser;
  }

  /**
   * Logs message when simple page was viewed
   *
   * @param FilterResponseEvent $event
   *   The response event.
   */
  public function addAccessAllowOriginHeaders(FilterResponseEvent $event) {
    if (!$this->currentUser->id()) {
      $response = $event->getResponse();
      $response->headers->set('Access-Control-Allow-Origin', '*');
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(){
    $events[KernelEvents::RESPONSE][] = array('addAccessAllowOriginHeaders');
    return $events;
  }

}