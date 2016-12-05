<?php
namespace Drupal\page_example;
use Symfony\Component\EventDispatcher\Event;

class DemoEvent extends Event {
  protected $message;
  public function __construct($message) {
    $this->message = $message;
  }

  /**
   * @return mixed
   */
  public function getMessage() {
    return $this->message;
  }

  /**
   * @param mixed $message
   */
  public function setMessage($message) {
    $this->message = $message;
  }
}