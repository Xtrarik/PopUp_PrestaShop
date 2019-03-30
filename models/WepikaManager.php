<?php
class WepikaManager extends ObjectModel
{

    public static function getLastSelling($period_of_time)
    {
        $sql = "SELECT ps_customer.id_customer As id_customer, ps_customer.id_lang As id_lang, ps_order_detail.product_id As product_id, ps_customer.firstname As firstname,ps_customer.lastname As lastname, ps_address.city AS city, DATE_FORMAT(ps_orders.date_add,'%d-%m-%Y') As date
              FROM ps_order_detail
              INNER JOIN ps_orders ON ps_orders.id_order = ps_order_detail.id_order
              INNER JOIN ps_customer ON ps_customer.id_customer = ps_orders.id_customer
              INNER JOIN ps_address ON ps_orders.id_address_delivery = ps_address.id_address
              WHERE DATE_FORMAT(ps_orders.date_add,'%Y-%m-%d') < '" . $period_of_time . " 00:00:00'
              ORDER BY RAND()";

        return Db::getInstance()->ExecuteS($sql);

    }

}