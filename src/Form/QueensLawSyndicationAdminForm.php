<?php

namespace Drupal\queenslaw_syndication\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides a form for storing syndication settings.
 *
 * @ingroup queenslaw_syndication
 */
class QueensLawSyndicationAdminForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'queenslaw_syndication_admin';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'queenslaw_syndication.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {
    $config = $this->config('queenslaw_syndication.settings');
    $form['base_url'] = [
      '#type' => 'url',
      '#title' => $this->t('Base URL'),
      '#description' => $this->t('The base URL from which syndicated content is loaded. Do not include a trailing slash.'),
      '#size' => 40,
      '#maxlength' => 255,
      '#default_value' => $config->get('base_url'),
      '#required' => TRUE,
    ];
    $form['username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#description' => $this->t('If HTTP authentication is required to access the source data, provide the username.'),
      '#size' => 40,
      '#maxlength' => 255,
      '#default_value' => $config->get('username'),
      '#required' => FALSE,
    ];
    $form['password'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Password'),
      '#description' => $this->t('If HTTP authentication is required to access the source data, provide the password.'),
      '#size' => 40,
      '#maxlength' => 255,
      '#default_value' => $config->get('password'),
      '#required' => FALSE,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    foreach ($values as $key => $value) $this->config('queenslaw_syndication.settings')->set($key, $value)->save();
    drupal_flush_all_caches();
    drupal_set_message($this->t('The configuration was updated.'));
  }

}
