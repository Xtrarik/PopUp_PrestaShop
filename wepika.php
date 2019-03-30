<?php
/**
 * Project : Wepika Pop Up test
 * @author Arik HAMDI
 **/
if (!defined('_PS_VERSION_'))
    exit;

class Wepika extends Module
{

    public function __construct()
    {
        $this->name = 'wepika';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'Arik Hamdi';
        $this->need_instance = 0;
        $this->context = Context::getContext();
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Wepika Pop Up');
        $this->description = $this->l('Affichage sur le front office d\'une popub indiquant qu\'une commande à eu lieu récemment');

        require_once('models/WepikaManager.php');
    }

    public function install()
    {
            return parent::install()
                && Configuration::updateValue('visibility_time', 3)
                && Configuration::updateValue('frequency', 5)
                && Configuration::updateValue('period_of_time', 15)
                && $this->registerHook('header');
    }

    public function uninstall()
    {
        return parent::uninstall()
            && Configuration::deleteByName('visibility_time')
            && Configuration::deleteByName('frequency')
            && Configuration::deleteByName('period_of_time');
    }

    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit'.$this->name))
        {
            $period_of_time = Tools::getValue('period_of_time');
            $visibility_time = Tools::getValue('visibility_time');
            $frequency = Tools::getValue('frequency');
            if (!$period_of_time  || empty($period_of_time) || !Validate::isGenericName($period_of_time))
                $output .= $this->displayError( $this->l('Invalid Configuration value') );
            else
            {
                Configuration::updateValue('period_of_time', $period_of_time);
                Configuration::updateValue('visibility_time', $visibility_time);
                Configuration::updateValue('frequency', $frequency);
                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }
        return $output.$this->displayForm();
    }

