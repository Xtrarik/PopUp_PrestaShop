<?php
class WepikaManager extends ObjectModel
{

    public function __construct()
    {
        $this->callDb();
    }

    public static function callDb()
    {
          $sql = "SELECT ps_customer.id_lang As id_lang, ps_order_detail.product_id As product_id, ps_customer.firstname As firstname,ps_customer.lastname As lastname, ps_address.city AS city, DATE_FORMAT(ps_orders.date_add,'%d-%m-%Y') As date
              FROM ps_order_detail
              INNER JOIN ps_orders ON ps_orders.id_order = ps_order_detail.id_order
              INNER JOIN ps_customer ON ps_customer.id_customer = ps_orders.id_customer
              INNER JOIN ps_address ON ps_customer.id_customer = ps_address.id_customer
              WHERE ps_customer.id_customer = 1 AND DATE_FORMAT(ps_orders.date_add,'%Y-%m-%d') > '2019-03-28 06:00:00'";

//        $sql = new DbQuery();
//        $sql->select('ps_customer.firstname,ps_customer.lastname,ps_country_lang.name');
//        $sql->from('ps_customer');
//        $sql->innerJoin( 'ps_country_lang','ps_customer.id_lang = ps_country_lang.id_lang');
//        $sql->where('id_customer = 1');
        return Db::getInstance()->ExecuteS($sql);

        //return new WepikaManager();
    }
}