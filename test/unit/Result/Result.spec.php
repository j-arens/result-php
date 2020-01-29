<?php

use O\Result\{Result, Ok, Err, ResultException};
use O\Option\{Option, Some, None};

describe('Result', function () {
    describe('creating an instance of Result', function () {
        it('throws if instantiated directly', function () {
            $fn = function () {
                new Result(1, 0);
            };
            expect($fn)->toThrow(ResultException::illegalInstantiation());
        });
    });

    describe('->isOk', function () {
        it('should return true for instances of Ok', function () {
            expect((new Ok(''))->isOk())->toBe(true);
        });

        it('should return false for instances of Err', function () {
            expect((new Err(''))->isOk())->toBe(false);
        });
    });

    describe('->isErr', function () {
        it('should return true for instances of Err', function () {
            expect((new Err(''))->isErr())->toBe(true);
        });

        it('should return false for instances of Ok', function () {
            expect((new Ok(''))->isErr())->toBe(false);
        });
    });

    describe('->ok', function () {
        it('returns a Some containing the success value if called on an instance of Ok', function () {
            $opt = (new Ok('foo'))->ok();
            expect($opt)->toBeAnInstanceOf(Some::class);
        });

        it('returns a None if called on an instance of Err', function () {
            $opt = (new Err(null))->ok();
            expect($opt)->toBeAnInstanceOf(None::class);
        });
    });

    describe('->err', function () {
        it('returns a Some containg the error value if called on an instance of Err', function () {
            $opt = (new Err('foo'))->err();
            expect($opt)->toBeAnInstanceOf(Some::class);
        });

        it('returns a None if called on an instance of Ok', function () {
            $opt = (new Ok('foo'))->err();
            expect($opt)->toBeAnInstanceOf(None::class);
        });
    });

    describe('->and', function () {
        it('returns the given result if called on an instance of Ok', function () {
            $resa = new Ok('foo');
            $resb = new Ok('bar');
            expect($resa->and($resb))->toBe($resb);
        });

        it('returns a new instance of Err if called on an instance of Err', function () {
            $resa = new Err('foo');
            $resb = new Ok('bar');
            $resc = $resa->and($resb);
            expect($resc->isErr())->toBe(true);
        });
    });

    describe('->andThen', function () {
        it('returns the result returned from the given function if called on an instance of Ok', function () {
            $resa = new Ok('foo');
            $resb = $resa->andThen(function (string $word) {
                return new Ok($word . 'bar');
            });
            expect($resb->isOk())->toBe(true);
            expect($resb->unwrap())->toBe('foobar');
        });

        it('returns a new instance of Err if called on an instance of Err', function () {
            $resa = new Err('');
            $resb = $resa->andThen(function ($x) {
                return new Ok($x);
            });
            expect($resb->isErr())->toBe(true);
            expect($resb)->not->toBe($resa);
        });
    });

    describe('->expect', function () {
        it('returns the success value if called on an instance of Ok', function () {
            $res = new Ok('foo');
            expect($res->expect('oops'))->toBe('foo');
        });

        it('throws a ResultError with the given message if called on an instance of Err', function () {
            $fn = function () {
                $res = new Err('');
                $res->expect('oops');
            };
            expect($fn)->toThrow(new ResultException('oops'));
        });
        
    });

    describe('->expectErr', function () {
        it('returns the error value if called on an instance of Err', function () {
            $res = new Err('foo');
            expect($res->expectErr('oops'))->toBe('foo');
        });

        it('throws a ResultError with the given message if called on an instance of Ok', function () {
            $fn = function () {
                $res = new Ok('');
                $res->expectErr('oops');
            };
            expect($fn)->toThrow(new ResultException('oops'));
        });
    });

    describe('->map', function () {
        it('returns a new instance of Ok if called on an instance of Ok', function () {
            $resa = new Ok('foo');
            $resb = $resa->map(function ($x) {
                return $x;
            });
            expect($resb->isOk())->toBe(true);
            expect($resa)->not->toBe($resb);
        });

        it('returns an instance of Ok containg the product of the given function', function () {
            $res = new Ok('foo');
            $mapped = $res->map(function ($x) {
                return $x . 'bar';
            });
            expect($mapped->unwrap())->toBe('foobar');
        });

        it('returns a new instance of Err with the error value if called on an instance of Err', function () {
            $resa = new Err('');
            $resb = $resa->map(function ($x) {
                return $x;
            });
            expect($resb->isErr())->toBe(true);
            expect($resa)->not->toBe($resb);
        });
    });

    describe('->mapErr', function () {
        it('returns a new instance of Err if called on an instance of Err', function () {
            $resa = new Err('foo');
            $resb = $resa->mapErr(function ($x) {
                return $x;
            });
            expect($resb->isErr())->toBe(true);
            expect($resa)->not->toBe($resb);
        });

        it('returns an instance of Err containg the product of the given function', function () {
            $res = new Err('foo');
            $mapped = $res->mapErr(function ($x) {
                return $x . 'bar';
            });
            expect($mapped->unwrapErr())->toBe('foobar');
        });

        it('returns a new instance of Ok with the success value if called on an instance of Ok', function () {
            $resa = new Ok('');
            $resb = $resa->mapErr(function ($x) {
                return $x;
            });
            expect($resb->isOk())->toBe(true);
            expect($resa)->not->toBe($resb);
        });
    });

    describe('->or', function () {
        it('returns a new instance of Ok with the success value if called on an instance of Ok', function () {
            $resa = new Ok('foo');
            $resb = new Ok('bar');
            $resc = $resa->or($resb);
            expect($resc->isOk())->toBe(true);
            expect($resc->unwrap())->toBe('foo');
        });

        it('returns the given result of called on an instance of Err', function () {
            $resa = new Err('foo');
            $resb = new Ok('bar');
            $resc = $resa->or($resb);
            expect($resb)->toBe($resc);
        });
    });

    describe('->orElse', function () {
        it('returns a new instance of Ok with the success value if called on an instance of Ok', function () {
            $resa = new Ok('foo');
            $resb = new Ok('bar');
            $resc = $resa->orElse(function () use ($resb) {
                return $resb;
            });
            expect($resc->isOK())->toBe(true);
            expect($resc->unwrap())->toBe('foo');
        });

        it('returns the result returned by the given function if called on an instance of none', function () {
            $resa = new Err('foo');
            $resb = new Ok('bar');
            $resc = $resa->orElse(function () use ($resb) {
                return $resb;
            });
            expect($resb)->toBe($resc);
        });
    });

    describe('->unwrap', function () {
        it('returns the success value contained within an Ok', function () {
            $res = new Ok('foo');
            expect($res->unwrap())->toBe('foo');
        });

        it('throws if called on an instance of Err', function () {
            $fn = function () {
                $res = new Err('foo');
                $res->unwrap();
            };
            expect($fn)->toThrow(ResultException::illegalCall('unwrap', 'Err'));
        });
    });

    describe('->unwrapErr', function () {
        it('returns the error value contained within an Err', function () {
            $res = new Err('foo');
            expect($res->unwrapErr())->toBe('foo');
        });
        
        it('throws if called on an instance of Ok', function () {
            $fn = function () {
                $res = new Ok('foo');
                $res->unwrapErr();
            };
            expect($fn)->toThrow(ResultException::illegalCall('unwrapErr', 'Ok'));
        });
    });

    describe('->unwrapOr', function () {
        it('returns the success value contained within if called on an instance of Ok', function () {
            $res = new Ok('foo');
            expect($res->unwrapOr(''))->toBe('foo');
        });

        it('returns the given value if called on an instance of Err', function () {
            $res = new Err('');
            expect($res->unwrapOr('foo'))->toBe('foo');
        });
    });

    describe('->unwrapOrElse', function () {
        it('returns the success value contained within if called on an instance of Ok', function () {
            $res = new Ok('foo');
            $fn = function () {
                return '';
            };
            expect($res->unwrapOrElse($fn))->toBe('foo');
        });
        
        it('returns the product returned from the given function if called on an instance of Err', function () {
            $res = new Err('');
            $fn = function () {
                return 'foo';
            };
            expect($res->unwrapOrElse($fn))->toBe('foo');
        });
    });
});