    public function displayForm()
    {

        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT'); // Get default Language

        $visibility_timer = [];
        for ($i = 5; $i <= 30; $i+=5)
        {
                $visibility_timer[] =
                    array('id_option' => $i,
                          'name' => $this->l($i. " secondes")
                    );
        }

        $frequency_timer[] =
            array('id_option' => 45,
                'name' => $this->l('45 secondes')
            );
        for ($i = 60; $i <= 1200; $i+=30)
        {
            if($i <= 300 || ($i > 300 && $i <= 600 && $i%60 == 0) || ($i > 600 && $i%300 == 0))    // Delays every 30 sec till 300, then 1minutes till 10 minutes, then 5 minutes delays
                $frequency_timer[] =
                    array('id_option' => $i,
                          'name' => ($i< 120)?$this->l($i . " secondes"): $this->l((int)($i/60).(($i%60!=0)?" mins ".$i%60 . " sec":" minutes"))
                    );
        }


        $periodSelecter[] = array(
            'id_option' => 1,
            'name' => $this->l("1 jour")
        );
        for ($i = 2; $i <= 90; $i++)
        {
            if($i <=5 || ($i > 5 && $i <= 15 && $i%5 ==0 ) || ($i >30 && $i%30 == 0))
                $periodSelecter[] = array(
                    'id_option' => $i,
                    'name' => $this->l($i . " jours")
                );
        }


        // Init Fields form array
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Configuration'),
            ),
            'input' => array(
                array(
                    'type' => 'select',
                    'label' => $this->l('Période ? Intervalle ?'),
                    'name' => 'period_of_time',
                    'required' => true,
                    'options' => array(
                        'query' => $periodSelecter,
                        'id' => 'id_option',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Durée de visibilité du pop up : '),
                    'name' => 'visibility_time',
                    'required' => false,
                    'options' => array(
                        'query' => $visibility_timer,
                        'id' => 'id_option',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Frequence d\'apparition de la fenetre de pop up : '),
                    'name' => 'frequency',
                    'required' => false,
                    'desc' => $this->l('!Attention! le decompte de la frequence d\'apparition repart à zero au moment de son apparition. 
                                                    Veuillez en tenir compte au moment de choisir la durée de visibilité.'),
                    'options' => array(
                        'query' => $frequency_timer,
                        'id' => 'id_option',
                        'name' => 'name'
                    )
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'button'
            )

        );


        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = array(
            'save' =>
                array(
                    'desc' => $this->l('Save'),
                    'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                        '&token='.Tools::getAdminTokenLite('AdminModules'),
                ),
            'back' => array(
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        // Load current value
        $helper->tpl_vars = array(
            'fields_value' => array(
                'period_of_time' => Configuration::get('period_of_time'),
                'visibility_time' => Configuration::get('visibility_time'),
                'frequency' => Configuration::get('frequency')
            ),
        );

        return $helper->generateForm($fields_form);
    }

    public function hookHeader()
    {


        $alllastSelling = $this->getAllOrderDetails();

        MediaCore::addJsDef(array(
            'mp_ajax' => $this->_path.'ajax/ajax.php',
            'dataOrder' => $alllastSelling
        ));

        $this->context->smarty->assign('myArray', $alllastSelling);
        $this->context->smarty->assign(
            array(

                'visibility_time' => Configuration::get('visibility_time'),
                'frequency' => Configuration::get('frequency')
            )
        );

        $this->context->controller->addJS(($this->_path).'views/js/wepika.js', 'all');
        $this->context->controller->addCSS(($this->_path).'views/css/wepika.css', 'all');
        return $this->display(__FILE__, 'wepika.tpl');
    }

    public function getRandomOrderDetails()
    {
        $datetime = new DateTime();
        $delay = '-'.Configuration::get('period_of_time').' day';
        $datetime ->modify($delay);
        $date = $datetime->format('Y-m-d');

        $image_type = 'home_default';
        $id_lang = Context::getContext()->language->id;
        $lastSelling = WepikaManager::getLastSelling($date);

            $country = CustomerCore::getCurrentCountry($lastSelling[0]['id_customer']);
            $customerCountry = CountryCore::getNameById($lastSelling[0]['id_lang'],$country);
            $product = new Product((int)$lastSelling[0]['product_id'], false, $id_lang);
            $img = $product->getCover($product->id);

            $img_url = $this->context->link->getImageLink(isset($product->link_rewrite) ? $product->link_rewrite : $product->name, (int)$img['id_image'], $image_type);

            $allOrderDetails[] = array(
                'firstname' => $lastSelling[0]['firstname'],
                'lastname' => $lastSelling[0]['lastname'],
                'city' => $lastSelling[0]['city'],
                'country' => $customerCountry,
                'date' => $lastSelling[0]['date'],
                'name' => ucfirst($product->name),
                'img' => $img_url
            );

        return $allOrderDetails;
    }
    public function getAllOrderDetails()
    {
        $datetime = new DateTime();
        $delay = '-'.Configuration::get('period_of_time').' day';
        $datetime ->modify($delay);
        $date = $datetime->format('Y-m-d');

        $image_type = 'home_default';
        $id_lang = Context::getContext()->language->id;
        $lastSelling = WepikaManager::getLastSelling($date);

        for($i = 0; $i < sizeof($lastSelling)-1; $i++)
        {
            $country = CustomerCore::getCurrentCountry($lastSelling[$i]['id_customer']);
            $customerCountry = CountryCore::getNameById($lastSelling[$i]['id_lang'],$country);
            $product = new Product((int)$lastSelling[$i]['product_id'], false, $id_lang);
            $img = $product->getCover($product->id);

            $img_url = $this->context->link->getImageLink(isset($product->link_rewrite) ? $product->link_rewrite : $product->name, (int)$img['id_image'], $image_type);

            $allOrderDetails[] = array(
                'firstname' => $lastSelling[$i]['firstname'],
                'lastname' => $lastSelling[$i]['lastname'],
                'city' => $lastSelling[$i]['city'],
                'country' => $customerCountry,
                'date' => $lastSelling[$i]['date'],
                'name' => ucfirst($product->name),
                'img' => $img_url
            );
        }

        return $allOrderDetails;
    }


}