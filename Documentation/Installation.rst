.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: Includes.txt

.. _start:


============
Installation
============

     composer require jvelletti/jv_add2group

Configuration
"""""""""""""

just add the delivered typoscript template to you own template or include it

add this to your config (constants):
    <INCLUDE_TYPOSCRIPT: source="FILE:EXT:jv_add2group/Configuration/TypoScript/constants.ts">

and this to setup:
    <INCLUDE_TYPOSCRIPT: source="FILE:EXT:jv_add2group/Configuration/TypoScript/setup.ts">

feel free to copy my templates, Layouts or partials to from:typo3conf\ext\jv_add2group\Resources\Private
to your own template extension f.e. : tx_your_template/Resources/jv_add2group/ override them.

You will need this as Constants:

    plugin.tx_jvadd2group_add2group {
        view {
            templateRootPath = EXT:tx_your_template/Resources/jv_add2group/Templates/
            partialRootPath = EXT:tx_your_template/Resources/jv_add2group/Partials/
            layoutRootPath = EXT:tx_your_template/Resources/jv_add2group/Layouts/
        }
    }


Styling
"""""""
But normaly it is sufficiant to copy only the folder Resources/Privat/Templates to
f.e. fileadmin/theme/ (or your own sitepackage private folder)

add a constant in your typo3script  that points to this folder
   plugin.tx_jvadd2email.view.templateRootPath = fileadmin/theme/Templates

or
a setup value:
   templateRootPaths.50 = fileadmin/theme/Templates




