<?php

namespace analib\Helpers;

/**
 * Description of PagesHelper
 *
 * @author acround
 */
class PagesHelper extends BaseHelper
{

    protected $script       = 'pager.js';
    protected $allwaysPages = false;

    /**
     * @return PagesHelper
     */
    public static function create($total = 0, $limit = 0, $start = 0)
    {
        /* @var $helper PagesHelper */
        $helper = self();
        $helper->
            setVariable('PAGER_TOTAL', $total)->
            setVariable('PAGER_LIMIT', $limit)->
            setVariable('PAGER_START', $start);
        return $helper;
    }

    /**
     * @param string $script
     * @return PagesHelper
     */
    public function setScript($script)
    {
        $this->script = $script;
        return $this;
    }

    public function getScript()
    {
        return $this->script;
    }

    /**
     *
     * @param boolean $allways
     * @return PagesHelper
     */
    public function allWaysPages($allways = null)
    {
        if ($allways !== null) {
            $this->allwaysPages = (boolean) $allways;
            return $this;
        } else {
            return $this->allwaysPages;
        }
    }

    /**
     * @return PagesHelper
     */
    public function run()
    {
        extract($this->variables);
        require $this->getTemplate();
        return $this;
    }

}
