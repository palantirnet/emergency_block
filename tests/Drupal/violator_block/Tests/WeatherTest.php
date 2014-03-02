<?php


/**
 * @file
 * Contains \Drupal\violator_block\Tests\WeatherTest.
 */

namespace Drupal\violator_block\Tests;

use Drupal\Tests\UnitTestCase;

use Drupal\violator_block\Weather;

/**
 * Tests the Drupalgotchi block.
 */
class HelloBlockTest extends UnitTestCase {

  /**
   * The stubbed config factory object.
   *
   * @var \PHPUnit_Framework_MockObject_MockBuilder
   */
  protected $configFactory;

  public static function getInfo() {
    return array(
      'name' => 'Drupalgotchi hello block plugin',
      'description' => 'Tests the Drupalgotchi hello block',
      'group' => 'Drupalgotchi',
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->configFactory = $this->getConfigFactoryStub(
      array(
        'violator_block.weather' => array(
          'weather_station' => '',
          'threshold' => 0,
          'message' => 'Cold',
        ),
      )
    );

    // Autoloading is not working for contrib. Load our class to test.
    // See https://drupal.org/node/2025883
    //include_once DRUPAL_ROOT . '/modules/violator_block/lib/Drupal/violator_block/Weather.php';
  }

  /**
   * Tests the build method for the block.
   */
  public function testWeatherRequest() {

    // Sample return string from openweathermap.org for Chicago,USA
    $json_string = <<<END
{
  "coord":{"lon":-87.63,"lat":41.88},
  "sys":{"message":0.1071,"country":"United States of America","sunrise":1392986191,"sunset":1393025494},
  "weather":[{"id":802,"main":"Clouds","description":"scattered clouds","icon":"03d"}],
  "base":"cmc stations",
  "main":{"temp":32.92,"pressure":1007,"humidity":47,"temp_min":30.2,"temp_max":35.6},
  "wind":{"speed":9.3,"deg":250,"gust":14.4},
  "snow":{"3h":0},
  "clouds":{"all":40},
  "dt":1393023120,
  "id":4887398,
  "name":"Chicago",
  "cod":200
}
END;

    // Set a mock class for the state container.
    // See http://phpunit.de/manual/current/en/test-doubles.html
    $state_stub = $this
      ->getMockBuilder('\Drupal\Core\KeyValueStore\StateInterface')
      ->getMock();

    $state_stub->expects($this->any())
      ->method('set')
      ->with($this->equalTo('violator_block.weather.temp'), $this->equalTo(42));

    $weather = new Weather($guzzle, $state_stub, $this->configFactory, '');

    $weather->updateTemperature();

  }

}
