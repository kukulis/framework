# What is wrong with Laravel?

We will analyze the laravel framework in this paper using criterias, described in other paper in this repository, called "Framework principles".

Lets evaluate first by each of described traits: 


1. Easy to learn;
1. Easy to read code;
1. Easy to add business logic features, (good developing performance);
1. Easy to modify code, easy to scale;
1. Easy to reuse code;
1. Final application easy to maintain;
1. Final application is stable reliable;
1. Final application is efficient in speed, memory consumption, disk consumption;
1. Final application satisfies client needs.


The problem is on not keeping cohesion and coupling principles.  
That is the models in laravel are 
* data structures,
* database access object ( Dao) ,
* view representation data object.

Laravel Model is a **very** universal class, and this is definately **low cohesion** sign, which is bad.

Reading what fields Model has, is impossible without looking to database so for "Reading code" we give 1 of 10.

For "Easy to add business logic features" would get grade 10 of 10 if your functionality is to provide your database through REST webservice, then you get 10 of 10, but if you want to  make anything else, you encounter a blocker; this is 1 of 10. So the laravel is really dedicated to do one type of applications, but nothing else.

For the same reason you can't reuse your code too.

There is possibility in Laravel to get Model class in your controller function as a parameter. How is it implemented? There is a middleware (some "middler" level "layer" in the Laravel ), which loads the object by its id, before calling your constructor class. What is that? This is how you mix your database layer with your http layer, which couples both of them in one very strong relation. 

But you may not to use this "feature". So the question remains if you do not use bad "features" of the Laravel, you may be can produce good subframework out of the Laravel framework..

Most of other traits are ok ( may be even 10 of 10 ) , but it depends if you are not going out of scope what Laravel can do for you.

Not sure if it is coverable with tests, so the paper will be modified in future.



## Conclusions

1. Laravel doesn't keep high cohesion and low coupling principles for Model and Controller classes.
1. Good subramework posibility remains for Laravel, but need to investigate it more.
1. Correct testing possibility remains.





