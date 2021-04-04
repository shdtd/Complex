<?php
require("class/complex.php");
use PHPUnit\Framework\TestCase;

class complexTest extends TestCase {
  protected $C1 = null;
  protected $C2 = null;
  protected $test_array = array();

  protected function zero() :complex {
    if (1 == rand(0, 1))
      return new complex($this->f_rand());
    else return new complex(0, $this->f_rand());
  }

  protected function f_rand(int $min=-10, int $max=10, int $mul=10000) {
    if ($min > $max) die("I can't go on, minimum value does exceed the ".
    "maximum in f_rand method.");
    return mt_rand($min * $mul, $max * $mul) / $mul;
  }

  protected function setUp(): void {
    $this->C1 = new complex(7, 5);
    $this->C2 = new complex(1, -2);
    array_push($this->test_array, new complex(0));

    for ($i = 0; $i < 20; $i++) {
      array_push($this->test_array, $this->zero());
    }

    for ($i = 0; $i < 1000; $i++) {
      array_push( $this->test_array,
        new complex($this->f_rand(), $this->f_rand()) );
    }
  }

  public function testInOut() {
    foreach($this->test_array as $test_complex) {
      $c = new complex($test_complex->toString());
      $cc = new complex($test_complex->toTrigString());
      $ccc = new complex($test_complex->toTrigString2());
      $this->assertEquals($test_complex->toString(), $c->toString());
      $this->assertEquals($test_complex->toString(), $cc->toString());
      $this->assertEquals($test_complex->toString2(4), $ccc->toString2(4));
    }
  }

  public function testToString() {
    $this->assertEquals("7 + 5i", $this->C1->toString());
  }

  public function testReal() {
    $this->assertEquals("7", $this->C1->real());
  }

  public function testImag() {
    $this->assertEquals("5", $this->C1->imag());
  }

  public function testAdd() {
    $this->assertEquals(
      "8 + 3i",
      complex::Add($this->C1, $this->C2)->toString()
    );
  }

  public function testSub() {
    $this->assertEquals(
      "6 + 7i",
      complex::Sub($this->C1, $this->C2)->toString()
    );
  }

  public function testMult() {
    $this->assertEquals(
      "17 - 9i",
      complex::Mult($this->C1, $this->C2)->toString()
    );
  }

  public function testDiv() {
    $this->assertEquals(
      "-0.6 + 3.8i",
      complex::Div($this->C1, $this->C2)->toString()
    );
  }
}