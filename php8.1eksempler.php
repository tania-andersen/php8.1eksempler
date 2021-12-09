<?php

// Eksempler til Version2's artikel om nyhederne i PHP 8.1 https://www.version2.dk/node/1093804

// Enums

enum Suit: string {
  case Hearts = 'H';
  case Diamonds = 'D';
  case Clubs = 'C';
  case Spades = 'S';
}

print Suit::Clubs->value;

echo "\n";

print Suit::from('D')->value;

echo "\n";

var_dump(Suit::tryFrom('X'));

echo "\n";

// Fibers

$fiber = new Fiber(function (): void {
    $value = Fiber::suspend('fiber');
    echo "Value used to resume fiber: ", $value, "\n";
});
 
$value = $fiber->start();
 
echo "Value from fiber suspending: ", $value, "\n";
 
$fiber->resume('test');

// Readonly props

class Test {
    public readonly string $prop;
 
    public function __construct(string $prop) {
        // Legal initialization.
        $this->prop = $prop;
    }
}
 
$test = new Test("foobar");
// Legal read.
var_dump($test->prop);
 

try {
	// Illegal reassignment. It does not matter that the assigned value is the same.
	$test->prop = "foobar";
} catch(Error  $e) {
  echo 'Fejl: '.$e->getMessage();
}

echo "\n";

$func = 'strlen';
echo $func('test'); 

echo "\n";

// Ny syntaks til closures 

class Test2 {
    public function getPrivateMethod() {
        //return [$this, 'privateMethod']; // does not work
        return Closure::fromCallable([$this, 'privateMethod']); // works, but ugly
        return $this->privateMethod(...); // works
    }
 
    private function privateMethod() {
        echo __METHOD__, "\n";
    }
}
 
$test = new Test2;
$privateMethod = $test->getPrivateMethod();
$privateMethod();

// New i constructors som standard-værdi

interface Logger {};

class NullLogger implements Logger {};

class Test3 {	
    public function __construct(
        private Logger $logger = new NullLogger,
    ) {}
}

// Intersection-typer

class A {
    private Traversable&Countable $countableIterator;
 
    public function setIterator(Traversable&Countable $countableIterator): void {
        $this->countableIterator = $countableIterator;
    }
 
    public function getIterator(): Traversable&Countable {
        return $this->countableIterator;
    }
}

// Arrays

$arrayA = ['a' => 1];
$arrayB = ['b' => 2];
 
$result = ['a' => 0, ...$arrayA, ...$arrayB];


// Final konstanter

class Foo
{
final public const XX = "foo";
}

// Oktal-tal

var_dump(0o16 === 14); // true

// Never-type

function redirect(string $uri): never {
    header('Location: ' . $uri);
    exit();
}
 
function redirectToLoginPage(): never {
    redirect('/login');
    echo 'Hello'; // <- dead code detected by static analysis
}

?>
