
plugin.tx_jvadd2group {
    view {
        templateRootPaths.0 = EXT:jv_add2group/Resources/Private/Templates/
        templateRootPaths.1 = {$plugin.tx_jvadd2group.view.templateRootPath}
        partialRootPaths.0 = EXT:jv_add2group/Resources/Private/Partials/
        partialRootPaths.1 = {$plugin.tx_jvadd2group.view.partialRootPath}
        layoutRootPaths.0 = EXT:jv_add2group/Resources/Private/Layouts/
        layoutRootPaths.1 = {$plugin.tx_jvadd2group.view.layoutRootPath}
    }
    features {
        skipDefaultArguments = 1
        # if set to 1, the enable fields are ignored in BE context
        ignoreAllEnableFieldsInBe = 0
        # Should be on by default, but can be disabled if all action in the plugin are uncached
        requireCHashArgumentForActionArguments = 0
    }
    mvc {
        callDefaultActionIfActionCantBeResolved = 1
    }
    # set this to 1 to get infos in Case of problems to get more debug output
    settings.debug = 0

    settings.hookClasses {

        example {

            label = Update Field Example - will do nothing!
            fqcn  = JVelletti\JvAdd2group\Utility\ExampleWrapperUtility
            function = main
            mapping {
                1 {
                    exampleField = trial_first_date
                    setDate = 1
                }
            }
        }
        # add more Hook classes example
        # yourHook {
        #    label = is Shown in Backend

        #    must be set! (if not, the label will not be selectable in Backend)
        #    may not start with "\" and do not add "::class"
        #    fqcn  = Vendor\Package\Classname

        #    see example in class SalesForceWrapperUtility->main()
        #    function = your-function-name

        #    add any kind of mapping via TYP Script.
        #    the configured function in Class muss exist and accept two parameter: this settings as array and current login fe_user
        #
        #    mapping {
        #        1 {
        #            yourField = externalDbFieldnameCreatedDate
        #            feuserField = crdate
        #        }
        #    }
        # }

    }
}

