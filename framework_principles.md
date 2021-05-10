# Framework principles

## Introduction

What is a framework after all? 

In general, a framework is a set of rules which should be kept in the application developing process plus base code libraries and coding instrumental scripts.

Why framework rules are needed at all? 
* framework lets to put multiple IT developers in one team,
* framework helps to understand what an application is doing at all,
* framework increases a productivity of a separate developer, by providing tools and reusable classes/functions to him.

What is a rule in general? This is nothing else but limitations to be kept during developing process.

There is proverb saying that: **perfect is an enemy of good** . This means that we should not expect to create such a framework, which solves all our future problems. Every framework will have its **shortcommings**. So if a framework doesn't help then at least it should not to obstruct a programmers work. The **flexible** term evolves, which means that you should be able to do the same functionality in several ways, and when one way is blocked, you should be able to make a functionality in an another way. 

Lets describe "good" framework traits/statements.

1. Easy to learn;
1. Easy to read code;
1. Easy to add business logic features, (good developing performance);
1. Easy to modify code, easy to scale;
1. Easy to reuse code;
1. Final application easy to maintain;
1. Final application is stable and reliable;
1. Final application is efficient in speed, memory consumption, disk consumption;
1. Final application satisfies client needs.



## Good in more technical terms

Lets break down the traits/statements and see how they are achieved using IT good practices.

Lets analyze these rules first:

* Easy to learn;
* Easy to read code;
* Easy to add business logic features, (good developing performance);

The code must be explicit about what it is doing. Of course framework doesn't force a bad programmer to make a good code, but still if it is easy to navigate a code and easily understand what it is doing ( even if we do not know full ideas of the framework ), then the framework is easy to learn and it is also good in general sense.

So with these statements we may evaluate the framework if it is good or not in an **euristic way** or by trying it in **practice**. We   propose a **functional programming** approach and its ideas, which helps a lot in the current topic.

There are special languages which work strictly on **functional programming** principles; these languages are not very popular, but the principles they are using, may be used in other languages, **greatly** increasing quality of your code.

