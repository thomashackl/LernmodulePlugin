<?php

require_once __DIR__."/../vendor/h5p-php-library/h5p.classes.php";

class H5peditorController extends PluginController
{

    public function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);
    }

    public function edit_action($module_id = null)
    {
        if (!Context::get()->id || !$GLOBALS['perm']->have_studip_perm("tutor", Context::get()->id)) {
            throw new AccessDeniedException();
        }
        Navigation::activateItem("/course/lernmodule/overview");
        $this->mod = new H5pLernmodul($module_id);

        $this->styles = array(
            $this->plugin->getPluginURL().'/assets/h5p/h5p.css',
            $this->plugin->getPluginURL().'/assets/h5p/h5p-confirmation-dialog.css',
            $this->plugin->getPluginURL().'/assets/h5p/h5p-core-button.css'
        );
        $this->scripts = array(
            $this->plugin->getPluginURL().'/assets/h5p/jquery.js',
            $this->plugin->getPluginURL().'/assets/h5p/h5p.js',
            $this->plugin->getPluginURL().'/assets/h5p/h5p-event-dispatcher.js',
            $this->plugin->getPluginURL().'/assets/h5p/h5p-x-api-event.js',
            $this->plugin->getPluginURL().'/assets/h5p/h5p-x-api.js',
            $this->plugin->getPluginURL().'/assets/h5p/h5p-content-type.js',
            $this->plugin->getPluginURL().'/assets/h5p/h5p-confirmation-dialog.js',
            $this->plugin->getPluginURL().'/assets/h5p/h5p-action-bar.js',
            $this->plugin->getPluginURL().'/assets/h5p/h5peditor-editor.js',
            $this->plugin->getPluginURL().'/assets/h5p/h5p-editor.js',
            $this->plugin->getPluginURL().'/assets/h5p/h5peditor.js',
            $this->plugin->getPluginURL().'/assets/h5p/language/de.js',
            $this->plugin->getPluginURL().'/assets/h5p/h5p-display-options.js',
            $this->plugin->getPluginURL().'/assets/h5p/h5p-toggle.js'
        );

        foreach ($this->styles as $style) {
            PageLayout::addStylesheet($style);
        }
        foreach ($this->scripts as $script) {
            PageLayout::addScript($script);
        }

        $this->integration = $this->get_h5p_editor_settings();
    }

    public function get_h5p_editor_settings()
    {
        $settings = array(
            'baseUrl' => $GLOBALS['ABSOLUTE_URI_STUDIP'],
            'url' => $GLOBALS['ABSOLUTE_URI_STUDIP']."plugins_packages/RasmusFuhse/LernmodulePluginData/moduledata/".$this->mod->getId(),
            'postUserStatistics' => false,
            'ajax' => array(
                'setFinished' => URLHelper::getURL("plugins.php/lernmoduleplugin/h5p/set_finished/"),
                'contentUserData' => URLHelper::getURL("plugins.php/lernmoduleplugin/h5p/set_userdata/")
            ),
            'saveFreq' => 2,
            'siteUrl' => $GLOBALS['ABSOLUTE_URI_STUDIP'],
            //'libraryUrl' => $this->mod->getH5pLibURL(), //needed to fetch the library.json via ajax-request
            'l10n' => array(
                'H5P' => array(
                    'fullscreen' => dgettext("lernmoduleplugin","Vollbild"),
                    'disableFullscreen' => dgettext('lernmoduleplugin','Vollbild beenden'),
                    'download' => dgettext('lernmoduleplugin','Download'),
                    'copyrights' => dgettext('lernmoduleplugin','Nutzungsbedingung'),
                    'embed' => dgettext('lernmoduleplugin','Einbetten'),
                    'size' => dgettext('lernmoduleplugin','Größe'),
                    'showAdvanced' => dgettext('lernmoduleplugin','Zeige mehr'),
                    'hideAdvanced' => dgettext('lernmoduleplugin','Zuklappen'),
                    'advancedHelp' => dgettext('lernmoduleplugin','Include this script on your website if you want dynamic sizing of the embedded content:'),
                    'copyrightInformation' => dgettext('lernmoduleplugin','Rights of use'),
                    'close' => dgettext('lernmoduleplugin','Schließen'),
                    'title' => dgettext('lernmoduleplugin','Titel'),
                    'author' => dgettext('lernmoduleplugin','Author'),
                    'year' => dgettext('lernmoduleplugin','Jahr'),
                    'source' => dgettext('lernmoduleplugin','Quelle'),
                    'license' => dgettext('lernmoduleplugin','License'),
                    'thumbnail' => dgettext('lernmoduleplugin','Thumbnail'),
                    'noCopyrights' => dgettext('lernmoduleplugin','No copyright information available for this content.'),
                    'downloadDescription' => dgettext('lernmoduleplugin','Download this content as a H5P file.'),
                    'copyrightsDescription' => dgettext('lernmoduleplugin','View copyright information for this content.'),
                    'embedDescription' => dgettext('lernmoduleplugin','View the embed code for this content.'),
                    'h5pDescription' => dgettext('lernmoduleplugin','Visit H5P.org to check out more cool content.'),
                    'contentChanged' => dgettext('lernmoduleplugin','This content has changed since you last used it.'),
                    'startingOver' => dgettext("lernmoduleplugin","You'll be starting over."),
                    'by' => dgettext('lernmoduleplugin','von'),
                    'showMore' => dgettext('lernmoduleplugin','Mehr anzeigen'),
                    'showLess' => dgettext('lernmoduleplugin','Weniger anzeigen'),
                    'subLevel' => dgettext('lernmoduleplugin','Sublevel'),
                    'confirmDialogHeader' => dgettext('lernmoduleplugin','Aktion bestätigen'),
                    'confirmDialogBody' => dgettext('lernmoduleplugin','Wirklich fortfahren? Änderungen können nicht rückgängig gemacht werden.'),
                    'cancelLabel' => dgettext('lernmoduleplugin','Abbrechen'),
                    'confirmLabel' => dgettext('lernmoduleplugin','Bestätigen'),
                    'licenseU' => dgettext('lernmoduleplugin','Undisclosed'),
                    'licenseCCBY' => dgettext('lernmoduleplugin','Attribution'),
                    'licenseCCBYSA' => dgettext('lernmoduleplugin','Attribution-ShareAlike'),
                    'licenseCCBYND' => dgettext('lernmoduleplugin','Attribution-NoDerivs'),
                    'licenseCCBYNC' => dgettext('lernmoduleplugin','Attribution-NonCommercial'),
                    'licenseCCBYNCSA' => dgettext('lernmoduleplugin','Attribution-NonCommercial-ShareAlike'),
                    'licenseCCBYNCND' => dgettext('lernmoduleplugin','Attribution-NonCommercial-NoDerivs'),
                    'licenseCC40' => dgettext('lernmoduleplugin','4.0 International'),
                    'licenseCC30' => dgettext('lernmoduleplugin','3.0 Unported'),
                    'licenseCC25' => dgettext('lernmoduleplugin','2.5 Generic'),
                    'licenseCC20' => dgettext('lernmoduleplugin','2.0 Generic'),
                    'licenseCC10' => dgettext('lernmoduleplugin','1.0 Generic'),
                    'licenseGPL' => dgettext('lernmoduleplugin','General Public License'),
                    'licenseV3' => dgettext('lernmoduleplugin','Version 3'),
                    'licenseV2' => dgettext('lernmoduleplugin','Version 2'),
                    'licenseV1' => dgettext('lernmoduleplugin','Version 1'),
                    'licensePD' => dgettext('lernmoduleplugin','Public Domain'),
                    'licenseCC010' => dgettext('lernmoduleplugin','CC0 1.0 Universal (CC0 1.0) Public Domain Dedication'),
                    'licensePDM' => dgettext('lernmoduleplugin','Public Domain Mark'),
                    'licenseC' => dgettext('lernmoduleplugin','Copyright'),
                    'contentType' => dgettext('lernmoduleplugin','Inhaltstyp'),
                    'licenseExtras' => dgettext('lernmoduleplugin','License Extras'),
                    'changes' => dgettext('lernmoduleplugin','Changelog'),
                )
            ),
            'hubIsEnabled' => false,
            'reportingIsEnabled' => FALSE,
            'libraryConfig' => null, //$h5p->getLibraryConfig(),
            'crossorigin' => null,
        );

        $settings['user'] = array(
            'name' => $GLOBALS['user']->getFullName(),
            'mail' => $GLOBALS['user']->email
        );
        $settings['core'] = array(
            'styles' => array(
                $this->plugin->getPluginURL()."/assets/h5p/h5p.css",
                $this->plugin->getPluginURL()."/assets/h5p/h5p-confirmation-dialog.css",
                $this->plugin->getPluginURL()."/assets/h5p/h5p-core-button.css"
            ),
            'scripts' => array(
                $this->plugin->getPluginURL()."/assets/h5p/jquery.js",
                $this->plugin->getPluginURL()."/assets/h5p/h5p.js",
                $this->plugin->getPluginURL()."/assets/h5p/h5p-event-dispatcher.js",
                $this->plugin->getPluginURL()."/assets/h5p/h5p-x-api-event.js",
                $this->plugin->getPluginURL()."/assets/h5p/h5p-x-api.js",
                $this->plugin->getPluginURL()."/assets/h5p/h5p-content-type.js",
                $this->plugin->getPluginURL()."/assets/h5p/h5p-confirmation-dialog.js",
                $this->plugin->getPluginURL()."/assets/h5p/h5p-action-bar.js"
            )
        );
        $settings['editor'] = array(
            'filesPath' => "/wordpress/wp-content/uploads/h5p/editor",
            "fileIcon" => array(
                "path" => "http://localhost/wordpress/wp-content/plugins/h5p/h5p-editor-php-library/images/binary-file.png",
                "width" => 50,
                "height" => 50
            ),
            'ajaxPath' => URLHelper::getURL("plugins.php/lernmoduleplugin/h5peditor/ajax", array('cid' => Context::get()->id, 'cmd' => ""), true), //path to load libraries
            'libraryUrl' => "http://localhost/wordpress/wp-content/plugins/h5p/h5p-editor-php-library/",
            'copyrightSemantics' => array(), // TODO
            'metadataSemantics' => json_decode("[{\"name\":\"title\",\"type\":\"text\",\"label\":\"Titel\",\"placeholder\":\"La Gioconda\"},{\"name\":\"license\",\"type\":\"select\",\"label\":\"License\",\"default\":\"U\",\"options\":[{\"value\":\"U\",\"label\":\"Undisclosed\"},{\"type\":\"optgroup\",\"label\":\"Creative Commons\",\"options\":[{\"value\":\"CC BY\",\"label\":\"Attribution (CC BY)\",\"versions\":[{\"value\":\"4.0\",\"label\":\"4.0 International\"},{\"value\":\"3.0\",\"label\":\"3.0 Unported\"},{\"value\":\"2.5\",\"label\":\"2.5 Generic\"},{\"value\":\"2.0\",\"label\":\"2.0 Generic\"},{\"value\":\"1.0\",\"label\":\"1.0 Generic\"}]},{\"value\":\"CC BY-SA\",\"label\":\"Attribution-ShareAlike (CC BY-SA)\",\"versions\":[{\"value\":\"4.0\",\"label\":\"4.0 International\"},{\"value\":\"3.0\",\"label\":\"3.0 Unported\"},{\"value\":\"2.5\",\"label\":\"2.5 Generic\"},{\"value\":\"2.0\",\"label\":\"2.0 Generic\"},{\"value\":\"1.0\",\"label\":\"1.0 Generic\"}]},{\"value\":\"CC BY-ND\",\"label\":\"Attribution-NoDerivs (CC BY-ND)\",\"versions\":[{\"value\":\"4.0\",\"label\":\"4.0 International\"},{\"value\":\"3.0\",\"label\":\"3.0 Unported\"},{\"value\":\"2.5\",\"label\":\"2.5 Generic\"},{\"value\":\"2.0\",\"label\":\"2.0 Generic\"},{\"value\":\"1.0\",\"label\":\"1.0 Generic\"}]},{\"value\":\"CC BY-NC\",\"label\":\"Attribution-NonCommercial (CC BY-NC)\",\"versions\":[{\"value\":\"4.0\",\"label\":\"4.0 International\"},{\"value\":\"3.0\",\"label\":\"3.0 Unported\"},{\"value\":\"2.5\",\"label\":\"2.5 Generic\"},{\"value\":\"2.0\",\"label\":\"2.0 Generic\"},{\"value\":\"1.0\",\"label\":\"1.0 Generic\"}]},{\"value\":\"CC BY-NC-SA\",\"label\":\"Attribution-NonCommercial-ShareAlike (CC BY-NC-SA)\",\"versions\":[{\"value\":\"4.0\",\"label\":\"4.0 International\"},{\"value\":\"3.0\",\"label\":\"3.0 Unported\"},{\"value\":\"2.5\",\"label\":\"2.5 Generic\"},{\"value\":\"2.0\",\"label\":\"2.0 Generic\"},{\"value\":\"1.0\",\"label\":\"1.0 Generic\"}]},{\"value\":\"CC BY-NC-ND\",\"label\":\"Attribution-NonCommercial-NoDerivs (CC BY-NC-ND)\",\"versions\":[{\"value\":\"4.0\",\"label\":\"4.0 International\"},{\"value\":\"3.0\",\"label\":\"3.0 Unported\"},{\"value\":\"2.5\",\"label\":\"2.5 Generic\"},{\"value\":\"2.0\",\"label\":\"2.0 Generic\"},{\"value\":\"1.0\",\"label\":\"1.0 Generic\"}]},{\"value\":\"CC0 1.0\",\"label\":\"Public Domain Dedication (CC0)\"},{\"value\":\"CC PDM\",\"label\":\"Public Domain Mark (PDM)\"}]},{\"value\":\"GNU GPL\",\"label\":\"General Public License v3\"},{\"value\":\"PD\",\"label\":\"Public Domain\"},{\"value\":\"ODC PDDL\",\"label\":\"Public Domain Dedication and Licence\"},{\"value\":\"C\",\"label\":\"Copyright\"}]},{\"name\":\"licenseVersion\",\"type\":\"select\",\"label\":\"License Version\",\"options\":[{\"value\":\"4.0\",\"label\":\"4.0 International\"},{\"value\":\"3.0\",\"label\":\"3.0 Unported\"},{\"value\":\"2.5\",\"label\":\"2.5 Generic\"},{\"value\":\"2.0\",\"label\":\"2.0 Generic\"},{\"value\":\"1.0\",\"label\":\"1.0 Generic\"}],\"optional\":true},{\"name\":\"yearFrom\",\"type\":\"number\",\"label\":\"Years (from)\",\"placeholder\":\"1991\",\"min\":\"-9999\",\"max\":\"9999\",\"optional\":true},{\"name\":\"yearTo\",\"type\":\"number\",\"label\":\"Years (to)\",\"placeholder\":\"1992\",\"min\":\"-9999\",\"max\":\"9999\",\"optional\":true},{\"name\":\"source\",\"type\":\"text\",\"label\":\"Source\",\"placeholder\":\"https:\/\/\",\"optional\":true},{\"name\":\"authors\",\"type\":\"list\",\"field\":{\"name\":\"author\",\"type\":\"group\",\"fields\":[{\"label\":\"Author's name\",\"name\":\"name\",\"optional\":true,\"type\":\"text\"},{\"name\":\"role\",\"type\":\"select\",\"label\":\"Author's role\",\"default\":\"Author\",\"options\":[{\"value\":\"Author\",\"label\":\"Autor\"},{\"value\":\"Editor\",\"label\":\"Editor\"},{\"value\":\"Licensee\",\"label\":\"Licensee\"},{\"value\":\"Originator\",\"label\":\"Originator\"}]}]}},{\"name\":\"licenseExtras\",\"type\":\"text\",\"widget\":\"textarea\",\"label\":\"License Extras\",\"optional\":true,\"description\":\"Any additional information about the license\"},{\"name\":\"changes\",\"type\":\"list\",\"field\":{\"name\":\"change\",\"type\":\"group\",\"label\":\"Changelog\",\"fields\":[{\"name\":\"date\",\"type\":\"text\",\"label\":\"Date\",\"optional\":true},{\"name\":\"author\",\"type\":\"text\",\"label\":\"Changed by\",\"optional\":true},{\"name\":\"log\",\"type\":\"text\",\"widget\":\"textarea\",\"label\":\"Description of change\",\"placeholder\":\"Photo cropped, text changed, etc.\",\"optional\":true}]}},{\"name\":\"authorComments\",\"type\":\"text\",\"widget\":\"textarea\",\"label\":\"Author comments\",\"description\":\"Comments for the editor of the content (This text will not be published as a part of copyright info)\",\"optional\":true},{\"name\":\"contentType\",\"type\":\"text\",\"widget\":\"none\"}]"), // TODO
            'assets' => array(
                'css' => array(
                    $this->plugin->getPluginURL()."/assets/h5p/h5p.css",
                    $this->plugin->getPluginURL()."/assets/h5p/h5p-confirmation-dialog.css",
                    $this->plugin->getPluginURL()."/assets/h5p/h5p-core-button.css",
                    $this->plugin->getPluginURL()."/assets/h5p/darkroom.css",
                    $this->plugin->getPluginURL()."/assets/h5p/h5p-hub-client.css",
                    $this->plugin->getPluginURL()."/assets/h5p/fonts.css",
                    $this->plugin->getPluginURL()."/assets/h5p/application.css",
                    $this->plugin->getPluginURL()."/assets/h5p/zebra_datepicker.min.css"
                ),
                'js' => array(
                    $this->plugin->getPluginURL()."/assets/h5p/jquery.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5p.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5p-event-dispatcher.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5p-x-api-event.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5p-x-api.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5p-content-type.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5p-confirmation-dialog.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5p-action-bar.js",
                    //"\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5p-hub-client.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-semantic-structure.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-library-selector.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-form.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-text.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-html.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-number.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-textarea.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-file-uploader.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-file.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-image.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-image-popup.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-av.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-group.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-boolean.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-list.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-list-editor.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-library.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-library-list-cache.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-select.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-selector-hub.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-selector-legacy.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-dimensions.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-coordinates.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-none.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-metadata.js?v=1",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-metadata-author-widget.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-metadata-changelog-widget.js",
                    $this->plugin->getPluginURL()."/assets/h5p/h5peditor-pre-save.js",
                    $this->plugin->getPluginURL()."/vendor/h5p-editor/ckeditor/ckeditor.js"
                )
            ),
            'deleteMessage' => dgettext("lernmoduleplugin","Bist du sicher, dass du diesen Inhalt löschen willst?"),
            'apiVersion' => array(
                'majorVersion' => 1,
                'minorVersion' => 19
            )
        );
        //"core":{"styles":["\/wordpress\/wp-content\/plugins\/h5p\/h5p-php-library\/styles\/h5p.css?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-php-library\/styles\/h5p-confirmation-dialog.css?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-php-library\/styles\/h5p-core-button.css?ver=1.11.3"],"scripts":["\/wordpress\/wp-content\/plugins\/h5p\/h5p-php-library\/js\/jquery.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-php-library\/js\/h5p.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-php-library\/js\/h5p-event-dispatcher.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-php-library\/js\/h5p-x-api-event.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-php-library\/js\/h5p-x-api.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-php-library\/js\/h5p-content-type.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-php-library\/js\/h5p-confirmation-dialog.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-php-library\/js\/h5p-action-bar.js?ver=1.11.3"]},"loadedJs":[],"loadedCss":[],"editor":{"filesPath":"\/wordpress\/wp-content\/uploads\/h5p\/editor","fileIcon":{"path":"http:\/\/localhost\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/images\/binary-file.png","width":50,"height":50},"ajaxPath":"http:\/\/localhost\/wordpress\/wp-admin\/admin-ajax.php?token=40a4e4c421&action=h5p_","libraryUrl":"http:\/\/localhost\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/","copyrightSemantics":{"name":"copyright","type":"group","label":"Copyright information","fields":[{"name":"title","type":"text","label":"Titel","placeholder":"La Gioconda","optional":true},{"name":"author","type":"text","label":"Autor","placeholder":"Leonardo da Vinci","optional":true},{"name":"year","type":"text","label":"Year(s)","placeholder":"1503 - 1517","optional":true},{"name":"source","type":"text","label":"Source","placeholder":"http:\/\/en.wikipedia.org\/wiki\/Mona_Lisa","optional":true,"regexp":{"pattern":"^http[s]?:\/\/.+","modifiers":"i"}},{"name":"license","type":"select","label":"License","default":"U","options":[{"value":"U","label":"Undisclosed"},{"value":"CC BY","label":"Attribution","versions":[{"value":"4.0","label":"4.0 International"},{"value":"3.0","label":"3.0 Unported"},{"value":"2.5","label":"2.5 Generic"},{"value":"2.0","label":"2.0 Generic"},{"value":"1.0","label":"1.0 Generic"}]},{"value":"CC BY-SA","label":"Attribution-ShareAlike","versions":[{"value":"4.0","label":"4.0 International"},{"value":"3.0","label":"3.0 Unported"},{"value":"2.5","label":"2.5 Generic"},{"value":"2.0","label":"2.0 Generic"},{"value":"1.0","label":"1.0 Generic"}]},{"value":"CC BY-ND","label":"Attribution-NoDerivs","versions":[{"value":"4.0","label":"4.0 International"},{"value":"3.0","label":"3.0 Unported"},{"value":"2.5","label":"2.5 Generic"},{"value":"2.0","label":"2.0 Generic"},{"value":"1.0","label":"1.0 Generic"}]},{"value":"CC BY-NC","label":"Attribution-NonCommercial","versions":[{"value":"4.0","label":"4.0 International"},{"value":"3.0","label":"3.0 Unported"},{"value":"2.5","label":"2.5 Generic"},{"value":"2.0","label":"2.0 Generic"},{"value":"1.0","label":"1.0 Generic"}]},{"value":"CC BY-NC-SA","label":"Attribution-NonCommercial-ShareAlike","versions":[{"value":"4.0","label":"4.0 International"},{"value":"3.0","label":"3.0 Unported"},{"value":"2.5","label":"2.5 Generic"},{"value":"2.0","label":"2.0 Generic"},{"value":"1.0","label":"1.0 Generic"}]},{"value":"CC BY-NC-ND","label":"Attribution-NonCommercial-NoDerivs","versions":[{"value":"4.0","label":"4.0 International"},{"value":"3.0","label":"3.0 Unported"},{"value":"2.5","label":"2.5 Generic"},{"value":"2.0","label":"2.0 Generic"},{"value":"1.0","label":"1.0 Generic"}]},{"value":"GNU GPL","label":"General Public License","versions":[{"value":"v3","label":"Version 3"},{"value":"v2","label":"Version 2"},{"value":"v1","label":"Version 1"}]},{"value":"PD","label":"Public Domain","versions":[{"value":"-","label":"-"},{"value":"CC0 1.0","label":"CC0 1.0 Universal"},{"value":"CC PDM","label":"Public Domain Mark"}]},{"value":"C","label":"Copyright"}]},{"name":"version","type":"select","label":"License Version","options":[]}]},"metadataSemantics":[{"name":"title","type":"text","label":"Titel","placeholder":"La Gioconda"},{"name":"license","type":"select","label":"License","default":"U","options":[{"value":"U","label":"Undisclosed"},{"type":"optgroup","label":"Creative Commons","options":[{"value":"CC BY","label":"Attribution (CC BY)","versions":[{"value":"4.0","label":"4.0 International"},{"value":"3.0","label":"3.0 Unported"},{"value":"2.5","label":"2.5 Generic"},{"value":"2.0","label":"2.0 Generic"},{"value":"1.0","label":"1.0 Generic"}]},{"value":"CC BY-SA","label":"Attribution-ShareAlike (CC BY-SA)","versions":[{"value":"4.0","label":"4.0 International"},{"value":"3.0","label":"3.0 Unported"},{"value":"2.5","label":"2.5 Generic"},{"value":"2.0","label":"2.0 Generic"},{"value":"1.0","label":"1.0 Generic"}]},{"value":"CC BY-ND","label":"Attribution-NoDerivs (CC BY-ND)","versions":[{"value":"4.0","label":"4.0 International"},{"value":"3.0","label":"3.0 Unported"},{"value":"2.5","label":"2.5 Generic"},{"value":"2.0","label":"2.0 Generic"},{"value":"1.0","label":"1.0 Generic"}]},{"value":"CC BY-NC","label":"Attribution-NonCommercial (CC BY-NC)","versions":[{"value":"4.0","label":"4.0 International"},{"value":"3.0","label":"3.0 Unported"},{"value":"2.5","label":"2.5 Generic"},{"value":"2.0","label":"2.0 Generic"},{"value":"1.0","label":"1.0 Generic"}]},{"value":"CC BY-NC-SA","label":"Attribution-NonCommercial-ShareAlike (CC BY-NC-SA)","versions":[{"value":"4.0","label":"4.0 International"},{"value":"3.0","label":"3.0 Unported"},{"value":"2.5","label":"2.5 Generic"},{"value":"2.0","label":"2.0 Generic"},{"value":"1.0","label":"1.0 Generic"}]},{"value":"CC BY-NC-ND","label":"Attribution-NonCommercial-NoDerivs (CC BY-NC-ND)","versions":[{"value":"4.0","label":"4.0 International"},{"value":"3.0","label":"3.0 Unported"},{"value":"2.5","label":"2.5 Generic"},{"value":"2.0","label":"2.0 Generic"},{"value":"1.0","label":"1.0 Generic"}]},{"value":"CC0 1.0","label":"Public Domain Dedication (CC0)"},{"value":"CC PDM","label":"Public Domain Mark (PDM)"}]},{"value":"GNU GPL","label":"General Public License v3"},{"value":"PD","label":"Public Domain"},{"value":"ODC PDDL","label":"Public Domain Dedication and Licence"},{"value":"C","label":"Copyright"}]},{"name":"licenseVersion","type":"select","label":"License Version","options":[{"value":"4.0","label":"4.0 International"},{"value":"3.0","label":"3.0 Unported"},{"value":"2.5","label":"2.5 Generic"},{"value":"2.0","label":"2.0 Generic"},{"value":"1.0","label":"1.0 Generic"}],"optional":true},{"name":"yearFrom","type":"number","label":"Years (from)","placeholder":"1991","min":"-9999","max":"9999","optional":true},{"name":"yearTo","type":"number","label":"Years (to)","placeholder":"1992","min":"-9999","max":"9999","optional":true},{"name":"source","type":"text","label":"Source","placeholder":"https:\/\/","optional":true},{"name":"authors","type":"list","field":{"name":"author","type":"group","fields":[{"label":"Author's name","name":"name","optional":true,"type":"text"},{"name":"role","type":"select","label":"Author's role","default":"Author","options":[{"value":"Author","label":"Autor"},{"value":"Editor","label":"Editor"},{"value":"Licensee","label":"Licensee"},{"value":"Originator","label":"Originator"}]}]}},{"name":"licenseExtras","type":"text","widget":"textarea","label":"License Extras","optional":true,"description":"Any additional information about the license"},{"name":"changes","type":"list","field":{"name":"change","type":"group","label":"Changelog","fields":[{"name":"date","type":"text","label":"Date","optional":true},{"name":"author","type":"text","label":"Changed by","optional":true},{"name":"log","type":"text","widget":"textarea","label":"Description of change","placeholder":"Photo cropped, text changed, etc.","optional":true}]}},{"name":"authorComments","type":"text","widget":"textarea","label":"Author comments","description":"Comments for the editor of the content (This text will not be published as a part of copyright info)","optional":true},{"name":"contentType","type":"text","widget":"none"}],"assets":{"css":["\/wordpress\/wp-content\/plugins\/h5p\/h5p-php-library\/styles\/h5p.css?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-php-library\/styles\/h5p-confirmation-dialog.css?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-php-library\/styles\/h5p-core-button.css?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/libs\/darkroom.css?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/styles\/css\/h5p-hub-client.css?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/styles\/css\/fonts.css?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/styles\/css\/application.css?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/styles\/css\/libs\/zebra_datepicker.min.css?ver=1.11.3"],"js":["\/wordpress\/wp-content\/plugins\/h5p\/h5p-php-library\/js\/jquery.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-php-library\/js\/h5p.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-php-library\/js\/h5p-event-dispatcher.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-php-library\/js\/h5p-x-api-event.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-php-library\/js\/h5p-x-api.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-php-library\/js\/h5p-content-type.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-php-library\/js\/h5p-confirmation-dialog.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-php-library\/js\/h5p-action-bar.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5p-hub-client.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-semantic-structure.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-library-selector.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-form.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-text.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-html.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-number.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-textarea.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-file-uploader.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-file.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-image.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-image-popup.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-av.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-group.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-boolean.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-list.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-list-editor.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-library.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-library-list-cache.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-select.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-selector-hub.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-selector-legacy.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-dimensions.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-coordinates.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-none.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-metadata.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-metadata-author-widget.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-metadata-changelog-widget.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/scripts\/h5peditor-pre-save.js?ver=1.11.3","\/wordpress\/wp-content\/plugins\/h5p\/h5p-editor-php-library\/ckeditor\/ckeditor.js?ver=1.11.3"]},"deleteMessage":"Bist du sicher, dass du diesen Inhalt l\u00f6schen willst?","apiVersion":{"majorVersion":1,"minorVersion":19}}
        /*$libraryname = $library['name']." ".$library['major_version'].".".$library['minor_version'];
        $this->attempt;
        $userdata = $this->attempt['customdata'] ? $this->attempt['customdata']->getArrayCopy() : array();
        $userdata = $userdata['h5pstate'] ? json_encode((array) $userdata['h5pstate']) : "{}";
        $settings['contents'] = array(
            "cid-" . $this->mod->getId() => array(
                'library' => $libraryname, //name of the runnable library with space instead of minus
                'jsonContent' => file_get_contents($this->mod->getPath()."/content/content.json"),
                'fullscreen' => 1,
                'exportUrl' => null,
                'embedCode' => '<iframe src="">',
                'resizeCode' => "<script src=\"".$GLOBALS['ABSOLUTE_URI_STUDIP']."plugins_packages/RasmusFuhse/LernmodulePluginassets/h5p/h5p-resizer.js\" charset=\"UTF-8\"><\/script>",
                'url' => "",
                'contentUrl' => $GLOBALS['ABSOLUTE_URI_STUDIP']."plugins_packages/RasmusFuhse/LernmodulePluginData/moduledata/".$this->mod->getId()."/content",
                'title' => $this->mod['name'],
                'displayOptions' => array(
                    'frame' => true,
                    'export' => true,
                    'embed' => true,
                    'copyright' => true,
                    'icon' => true
                ),
                'metadata' => array(
                    'title' => $this->mod['name'],
                    'license' => "U"
                ),
                'contentUserData' => array(
                    array('state' => $userdata)
                )
            )
        );*/
        return $settings;
    }

    public function ajax_action() {
        $cmd = Request::get("cmd");
        if ($cmd === "libraries" && Request::get("machineName")) {
            //deliver the JSON to display the lib in the editor after it has been selected
            $main_library = H5PLib::findVersion(Request::get("machineName"), Request::get("majorVersion"), Request::get("minorVersion"));
            if (!$main_library['allowed']) {
                throw new AccessDeniedException();
            }
            $javascripts = array();
            $css = array();

            $library = json_decode(file_get_contents($main_library->getPath()."/library.json"), true);
            $libs = array();
            $lib_ids = array();
            foreach ((array) $library['editorDependencies'] as $dependency) {
                //TODO
                $lib = H5PLib::findVersion($dependency['machineName'], $dependency['majorVersion'], $dependency['minorVersion']);
                if ($lib) {
                    $libs[] = $lib;
                    $lib_ids[] = $lib->getId();
                }
            }




            //Once again the topological ordering of libs:
            $edges = array();
            foreach ($libs as $lib) {
                foreach ($lib->getSubLibs() as $sublib) {
                    if (!in_array($sublib->getId(), $lib_ids)) {
                        $lib_ids[] = $sublib->getId();
                        $libs[] = $sublib;
                    }
                    $edges[] = array(
                        $sublib->getId(),
                        $lib->getId()
                    );
                }
            }
            $lib_ids = LernmodulePlugin::topologicalSort($lib_ids, $edges);
            if ($lib_ids === false) {
                throw new Exception("Could not sort dependencies of H5P module.");
            }
            $libs_sorted = array();
            foreach ($lib_ids as $lib_id) {
                foreach ($libs as $i => $lib) {
                    if ($lib->getId() == $lib_id) {
                        $libs_sorted[] = $lib;
                        unset($libs[$i]);
                        break;
                    }
                }
            }
            $libs = $libs_sorted;


            //now fetch the js and css files:
            $language = "de";
            $translations = array();
            foreach ($libs as $lib) {
                if ($lib) {
                    foreach ($lib->getFiles("js") as $js) {
                        $javascripts[] = H5PLernmodul::getH5pLibURL() . "/" . $js;
                    }
                    foreach ($lib->getFiles("css") as $style) {
                        $css[] = H5PLernmodul::getH5pLibURL() . "/" . $style;
                    }
                }

                if (file_exists($lib->getPath() . "/language/".$language.".json")) {
                    $translation = json_decode(file_get_contents($lib->getPath() . "/language/".$language.".json"), true);
                    if ($translation) {
                        $translations[$lib['name']] = $translation;
                    }
                }
            }



            $output = array(
                'semantics' => json_decode(file_get_contents($main_library->getPath()."/semantics.json"), true),
                'language' => file_exists($main_library->getPath()."/language/de.json")
                    ? file_get_contents($main_library->getPath()."/language/de.json")
                    : array(),
                'javascript' => $javascripts,
                'css' => $css,
                'translations' => $translations
            );
            $this->render_json($output);
            return;
        } elseif ($cmd === "libraries") {
            //load all libraries for editor at the beginning
            $statement = DBManager::get()->prepare("
                    SELECT lernmodule_h5plibs.*
                    FROM lernmodule_h5plibs
                    WHERE lernmodule_h5plibs.allowed = '1'
                        AND lernmodule_h5plibs.runnable = '1'
                        AND lib_id = (
                            SELECT l2.lib_id
                            FROM lernmodule_h5plibs AS l2
                            WHERE l2.name = lernmodule_h5plibs.name
                            ORDER BY major_version DESC, minor_version DESC
                            LIMIT 1
                        )
                    ORDER BY name ASC
                ");
            $statement->execute();
            $libs = array();
            $data = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($data as $d) {
                $libs[] = H5PLib::buildExisting($d);
            }
            $output = array();
            foreach ($libs as $lib) {
                $data = $lib->getLibraryData();
                $output[] = array(
                    'name' => $lib['name'],
                    'title' => $data['title'],
                    'majorVersion' => $lib['major_version'],
                    'minorVersion' => $lib['minor_version'],
                    'tutorialUrl' => "",
                    'restricted' => false,
                    'metadataSettings' => null
                );
            }
            $this->render_json($output);
            return;
        } elseif ($cmd === "files") {

            $this->render_json(array('ok' => "no"));
        }
        $this->render_text("errr .. ok");
    }


}
