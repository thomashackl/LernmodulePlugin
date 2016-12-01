<?php

require_once __DIR__."/lib/Lernmodul.php";

if (!isset($GLOBALS['FILESYSTEM_UTF8'])) {
    $GLOBALS['FILESYSTEM_UTF8'] = true;
}

class LernmodulePlugin extends StudIPPlugin implements StandardPlugin {

    public function getTabNavigation($course_id)
    {
        $tab = new Navigation(_("Lernmodule"), PluginEngine::getURL($this, array(), "lernmodule/overview"));
        $tab->setImage(Icon::create("learnmodule", "info_alt"));
        return array('lernmodule' => $tab);
    }

    public function getIconNavigation($course_id, $last_visit, $user_id)
    {
        $tab = new Navigation(_("Lernmodule"), PluginEngine::getURL($this, array(), "lernmodule/overview"));
        $new = Lernmodul::countBySQL("seminar_id = :course_id AND chdate >= :last_visit", compact('course_id', 'last_visit'));
        if ($new > 0) {
            $tab->setImage(Icon::create("learnmodule", "new", array('title' => sprintf(_("%s neue Lernmodule"), $new))));
        } else {
            $tab->setImage(Icon::create("learnmodule", "inactive", array('title' => _("Lernmodule"))));
        }
        return $tab;
    }

    public function getInfoTemplate($course_id)
    {
        return null;
    }

    public function perform($unconsumed_path)
    {
        $this->addStylesheet("assets/lernmodule.less");
        parent::perform($unconsumed_path);
    }

    static public function mayEditSandbox()
    {
        return $GLOBALS['perm']->have_perm("admin")
                    || RolePersistence::isAssignedRole($GLOBALS['user']->id, "Lernmodule-Admin");
    }

    public function getDisplayTitle()
    {
        return _("Lernmodule");
    }

}