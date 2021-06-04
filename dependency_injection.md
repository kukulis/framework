# Dependency injection from decoupling perspective

The dependency injection (DI) has a lot of features too mutch to discus in one paper. So we will review the DI from a decoupling perspective.
The object-oriented programming has two concepts: "high cohesion" and "low coupling". These are traits of your functions/classes/modules/application which are treated as good. Cohesion means specialization of your class/function to do one thing instead of many. The higher cohesion of your class/function the better they are. High cohesion allows you to reuse your code in many places, to read the code easer and to test it easer. 

Coupling means how classes/function/modules are related to each other. Actually the word "dependency" is very strongly related to a "coupling" term. The less coupling exist in your application the better application is. Low coupling lets to modify your code easier; low coupling lets to move various parts of your application between modules.

We will analyze in this paper how using DI you may atchieve "low coupling" trait of your application.

## DI Purpose
The purposes of dependency injection (DI) are:

1. Separating instanciation of service classes to outside factories.
2. Composing application from various vendors.
3. Decoupling classes from each other, by letting to make modifications which does not impact other classes.
4. Separating environment configuration read from service classes to outside factories.


## DI coupling levels
The term "DI coupling level" is used in this paper only. By the "DI coupling level" we indicate how using DI we separate classes/functions/modules from each other to atchieve the "low coupling" goal; the lesser coupling is atchieved the higher "DI coupling level" is. We introduce several levels of DI and discuse them one by one in the following text.

### level 0 - no DI at all
