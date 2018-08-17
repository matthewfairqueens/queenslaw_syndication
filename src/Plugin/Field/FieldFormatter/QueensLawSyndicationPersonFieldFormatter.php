<?php

namespace Drupal\queenslaw_syndication\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'queenslaw_syndication_person' formatter.
 *
 * @FieldFormatter(
 *   id = "queenslaw_syndication_person",
 *   label = @Translation("Queen's Law person"),
 *   field_types = {
 *     "queenslaw_syndication_person"
 *   }
 * )
 */

class QueensLawSyndicationPersonFieldFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'display' => 'name',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $default_display = $this->getSetting('display');
    $options = [
      'name' => 'Name',
      'full' => 'Full',
    ];
    $elements = parent::settingsForm($form, $form_state);
    $elements['display'] = [
      '#type' => 'select',
      '#title' => t('Display'),
      '#options' => $options,
      '#default_value' => $default_display,
    ];
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $settings = $this->getSettings();
    if (isset($settings['display']) && ($settings['display'] == 'name')) $summary_text = $this->t('Displays the linked person name.');
    else $summary_text = $this->t('Displays the person. The view mode is provided by the syndication source.');
    $summary = [$summary_text];
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
    $settings = $this->getSettings();
    foreach ($items as $delta => $item) {
      $markup = _queenslaw_syndication_person_display($item->value, $settings['display']);
      $element[$delta] = [
        '#theme' => 'queenslaw_syndication_person',
        '#markup' => $markup,
      ];
    }
    return $element;
  }

}
