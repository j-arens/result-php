# Result

This is a partial port of the [Result](https://doc.rust-lang.org/std/result/) type from [Rust](https://www.rust-lang.org/). Most of the functionality is here but I skipped porting some of the more Rust-specific methods that don't really make sense in a PHP context.

## Usage

Basic usage is the same as in Rust.

```php
use O\Result\{ResultInterface, Ok, Err};

// basic setting and getting of values
$greeting = new Ok('hey there');
$name = new Err('');

echo $greeting->unwrap(); // echos 'hey there'
echo $name->unwrap(); // throws a ResultException
echo $name->unwrapOr('unknown'); // echos 'unknown'

// function that returns a Result<number, string>
function divide(int $x, int $y): ResultInterface
{
  if ($y === 0) {
    return new Err('cannot divide by zero');
  }
  return new Ok(x / y);
}

divide(1, 0); // Err('cannot divide by zero')
divide(1, 1); // Ok(1)
```

## Linting

```
$ composer lint
```

## Analysing

```
$ composer analyse
```

## Testing

```
$ composer test:unit
```
