.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: Includes.txt

.. _start:


============
Introduction
============

Add the Plugin to any Page and Define headline and Text to Show

.. figure:: Images/text.png


Restrictions
""""""""""""
Define the needed Usergroup(s) that ruels, when to Show the Text.
If you use "MAY not have group", please default TYPO3 Access -> "Show on any login"

.. figure:: Images/text.png


Set Result
""""""""""

.. figure:: Images/result.png


Result in Frontend will be a bootstrap alert div with alert-warning class.
Only visible in this example if user has usergroup "_KategoryMogler"

if he accepts the Message, he will loose usergroup "_KategoryMogler"



To Dos
""""""

The plan for Version 2 is to a configurable list of Classes that can be configured via typoscript.
this list will be added as select to the plugins flexform.
after a usergroup Change in the Controller, this class should be called.

So f.e. you can add hooks to transport this usergroup change to an external CMS, email Newsletter tool or similar.





