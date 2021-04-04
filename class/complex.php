<?php

class complex {
  private float $_Re = 0.0;
  private float $_Im = 0.0;

  public function __construct($Re, float $Im = 0.0)
  {
    /* Т.к. при создании экземпляра класса можно использовать записи:
    $c = complex(1, 2);
    $c = complex('1 + 2i');
    $c = complex('sqrt(5)(cos(atan(2)) + i sin(arctg(2)))');
    проверим тип данных в параметре $Re,
    если это число или числовая строка, то используем сразу,
    если это не числовая строка, она будет передана методу fromString,
    иначе выход с ошибкой. */
    if (is_numeric($Re)) {
      $this->_Re = $Re;
      $this->_Im = $Im;
    } elseif ('string' == gettype($Re))
      $this->fromString($Re);
    else
      die(gettype($Re) . "? You must be joking. complex::__construct");
  }

  public function real() {
    return $this->_Re;
  }

  public function imag() {
    return $this->_Im;
  }

  public function toString() {
    return strval($this->real()) .
        (0 > $this->imag() ? ' - ' : ' + ') .
        abs($this->imag()) . 'i';
  }
  
  /* Нивелируем разницу, при сложных числах */
  public function toString2($rnd = 5) {
    return strval(round($this->real(), $rnd)) .
        (0 > $this->imag() ? ' - ' : ' + ') .
        round(abs($this->imag()), $rnd) . 'i';
  }

  /* Выводим комплексное число в тригонометрической форме */
  public function toTrigString() {
    if (0.0 == $this->_Re && 0.0 == $this->_Im)
      return '0';

    $r = sqrt($this->_Re ** 2 + $this->_Im ** 2);
    $argument = '';

    if (0 < $this->_Re && 0 <= $this->_Im)
      $argument = (0 == $this->_Im) ?
        '0' :
        atan($this->_Im / $this->_Re);
    elseif (0 < $this->_Re && 0 > $this->_Im)
      $argument = atan($this->_Im / $this->_Re);
    elseif (0 > $this->_Re && 0 <= $this->_Im)
      $argument = (0 == $this->_Im) ?
        M_PI :
        M_PI + atan($this->_Im / $this->_Re);
    elseif (0 > $this->_Re && 0 > $this->_Im)
      $argument = -M_PI + atan($this->_Im / $this->_Re);
    elseif (0 == $this->_Re)
      $argument = (0 < $this->_Im) ? M_PI / 2 : -M_PI / 2;

    return sprintf('%s(cos(%s) + i * sin(%s))', $r, $argument, $argument);
  }

  /* Выводим комплексное число в тригонометрической форме
  не просчитанной до конца, с 'pi' вместо 3.1415926535898
  и не подсчитанными арктангенсами. */
  public function toTrigString2() {
    if (0.0 == $this->_Re && 0.0 == $this->_Im)
      return '0';

    $r = sprintf('sqrt(%F)', $this->_Re ** 2 + $this->_Im ** 2);
    $argument = '';

    if (0 < $this->_Re && 0 <= $this->_Im)
      $argument = (0 == $this->_Im) ?
        '0' :
        sprintf('atan(%F)', $this->_Im / $this->_Re);
    elseif (0 < $this->_Re && 0 > $this->_Im)
      $argument = sprintf('atan(%F)', $this->_Im / $this->_Re);
    elseif (0 > $this->_Re && 0 <= $this->_Im)
      $argument = (0 == $this->_Im) ?
        'pi' :
        sprintf('pi + atan(%F)', $this->_Im / $this->_Re);
    elseif (0 > $this->_Re && 0 > $this->_Im)
      $argument = sprintf('-pi + atan(%F)', $this->_Im / $this->_Re);
    elseif (0 == $this->_Re)
      $argument = (0 < $this->_Im) ? sprintf('pi / 2') : sprintf('-pi / 2');

    return sprintf('%s(cos(%s) + i * sin(%s))', $r, $argument, $argument);
  }

