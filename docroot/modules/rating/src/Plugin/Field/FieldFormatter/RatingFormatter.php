<?php

/**
 * @file
 * Contains \Drupal\rating\Plugin\Field\FieldFormatter\RatingFormatter.
 */

namespace Drupal\rating\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
//use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatter;

/**
 * Plugin implementation of the 'number_decimal' formatter.
 *
 * @FieldFormatter(
 *   id = "rating_formatter",
 *   label = @Translation("Rating Formatter"),
 *   field_types = {
 *     "decimal"
 *   }
 * )
 */
class RatingFormatter extends FormatterBase  {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
//    $elements = parent::viewElements($items);
//    foreach ($elements as &$element) {
//      $element['#theme'] = 'image_title_caption_formatter';
//    }
//    return $elements;
    $element = array();
    foreach ($items as $delta => $item) {
      $input = $item->getValue();
      $value = ($input['value']);

      // Render each element as markup.
      $element[$delta] = array(
        //'#type' => 'markup',
        '#theme' => 'rating_number_formatter',
        '#value' => $value*20,
        '#attached' => array('library'=> array('rating/rating')),
      );
    }

    return $element;

  }

}
