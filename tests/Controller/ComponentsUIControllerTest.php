<?php

namespace Drupal\repeat_ui\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the repeat_ui module.
 */
class ComponentsUIControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "repeat_ui ComponentsUIController's controller functionality",
      'description' => 'Test Unit for module repeat_ui and controller ComponentsUIController.',
      'group' => 'Other',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests repeat_ui functionality.
   */
  public function testComponentsUIController() {
    // Check that the basic functions of module repeat_ui.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