  /*
  Получает комплексное число из строковой переменной.

  В алгебраической форме:
  Работает как в Python, только вместо j здесь i и пробелы не учитываются.

  В тригонометрической форме:
  Получает комплексное число записонное в строке, разбирает и преобразует в
  алгебраическую форму.
  */
  private function fromString( string $sUnknown ) {
    /* Удаляем все пробелы и приводим строку к нижнему регистру */
    $sUnknown = str_replace(' ', '', strtolower($sUnknown));
    /* Проверим в какой форме записано число. Т.к. базовая формула
    тригонометрической формы z = |z|(cos ϕ + i sin ϕ),
    проверим наличие cos в строке. */
    if (false === strpos($sUnknown, 'cos')) {
      /* Алгебраическая форма */
      /* Выделяем из строки действительную и мнимую части числа */
      $pattern = '/^(-?\d*\.?\d*)?([-+]?\d*\.?\d*i)?$/U';
      if (preg_match($pattern, $sUnknown, $matches))
      {
        /* Если действительная часть числа указана используем её */
        if (isset($matches[1]) && is_numeric($matches[1]))
          $this->_Re = $matches[1];
        /* Если мнимая часть числа записана как i дополняем ее до 1.0i
        с сохранением знака или используем указанное число */
        $this->_Im = (in_array($matches[2], ['i', '+i', '-i'])) ?
          1.0 * str_replace('i', '1', $matches[2]) :
          $this->_Im = str_replace('i', '', $matches[2]);
      /* Если строка не соответствует регулярному выражению,
      значит в строке не комплексное число, завершаем работу с ошибкой. */
      } else die('ValueError[1]: complex() arg is a malformed string.');
    } else {
      /* Тригонометрическая форма */
      /* Выделяем из строки модуль и аргумент комплексного числа */
      $pattern = '/^(.*)\(cos\((.*)\)\+i\*?sin\(.*\)\)$/';
      if (preg_match($pattern, $sUnknown, $matches))
      {
        $r = 0;
        $argument = 0;
        /* Проверим не записан ли модуль в виде действительного числа */
        if (is_numeric($matches[1])) {
          $r = $matches[1];
        } else {
          /* Вычисляем модуль числа. Удаляем sqrt() из строки,
          получаем действительное число и вычисляем из него
          значение квадратного корня */
          $pattern = '/^sqrt\((\d+\.?\d*)\)$/';
          preg_match($pattern, $matches[1], $match);
          $r = sqrt($match[1]);
        }

        /* Проверим аргумент, не в виде ли действительного числа он */
        if (isset($matches[2]) && is_numeric($matches[2])) {
          $argument = $matches[2];
        } elseif (isset($matches[2])) {
          /* Вычисляем аргумент числа. Проверив что,
          в строке только цыфры, операторы + - *, и единственная
          функция atan(). Выполняем эту строку через eval()
          для получения аргумента числа. Иначе придется вводить
          жесткий формат записи аргумента числа в
          тригонометрической форме. */
          $matches[2] = str_replace('arctg', 'atan', $matches[2]);
          $pattern = ('/^[-0-9*]*(pi)?\/?\+?(atan)?\(?[-0-9.]*\)?\+?'.
                '[-0-9*]*(pi)?$/');
          if (preg_match($pattern, $matches[2], $match)) {
            $match[0] = str_replace('pi', M_PI, $match[0]);
            eval('$argument = ' . $match[0] . ';');
          } else die('ValueError[2]: complex() arg is a malformed string.');
        } else die('ValueError[3]: complex() arg is a malformed string.');
        /* Вычисляем и сохраняем действительную и мнимую часть
        алгебраической формы комплексного числа */
        $this->_Re = round($r * cos($argument), 6);
        if (-0 == $this->_Re)
          $this->_Re = 0;
        $this->_Im = round($r * sin($argument), 6);
      /* Если строка не соответствует регулярному выражению,
      значит в строке не комплексное число, завершаем работу с ошибкой. */
      } else die('ValueError[4]: complex() arg is a malformed string.');
    }
  }

  /*
  Сумма комплексных чисел
  (a + bi) + (c + di) = (a + c) + (b + d)i
  */
  public static function add(
    complex $C1,
    complex $C2
  ) {
    return new complex(
      $C1->real() + $C2->real(),
      $C1->imag() + $C2->imag()
    );
  }

  /*
  Разница комплексных чисел
  (a + bi) - (c + di) = (a - c) + (b - d)i
  */
  public static function sub(
    complex $C1,
    complex $C2
  ) {
    return new complex(
      $C1->real() - $C2->real(),
      $C1->imag() - $C2->imag()
    );
  }

  /*
  Произведение комплексных чисел
  (a + bi) * (c + di) = (ac - bd) + (bc + ad)i
  */
  public static function mult(
    complex $C1,
    complex $C2
  ) {
    $Re = ($C1->real() * $C2->real()) - ($C1->imag() * $C2->imag());
    $Im = ($C1->imag() * $C2->real()) + ($C1->real() * $C2->imag());
    return new complex($Re, $Im);
  }

  /*
  Деление комплексных чисел
  (a + bi) / (c + di) = (ac + bd) / (c^2 + d^2) + ((bc - ad) / (c^2 + d^2))i
  */
  public static function div(
    complex $C1,
    complex $C2
  ) {
    $a = $C1->real();
    $b = $C1->imag();
    $c = $C2->real();
    $d = $C2->imag();
    $divider = $c ** 2 + $d ** 2;
    if ( $divider == 0.0 )
      die("Division by zero in mathComplex::div");
    $Re = ($a * $c + $b * $d) / $divider;
    $Im = ($b * $c - $a * $d) / $divider;
    return new complex($Re, $Im);
  }
}