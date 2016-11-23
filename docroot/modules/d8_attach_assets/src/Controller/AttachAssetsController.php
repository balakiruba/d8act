<?php

/**
 * @file
 * Contains \Drupal\d8_attach_assets\Controller\AttachAssetsController.
 */

namespace Drupal\d8_attach_assets\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller routines for theme example routes.
 */
class AttachAssetsController extends ControllerBase {

  public function simple() {
    return [
      'example one' => [
        '#markup' => '<div> Markup Example </div>',
      ]
    ];
  }
}
