# Dependency injection from a low coupling perspective

The dependency injection (DI) has a lot of features that are too mutch to discus in one paper. So we decided to review the DI from one perspective: how using DI we atchieve low coupling goal in our application.

The object-oriented programming has two concepts: "high cohesion" and "low coupling". These are the traits of your functions/classes/modules/application which are treated as good. Cohesion means specialization of your class/function to do one thing instead of many. The higher cohesion of your class/function the better they are. High cohesion allows you to reuse your code in many places, to read the code easer and to test it easer. 

Coupling means how classes/function/modules are related to each other. The less coupling exist in your application the better application is. Low coupling lets to modify your code easier; low coupling lets to move various parts of your application between modules.

We will analyze in this paper how using DI you may atchieve "low coupling" trait of your application.  Actually the word "dependency" is very strongly related to a "coupling" term, as words "coupled" and "dependent" means how something is related to something.

## DI Purpose
The purposes of dependency injection (DI) are:

1. **Separating** instanciation of service classes to outside factories.
2. **Composing** application from various vendors modules/classes/functions.
3. **Decoupling** classes from each other, by letting to make modifications which does not impact other classes.
4. Separating loading of **environment** configuration parameters from your code to DI system.


## DI coupling levels
The term "DI coupling level" is used in this paper only. By the "DI coupling level" we indicate how using DI we separate classes/functions/modules from each other to atchieve the "low coupling" goal; the lesser coupling is atchieved the higher "DI coupling level" is. We will introduce several levels of DI and discuse them one by one in the following text.

We will provide all examples in the PHP programming language.

### level 0 - no DI at all

Lets say we have three classes A, B  and C, which has function x, y, z and they call each others functions.

    class A {
      private $b;
      public function __construct() {
         $this->b = new B();
      }
      public funciton x() {
        echo "A.x\n";
        $this->b->y();
      }
    }

    class B {
      private $c;
      public function __construct() {
         $this->c = new C();
      }
      public function y() {
         echo "B.y\n";
         $this->c->z();
      }
    }

    class C {
       private $param;
       public function z() {
          $this->param = Config::getParam('param');
          echo "C.z, {$this->param} \n";
       }
    }

    class Config {
        const PARAMETERS = [
            'param' => 'Param value',
            'param2' => 'Param2 value',
        ]
        public static function getParam($name) {
            return self::PARAMETERS[$name];
        }
    }
    

As you see here the classes A, B and C are strongly coupled to each other. The instanciation of classes with a "new" operator appears inside A and B classes. If you remove B or C class from your code, it will stop compiling. Also the configuration parameters ( that are stored in the Config class ) are loaded in the runtime in the class C. The class A **must know** about class B, class B **must know** about class C and class C **must know** about Config class.

### level 1 moving instantiation to outside, injecting configuration parameters.

Lets take the same code, but in this time we remove the "new" operators from the code. Also we put configuration parameter $param in to the class C constructor.

    class A {
      private $b;
      public function __construct(B $b) {
         $this->b = $b;
      }
      public funciton x() {
        echo "A.x\n";
        $this->b->y();
      }
    }

    class B {
      private $c;
      public function __construct(C $c) {
         $this->c = $c;
      }
      public function y() {
         echo "B.y\n";
         $this->c->z();
      }
    }

    class C {
       private $param;
       public function __construct($param) {
            $this->param = $param;
       }
       public function z() {
          echo "C.z {$this->param} \n";
       }
    }
    
    class Config {
        const PARAMETERS = [
            'param' => 'Param value',
            'param2' => 'Param2 value',
        ]
        public static function getParam($name) {
            return self::PARAMETERS[$name];
        }
    }


Now the question arizes, where the instances of our classes will be created?
The **DI assembly engine** commes in to the work now. Instead of using a particular DI engine ( symfony, laravel or other ) , as an example we will provide a factory class here.

    class Factory {
       public static function getAInstance() {
            return new A(new B( new C(Config::getParam('param'))));
       }
    }

This is a primitive replacement of the DI assembly engine, but actually it does the same job - makes an instances of our classes and passes config parameters where they are needed.

Here we completely detach Config class from the class C. Instead of getting the parameter $param value from the Config class, we may use **any** other source of the parameter - for example loading it from some file or from the database, or just hardcode it like this.

    class Factory {
       public static function getAInstance() {
            return new A(new B( new C('other param value')));
       }
    }
    
Also with this approach we may controll how the instances of A, B and C are created. We may make them as **singletons** like this:

    class Factory {
        private static $a=null, $b=null, $c=null;
        
        public static function getC() {
            if (self::$c == null) {
                self::$c = new C(Config::getParam('param'));
            }
            return self::$c;
        }
        
        public static function getB() {
            if ( self::$b == null) {
                self::$b = new B(self::getC());
            }
            return self::$b;
        }
        
        public static function getC() {
            if (self::$a == null) {
                self::$a = new A(self::getB());
            }
            return self::$a;
        }
    }


