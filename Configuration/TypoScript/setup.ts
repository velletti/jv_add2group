
plugin.tx_jvadd2group_add2group {
    view {
        templateRootPaths.0 = EXT:jv_add2group/Resources/Private/Templates/
        templateRootPaths.1 = {$plugin.tx_jvadd2group_add2group.view.templateRootPath}
        partialRootPaths.0 = EXT:jv_add2group/Resources/Private/Partials/
        partialRootPaths.1 = {$plugin.tx_jvadd2group_add2group.view.partialRootPath}
        layoutRootPaths.0 = EXT:jv_add2group/Resources/Private/Layouts/
        layoutRootPaths.1 = {$plugin.tx_jvadd2group_add2group.view.layoutRootPath}
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
}

