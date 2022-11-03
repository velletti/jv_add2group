# jv_add2group
TYPO3 Extension - add usergroup to fe_user after Showing him a text


## What does it do: 

Configure a TYPO3 Text element and set up the needed Usergroup(s) the  
frontend user SHOULD have Or define the opposite Should **not** have. 

Set the Button Text user should Click. 

Set up the NEW Usergroup(s) the User should get additionally or that should be removed.

*Example use case:*
Show a text, that explanes the Rules to UPLOAD of images. 
Or to participate in a user Forum
Or to accept New  **"Terms Of Use"** 

When the current logged in User clicked to the defined *"Accept (s)Rules"* Button,group "UploadAllowed"
(s)he will get the Usergroup "UploadAllowed" 

You can have more than one of such Text boxes  on one Page. 

*Feature since Version 11.1.10:*
you can configure a custom class and the function in this class, that is called at the end.

    settings.hookClasses {
        example { 
           fqcn  = JVelletti\JvAdd2group\Utility\ExampleWrapperUtility
           function = main
        }
    }

will call 

    $hook = $settings['hookClasses']['example']
    ExampleWrapperUtility->main( $hook , $user)

more examples on this will follow (f.e. active newsletter Opt In in FE user entry or similar )

## Internal reminder for the extension maintainer: 
To Update this extension in TER: 
change version Number to "x.y.z" in ext_emconf.php, Documentation\ in Settings.cfg and Index.rst
create Tag "x.y.z"
git push --tags

create new zip file:
cd typo3conf/ext/jv_add2group
git archive -o "${PWD##*/}_x.y.z.zip" HEAD

Upload ZIP File to https://extensions.typo3.org/my-extensions
git push

check:
https://intercept.typo3.com/admin/docs/deployments
https://packagist.org/packages/jvelletti/jv-add2group
https://extensions.typo3.org/extension/jv_add2group/
