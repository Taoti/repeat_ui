<?php

namespace Drupal\repeat_ui\Controller;

use Drupal\Core\Controller\ControllerBase;
use RepeatCli\Components;
use Drupal\Core\Render\Markup;
use Symfony\Component\Yaml\Yaml;
use ScssPhp\ScssPhp\Compiler;

define('LIPSUM', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
 dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
 proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');

/**
 * Class ComponentsUIController.
 */
class ComponentsUIController extends ControllerBase {

  /**
   * The repeat components source folder.
   *
   * @var string
   */
  protected $sourceDirectory;

  /**
   * Controller constructor.
   */
  public function __construct()
  {
      $this->sourceDirectory = DRUPAL_ROOT . '/../vendor/taoti/repeat/drupal';
  }

  /**
   * List Components.
   *
   * @return string
   *   Return Hello string.
   */
  public function list() {
    // Load the Twig theme service.
    /** @var \Twig_Environment $twig_service */
    $twig_service = \Drupal::service('twig');
    $scss = new Compiler();

    $scss_var_defaults = '
      $blue: #339;
      $gray: #999;
      $white: #fff;
      $box-shadow: 0 0 #ccc;
      $width--m: 100%;
      $width--l: 100%;';

    // Parse repeate components directory.
    $components = array_diff(scandir($this->sourceDirectory), ['..', '.']);
    foreach ($components as $component_name) {
      $template_dir = $this->sourceDirectory . '/' . $component_name .
        '/templates/canonical';
      $scss_dir = $this->sourceDirectory . '/' . $component_name .
        '/sass/3.components';

      // Get the twig dir.
      $template_filename = reset(array_diff(scandir($template_dir),
        ['..', '.']));
      $template_content = file_get_contents($template_dir . '/' .
        $template_filename);

      // @TODO: load context variables... somehow...
      $context = [
        'name' => $component_name,
      ];
      // @TODO: load twig extension.
      if (strpos($template_content, 'field_target_entity') === FALSE &&
          strpos($template_content, 'field_value') === FALSE) {
        if ($component_name == 'accordion'){
          // Get the styles.
          $scss_filename = reset(array_diff(scandir($scss_dir),
            ['..', '.']));
          if (!empty($scss_filename)) {

            $scss_content = file_get_contents($scss_dir . '/' .
              $scss_filename);
            $css = $scss->compile($scss_var_defaults . $scss_content);
          }
          $context['classes'] = [
            'accordion',
            'accordion--collapsed',
            'paragraph',
            'paragraph--type--accordion-item',
            'paragraph--view-mode--default',
          ];
          $context['section_title'] = 'Section Title';
          $context['title'] = 'Title';
          $context['accordion_content'] = LIPSUM;
          $build[$component_name] = [
            '#type' => 'inline_template',
            '#template' => '<h2>{{name}}</h2>' .
              '<style>' . $css . '</style>' .
              $template_content,
            '#context' => $context,
          ];
        }
        else {
          $build[$component_name] = [
            '#type' => 'inline_template',
            '#template' => '<h2>{{name}}</h2><br>Canoncal template TBD..',
            '#context' => $context,
          ];
        }
      }
      else {
        $build[$component_name] = [
          '#type' => 'inline_template',
          '#template' => '<h2>{{name}}</h2><br>Preview coming soon..',
          '#context' => $context,
        ];
      }
    }

    return $build;
  }

}
