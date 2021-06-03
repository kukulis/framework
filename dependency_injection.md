# About dependency injection

## Purpose
The purposes of dependency injection (DI) are:

1. Separating instanciation of service classes to outside factories.
2. Composing application from various vendors.
3. Decoupling classes from each other, by letting to make modifications which does not impact other classes.
4. Separating environment configuration read from service classes to outside factories.

## Implementation

There is two main principles in the DI implementation.

1. DI factories and configuration;
2. The way you write services to meet the DI contracts to be used in the DI framework factories.


Actually if you are keeping the principle 2 ( services meets DI contrats ), you can skip using any DI framework, and implement your own DI factories.


The DI contracts of your services are simple:

1. You can't ever instantiate other services in your services code. All other services you receive must be given through constructor parameters or your service class setters. 
2. You can't ever read environment configuration parameters from your system, ant also must receive through constructor or setters.

All these are DI 

Beside constructor or setters there are a Reflection way o
