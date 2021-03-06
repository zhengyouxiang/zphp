<?php
/**
 * User: shenzhe
 * Date: 13-6-17
 */


namespace ZPHP\Protocol\Adapter;
use ZPHP\Core;
use ZPHP\View;
use ZPHP\Core\Config;
use ZPHP\Protocol\IProtocol;

class Http implements IProtocol
{
    private $_action = 'main\\main';
    private $_method = 'main';
    private $_params = array();
    private $_view_mode = '';
    private $_tpl_file = '';

    /**
     * 直接 parse $_REQUEST
     * @param $_data
     * @return bool
     */
    public function parse($_data)
    {
        $data = $_data;
        $apn = Config::getField('project', 'action_name', 'a');
        $mpn = Config::getField('project', 'method_name', 'm');
        if (isset($data[$apn])) {
            $this->_action = \str_replace('/', '\\', $data[$apn]);
        }
        if (isset($data[$mpn])) {
            $this->_method = $data[$mpn];
        }
        $this->_params = $data;
        return true;
    }

    public function getAction()
    {
        return $this->_action;
    }

    public function getMethod()
    {
        return $this->_method;
    }

    public function getParams()
    {
        return $this->_params;
    }

    public function setViewMode($mode)
    {
        $this->_view_mode = $mode;
    }

    public function setTplFile($tpl)
    {
        $this->_tpl_file = $tpl;
    }

    public function display($model)
    {
        if (empty($this->_view_mode)) {
            $viewMode = Config::getField('project', 'view_mode', 'String');
        } else {
            $viewMode = $this->_view_mode;
        }
        $this->_view_mode = '';
        $view = View\Factory::getInstance($viewMode);
        $view->setModel($model);
        if ('Php' === $viewMode) {
            $view->setTpl($this->_tpl_file);
        }
        $view->display();

    }
}
