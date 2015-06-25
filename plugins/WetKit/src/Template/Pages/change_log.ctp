<?php
use Cake\Core\Configure;

if (!Configure::read('debug')):
    throw new NotFoundException();
endif;

$markdown = $this->helpers()->load('WetKit.Markdown');
echo $markdown->text(
<<<'MD'


# WETKIT Change Log


## 2015-06-25

- Added Splash page
- Minor modification to Language handling
- Redesign of WET UI elements and view blocks
- New app configurations for WET UI displays

## 2015-06-23

- Standardize wetkit configurations
    - Removed the app. configurations and merged them into the wetkit. configurations.
    - Added WET path location configuration and autopath to have it built based on other configs.
    - Added environment configurations. Removed reliance on $current_env and added it to wetkit.current_env.
- Adapted the layout and elements according to new configuration changes
- AppController
    - Removed redundant $this->wetkit and $this->set("wetkit") all data is now in $this->appData and $this->set("appData")
    - Removed $this->set("current_env")
- Fixed documentation accordingly.

## 2015-06-22

- Added Site Menu to Wetkit Overwrites
- Fixed issue with mega menu being hidden and breadcrump config
- Added a configuration error widget for WetKit
- Added getAppData to WetKitComponent
- Modification to AppController
- Updated documentation

## 2015-06-18

- Added WET form validation
    - Bake Controller modifications
    - Bake Templates modifications
- Modified CakePHP validation errors to match WET validation errors


## 2015-06-17

- Fixed checkbox issue in Form template
- Fixed minor UI issues with bake
- Modified whoDidIt display (removed from the alert class because... it's not an alert!)
- Added a change log page
- Added bottom of page WetKit menu to access help and change log and possibly future WetKit features.


## 2015-06-16

-	Nouvelle logique pour le whoDidIt qui ne requirert plus de configuration dans le model et controller.
-	Ajout du « Modification Date » de WET.
-	Ajout des paramêtres d’application (nom, version, release_date, title, description, etc…) peut être défini dans votre App Controller
-	Mise à jour du POT file de traduction
-	Mise à jour de la documentation ([Votre URL]/pages/wetkit_info)
-	Modification au Bake…
    - Controller :
        -Ajout d’un snippet qui modifie la date de modification du thême WET pour les view et edit.
    - Views
        - Clean up code
        - Removed created, modified, created_by and modified_by from view fields (it’s already shown in the whoDidIt part at the end)
        - Fixed issue with Text field not displaying right.



MD
);
