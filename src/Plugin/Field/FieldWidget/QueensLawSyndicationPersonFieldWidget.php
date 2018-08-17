<?php

namespace Drupal\queenslaw_syndication\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\WidgetInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements a select list of people from the main Queen's Law site.
 *
 * @FieldWidget(
 *   id = "queenslaw_syndication_person",
 *   label = @Translation("Queen's Law Person"),
 *   field_types = {
 *     "queenslaw_syndication_person"
 *   }
 * )
 */

class QueensLawSyndicationPersonFieldWidget extends WidgetBase implements WidgetInterface {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'instructor' => 0,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = [
      'instructor' => [
        '#title' => $this->t('Instructors only'),
        '#type' => 'checkbox',
        '#default_value' => $this->getSetting('instructor'),
      ],
    ];
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $instructor = $this->getSetting('instructor');
    if ($instructor) $summary_text = $this->t('Only instructors are available.');
    else $summary_text = $this->t('All published people are available.');
    $summary[] = $summary_text;
    return $summary;
  }

  /**
   * Implements custom form element.
   *
   * @inheritdoc
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $instructor = $this->getSetting('instructor');
    $options = _queenslaw_syndication_people($instructor);
    $value = isset($items[$delta]->value) ? $items[$delta]->value : NULL;
    $element += [
      '#type' => 'select',
      '#default_value' => $value,
      '#options' => $options,
      '#empty_option' => $this->t('Please choose...'),
    ];
    return ['value' => $element];
  }

}
