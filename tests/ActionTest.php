<?php

declare(strict_types=1);

namespace Tabuna\FailBack;

use Error;
use Exception;
use PHPUnit\Framework\TestCase;

class ActionTest extends TestCase
{
    public function testDefaultValue(): void
    {
        $result = Action::make(function () {
            throw new Exception();
        }, true)->run();

        $this->assertTrue($result);
    }

    public function testHelper(): void
    {
        $result = failBack(function () {
            throw new Exception();
        })->run();

        $this->assertNull($result);
    }

    public function testInvoke(): void
    {
        $result = failBack(function () {
            throw new Exception();
        })();

        $this->assertNull($result);
    }

    public function testToString(): void
    {
        $result = (string)failBack(function () {
            throw new Exception();
        })->fail(function () {
            return 'Alexandr';
        });

        $this->assertEquals('Alexandr', $result);
    }

    public function testFailBack(): void
    {
        $result = Action::make(function () {
            throw new Exception();
        })->fail(function () {
            throw new Exception();
        })->fail(function () {
            return true;
        })
            ->run();

        $this->assertTrue($result);
    }

    public function testContextFailBack(): void
    {
        $name = 'Tomas';

        $result = Action::make(function () {
            throw new Exception();
        })->fail(function () use (&$name) {
            $name = 'Alexandr';

            return $this->returnAlwaysTrue();
        })
            ->run();

        $this->assertTrue($result);
        $this->assertEquals('Alexandr', $name);
    }

    /**
     * @return bool
     */
    private function returnAlwaysTrue(): bool
    {
        return true;
    }

    public function testLateCreation(): void
    {
        $action = new Action();

        $action->set(function () {
            return false;
        });

        $result = $action->run();

        $this->assertFalse($result);
    }

    public function testRunExeptions(): void
    {
        $action = new Action();

        $action->set(function () {
            try {
                throw new Exception();
            } catch (Exception $exception) {
                return true;
            }
        });

        $result = $action->run();

        $this->assertTrue($result);
    }

    public function testFallExeptions(): void
    {
        $action = new Action();

        $action->fail(function () {
            try {
                throw new Exception();
            } catch (Exception $exception) {
                return true;
            }
        });

        $action->set(function () {
            throw new Exception();
        });

        $result = $action->run();

        $this->assertTrue($result);
    }

    public function testDownAction(): void
    {
        $result = Action::make(function () {
            throw new Exception();
        }, 'default')
            ->fail(function () {
                throw new Exception();
            })
            ->fail(function () {
                throw new Error();
            })
            ->run();

        $this->assertEquals($result, 'default');
    }

    public function testInvokeAction(): void
    {
        $class = new class {
            /**
             * @return bool
             */
            public function __invoke(): bool
            {
                return true;
            }
        };

        $result = Action::make(function () {
            throw new Exception();
        })->fail($class)
            ->run();

        $this->assertTrue($result);
    }
}