Still this is only **level 1** of DI because class A **must know** about class B and the class B **must know** about class C.  

Level 1 DI already gives you a **big benefit** to you application. You may cover code with **Tests** as you may mock dependent classes, by passing stubs through constructor parameters. This was **imposible** in the DI level 0.

### level 2 using interfaces.

let each of the classes A, B and C now implement interfaces IA,IB and IC

    interface IA {
        public function x();
    }
    
    interface IB {
        public function y();
    }
    
    interface IC {
        public function z();
    }
    
    class A implements IA {
      private $b;
      public function __construct(IB $b) {
         $this->b = $b;
      }
      public funciton x() {
        echo "A.x\n";
        $this->b->y();
      }
    }

    class B implements IB {
      private $c;
      public function __construct(IC $c) {
         $this->c = $c;
      }
      public function y() {
         echo "B.y\n";
         $this->c->z();
      }
    }

    class C implements IC {
       private $param;
       public function __construct($param) {
            $this->param = $param;
       }
       public function z() {
          echo "C.z {$this->param} \n";
       }
    }
    
    class Config {
        const PARAMETERS = [
            'param' => 'Param value',
            'param2' => 'Param2 value',
        ]
        public static function getParam($name) {
            return self::PARAMETERS[$name];
        }
    }


The factory may remain the same. 
So what is the difference?

The difference here is that class A doesn't know about class B anymore and the class B doesn't know about class C. This may look a small thing, but actually this is **a huge** difference. Now if you remove any of the classes A, B or C from the code, the code still will compile. This means that we **decoupled** A from B and B from C.

Now in the factory we may use other implementations for A, B or C classes.


    class A2 implements IA {
        ....
    }
    class A3 implements IA {
        ....
    }
    class B2 implements IB {
        ....
    }
    class B3 implements IB {
        ....
    }
    class C2 implements IC {
        ....
    }
    class C3 implements IC {
        ....
    }
    
    class Factory () {
        public static function getA() {
            return new A(new B2( new C3(Config::getParam('param'))));
        }
    }

With this approach you may compose your application in the DI assembly code, by using various implementations from various modules.

### level 3 using multiple instances of the same classes

Lets say we need to have multiple databases in our system, or we need to work with different resources but still use the same code. So we may use multiple instances or our service classes, depending on our needs.

Lets say we have a class D which have multiple workers - implementations of interface IA. Each of the worker does the "same" thing from the D perspective, but actully it does different, because each worker will be a different instance of the same interface IA.

    class D {
        private $workers=[];
        
        public addWorker(IA $worker) {
            $this->workers[] = $worker;
        }
        
        public function work() {
            foreach ( $this->workers as $worker ) {
                $worker->x();
            }
        }
    }


    class Factory () {
        public static function getBusinesLogicFirstA() {
            return new A3(new B2( new C3(Config::getParam('param'))));
        }
        public static function getBusinesLogicSecondA() {
            return new A(new B3( new C2(Config::getParam('param2'))));
        }

        public static function getBusinesLogicThirdA() {
            return new A(new B( new C3(Config::getParam('param2'))));
        }
        
        public static function getD() {
            $d = new D();
            $d->addWorker(self::getBusinesLogicFirstA());
            $d->addWorker(self::getBusinesLogicSecondA());
            $d->addWorker(self::getBusinesLogicThirdA());
            
            return $d;
        }
    }

When a need arises, we may modify the Factory to assembly other implementations of IA, IB or IC interfaces.
With this approach we atchieve a goal of combining our system behavior in DI assembly part, without modifying a code

If you pass the exact classes in you constructor, you automaticaly make them dependend to each other, so the coupling instead of "low" becomes "high" again.

So for **level 3 DI** you need to have a possibility to have **multiple instances** of the same interface in you assembly engine. This may be atchieved by giving some naming to your services instances like "BusinesLogicFirstA",  "BusinesLogicSecondA", "getBusinesLogicThirdA" or you should have a very flexible assembly factory like this:

    class Factory () {
        public static function getD() {
            $d = new D();
            $d->addWorker(new A3(new B2( new C3(Config::getParam('param')))));
            $d->addWorker(new A(new B3( new C2(Config::getParam('param2')))));
            $d->addWorker(new A(new B( new C3(Config::getParam('param2')))));
            return $d;
        }
    }
    
    
BTW most flexible DI factory is a pure PHP code, but it migh be too verbose and too work consuming. So, depending on your DI framework, you should be able to atchieve **level 3 DI** in some way.

## Good and bad practices

Here we should provide good and bad examples of the DI application in our system, but we move the examples part to another paper.


## Conclusions

* We provided 3 levels of the DI application;
* We introduced a "DI assembly engine" term;
* The decoupling of classes are achieved by moving "dependency" code from our business logic code to DI assembly code.