Functional programming introduces [pure function](https://en.wikipedia.org/wiki/Pure_function) term which has such features:

* immutable argument data;
* data processing encapsulation;
* data processing determinism;

**Immutable argument data** means, that when you pass arguments to a function, they should not be changed after function ends its work. 
For example when you want to modify an array/object in a function, you must create a new array/object and copy each element from the source array and fill modified data.
This **increases readability** greatly, because you must not search in the code where your data is changed, as data you need appears once in your code. This feature increases ability to use a simultaneous processing too, as the main problem for a simultaneous processing is when two different threads are trying to modify the same data. **immutable arguments** principle completely solves this problem.

**data processing encapsulation** means that all data you need for calculation you must pass through function arguments. **No** global variables, **no** class parameters, **no** outside data should be received or modified during function execution.
This also greatly increases readability as you feel safe when you call a function, because you know that it will not make any unexpected actions. When you really need to get data from outside (webservice or database) or write to outside (webservice or database), then you should write a separate function **specialized** for that task. And a business logic should be separated to a different function which fully contracts **data processing encapsulation** principle.

**data processing determinism** means, that when you put same arguments to the function twice (or as many times you want), you should **always** receive the same result. 
Actually this principle evolves from the **data processing encapsulation** or as we may say both of them comes in one couple.
The main advantage of this principle is that you may now cover your code with **tests**, because when result is determined, your test result directly depends only on function arguments and if the **tested function** is modified or not, but will not depend if "something" is changed in your program/system.



Lets take next group of traits.

* Easy to modify code; 
* Easy to scale;
* Easy to reuse code;

For these requirements we propose two objective programming principles:

* high cohesion;
* low coupling.

Cohesion is a class/function specialization. **High cohesion** means, that classes functions must be as much specialized as possible. This lets them to reuse elsewhere. Because if elsewhere you need to do one thing, and the class/function is doing two things, where second thing is not what you want, then you will not be able to reuse it. This correlates well with "functional programming" principle where you separate calculation code and a data read/write to outside resources code, to a separate functions or classes, to meet **data processing determinism** requirement, but in the same way you increase specialization/cohesion of your function/class.
When you have a high cohesion functions, you also modify your code easier because when you want to modify one thing, you rewrite one function/class, but if the function/class does two things, then after rewriting the code which does first thing you must also copy code which makes this second thing; and this is usually bad sign.

Coupling is a term which describes how strong classes/function (more classes in this case) are related to each other. **Low coupling** means that classes have weak relation or none at all. This helps you to modify a code without being afraid that it will affect system functionality where you were not planned to modify.
This is a usual problem of all big systems, because when systems grow larger, it comes harder and harder to make small changes, because each changes affects the whole system's work. This is an example of **high coupling** (BAD), instead of **low coupling** (GOOD) in your system.

For **low coupling** there are two approach that helps to achieve lesser dependency between code parts:

* a layering architecture (horizontal layering);
* a modular architecture (vertical layering);
* dependency injection.

**Layering architecture** is a way to organize classes/functions of your application in layers, assigning hierarchy level to these layers. This layering lets to control relations between  classes/function of your application, in this way decreasing coupling. 
Lets say we have two classes/functions A and B; depending on a hierarchy level of each class/funciton, it is granted or forbidden to call other class/function; that is: the higher level layer classes are allowed to call only functions/classes from own layer or **one level** lower layers. ( Note that there is a difference between "same layer" and "same level layer" ).
Using layering architecture, instead of "net-like" call hierarchy we receive a "tree-like" call structure, so coupling is decreased.

Inside a layer we also may want to implement a micro layering by splitting our classes/functions to:
* data classes,
* helpers with pure functions,
* services.


In a **modular architecture** we make layers "vertically". That is we separate uses/calls to classes/function that are outside module, into a separate package inside the module. The same should work in an opposite way: a module should provide a package of contracted classes/functions that may be called from outside classes/functions.
In this way we make clear where module works inside itself and where it communicates to outside classes/function. So the coupling should be lowered too.

**Dependency injection** is a technique which lets you to combine your application from many modules, implementing some predefined interfaces and contracts. When modules/classes/functions are collected in to a single application, using **Dependency Injection**, they are "coupled" between each other using these "interfaces", instead of using exact classes. For example I am writing a class A which uses some exact class B from a module M2. If we are not using dependency injection we make a strong relation between class A and class B which signals about a **bad** high coupling situation. But if instead of using class B , the class A is using a predefined interface "I" which may be implemented not only in module "M2" but also in module "Mx", then no direct relation between class A and B remains, but only between A and "I".  And the application **Dependency Injection** engine may use any "Mx" implementation of an interface "I", including "M2". In this way we achieve a low coupling result in our application. The **Dependency injection** is usually applied to "services" classes 


Later we will give examples where the **low coupling** and **high cohesion** are used in good and in a bad manner, and we will propose how to solve them.

Lets take the next group of "good traits":

* Final application is easy to maintain;
* Final application is stable and reliable;
* Final application is efficient in speed, memory consumption, disk consumption;
* Final application satisfies client needs.

We will analyze these traits by a list of advice (some of them) from https://12factor.net. Also will include other sources to relate on.
Actually this part is too wide to cover everything in a small amount of text; we will try to mention most important things.

**Final application is easy to maintain**
This may be decomposed to following parts:

* Comfortable way to deploy;
* Comfortable way cope incidents;
* Comfortable way to make small changes of functionality.

To be able to deploy application with a comfort, there must be simple script to do that including **code changes**, **data migrations** and **configuration** changes. This all comes from instrumental part of the framework, which we will no go deep. Just when we rate a particular framework, we will have in mind if it has all these tools.
From the 12factor.net we may mark out two important topics which helps a lot in this step: well structured **Codebase** and **dependencies**, separated **configurations**.

**Comfortable way to cope incidents.** 
On this topic we must have a way to track out what happened. The most popular way to do this is using some **logging** system. Also to cope these incidents we must have a functionality to modify data on a live server, assign user rights and so on. Also backuping services should be used on a really big incidents, but it is out of the framework care. What is in the framework care, is how the **logging** and **rights** assigning features are implemented in the application.

**Comfortable way to make small changes of functionality** 
If the code is structured well enough to meet the previously mentioned "traits", the changes will be easy to make even in the live environment.


**Final application is stable and reliable**
Answer is: **Tests.**
And tests depends on **low coupling** and **high cohesion** and **functional programming principles** from the previously mentioned "traits".
Also if you reuse your classes/functions a lot, you will be able to write a more reliable application, because when you use a function/class which you know works correctly, you will be saved from writing a new class/function and make error there :)


**Final application is efficient in speed, memory consumption, disk consumption** 
This is a complex topic. No framework will secure you from making an inefficient application. But if you take care of **low coupling** and **high cohesion** principles, you may be will load only the data which is needed for a **particular task**. So the memory and processor power will be saved :).

**Final application satisfies client needs.**
First of all question arises: How many code lines, classes and function you need to write to implement a particular feature. In this we may compare different frameworks.
Also a framework flexibility comes back to mind: if the framework rules doesn't obstruct you to make a functionality which fall in to framework rules set or not.

## Conclusions
1. Described 9 hints how to evaluate a particular framework.
1. Proposed **functional programing** principles organizing code and increasing its readability.
1. Introduced **high cohesion** and **low coupling** principles of an objective programming.
1. Introduced **layering** and **dependency injection** principles for achieving a "low coupling" feature of your application code.
1. Introduced **testing** technique which helps to enhance an application stability and gave hints how it is related to a **functional programming** techniques of an application.
1. Introduced **logging** technique which should help you to maintain your application.

## Following reading
Next we will try to evaluate well known frameworks in the market using this paper hints.



