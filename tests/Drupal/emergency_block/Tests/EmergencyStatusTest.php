<?php


/**
 * @file
 * Contains \Drupal\emergency_block\Tests\EmergencyStatusTest.
 */

namespace Drupal\emergency_block\Tests;

use Drupal\Tests\UnitTestCase;

use Drupal\emergency_block\EmergencyStatus;

/**
 * Tests the Emergency block status.
 */
class EmergencyStatusTest extends UnitTestCase {

  /**
   * The stubbed config factory object.
   *
   * @var \PHPUnit_Framework_MockObject_MockBuilder
   */
  protected $configFactory;

  public static function getInfo() {
    return array(
      'name' => 'Emergency Status test',
      'description' => 'Tests the emergency status service',
      'group' => 'Emergency block',
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->configFactory = $this->getConfigFactoryStub(
      array(
        'emergency_block.weather' => array(
          'weather_station' => '',
          'threshold' => 0,
          'message' => 'Cold',
        ),
      )
    );

    // Autoloading is not working for contrib. Load our class to test.
    // See https://drupal.org/node/2025883
    //include_once DRUPAL_ROOT . '/modules/emergency_block/lib/Drupal/emergency_block/Weather.php';
  }

  /**
   * Tests correct behavior of the reason-determination for admin.
   */
  public function testReasonAdmin() {
    // Set a mock class for the state container.
    // See http://phpunit.de/manual/current/en/test-doubles.html
    $state_stub = $this
      ->getMockBuilder('\Drupal\Core\KeyValueStore\StateInterface')
      ->getMock();

    $state_stub->expects($this->any())
      ->method('get')
      ->with($this->equalTo('emergency_block.status'))
            ->will($this->returnValue(TRUE));

    $weather_stub = $this
      ->getMockBuilder('Drupal\emergency_block\Weather')
      ->disableOriginalConstructor()
      ->getMock();

    $weather_stub->expects($this->never())
      ->method('isTooCold');

    $emergency = new EmergencyStatus($state_stub, $weather_stub);
    $this->assertEquals('admin', $emergency->getReason());
  }

  /**
   * Tests correct behavior of the reason-determination for weather-reasons.
   */
  public function testReasonWeather() {
    // Set a mock class for the state container.
    // See http://phpunit.de/manual/current/en/test-doubles.html
    $state_stub = $this
      ->getMockBuilder('\Drupal\Core\KeyValueStore\StateInterface')
      ->getMock();

    $state_stub->expects($this->any())
      ->method('get')
      ->with($this->equalTo('emergency_block.status'))
            ->will($this->returnValue(FALSE));

    $weather_stub = $this
      ->getMockBuilder('Drupal\emergency_block\Weather')
      ->disableOriginalConstructor()
      ->getMock();

    $weather_stub->expects($this->once())
      ->method('isTooCold')
      ->will($this->returnValue(TRUE));

    $emergency = new EmergencyStatus($state_stub, $weather_stub);
    $this->assertEquals('weather', $emergency->getReason());
  }

  /**
   * Tests correct behavior of the reason-determination for no warning.
   */
  public function testReasonNone() {
    // Set a mock class for the state container.
    // See http://phpunit.de/manual/current/en/test-doubles.html
    $state_stub = $this
      ->getMockBuilder('\Drupal\Core\KeyValueStore\StateInterface')
      ->getMock();

    $state_stub->expects($this->any())
      ->method('get')
      ->with($this->equalTo('emergency_block.status'))
            ->will($this->returnValue(FALSE));

    $weather_stub = $this
      ->getMockBuilder('Drupal\emergency_block\Weather')
      ->disableOriginalConstructor()
      ->getMock();

    $weather_stub->expects($this->once())
      ->method('isTooCold')
      ->will($this->returnValue(FALSE));

    $emergency = new EmergencyStatus($state_stub, $weather_stub);
    $this->assertEquals('', $emergency->getReason());
  }

}
