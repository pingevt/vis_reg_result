<?php

namespace Drupal\Tests\external_site_monitor\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\esm_test_blc\SiteCrawlerController;

/**
 *
 */
class VisRegRsultTests extends UnitTestCase {

  protected $siteCrawler;

  /**
   * Before a test method is run, setUp() is invoked.
   * Create new unit object.
   */
  public function setUp() {
    // $this->siteCrawler = new SiteCrawlerController();
  }

  /**
   * Test staic vars.
   */
  public function testCheckStaticVars() {
    $this->assertTrue(TRUE);
  }

  /**
   * Once test method has finished running, whether it succeeded or failed, tearDown() will be invoked.
   * Unset the  object.
   */
  public function tearDown() {

  }

}
