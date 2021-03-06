<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload() {
        $loader = Zend_Loader_Autoloader::getInstance();

        $resourceTypes = array('form' => array('path' => 'forms/', 'namespace' => 'Form',),);
        $modules = array('frontpage', 'users', 'auth', 'exhibitions', 'news', 'gallery');

        foreach ($modules as $module) {
            $loader->pushAutoloader(new Zend_Application_Module_Autoloader(
                array(
                    'namespace' => ucfirst($module),
                    'basePath' => APPLICATION_PATH . '/modules/' . $module, 'resourceTypes' => $resourceTypes)
            ));
        }

        $loader->pushAutoloader(new Application_Models_Loader());
        return $loader;
    }

    protected function _initRouter() {
        $ctrl = Zend_Controller_Front::getInstance();
        $router = $ctrl->getRouter();

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes.ini');
        $router->addConfig($config, 'global');

        return $router;
    }

    protected function _initSession() {
        Zend_Session::start();
    }

    protected function _initConfig() {
        $config = new Zend_Config($this->getOptions());
        Zend_Registry::set('config', $config);
        return $config;
    }

    protected function _initTranslate() {
        $translate = new Zend_Translate(array(
            'adapter' => 'csv',
            'content' => APPLICATION_PATH . '/../i18n/es.csv',
            'locale'  => 'es',
            'delimiter' => ','
        ));

        // PHP's settings for encoding
        mb_internal_encoding('UTF-8');
        mb_http_output('UTF-8');

        // Set for localization
        setlocale(LC_CTYPE, 'en_US.UTF8');
        Zend_Locale::setDefault('en_US.UTF8');

        Zend_Registry::set('Zend_Translate', $translate);
        Zend_Form::setDefaultTranslator($translate);
        Zend_Validate_Abstract::setDefaultTranslator($translate);

        return $translate;
    }

    protected function _initView() {
        $renderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $renderer->setViewSuffix('php');
        Zend_Controller_Action_HelperBroker::addHelper($renderer);

        $options = $this->getOptions();

        $view = new Zend_View();

        $view->headTitle($options['system']['name']);
        $view->doctype($options['resources']['layout']['doctype']);
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=utf-8');
        $view->headScript()->appendFile('/js/jquery-1.6.2.min.js', 'text/javascript')
                           ->appendFile('/js/jquery.countdown.min.js', 'text/javascript')
                           ->appendFile('/js/init.js', 'text/javascript');
        $view->headLink()->appendStylesheet('/css/style.css');
        return $view;
    }
}
