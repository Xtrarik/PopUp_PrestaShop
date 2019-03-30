<?php
class WepikaManager extends ObjectModel
{
    /**
     * @param $period_of_time receives a date formatting like '%Y-%m-%d'
     * @return array containing a single random value between the current date and the one sent in parameter
     */
    public static function getLastSelling($period_of_time)
    {
        $p = _DB_PREFIX_;   //get the prefix variable for more flexibility

        $sql = "SELECT ".$p."customer.id_customer As id_customer, ".$p."customer.id_lang As id_lang, ".$p."order_detail.product_id As product_id, ".$p."customer.firstname As firstname, ".$p."customer.lastname As lastname, ".$p."address.city AS city, DATE_FORMAT(".$p."orders.date_add,'%d-%m-%Y') As date
              FROM ".$p."order_detail
              INNER JOIN ".$p."orders ON ".$p."orders.id_order = ".$p."order_detail.id_order
              INNER JOIN ".$p."customer ON ".$p."customer.id_customer = ".$p."orders.id_customer
              INNER JOIN ".$p."address ON ".$p."orders.id_address_delivery = ".$p."address.id_address
              WHERE DATE_FORMAT(".$p."orders.date_add,'%Y-%m-%d') > '" . $period_of_time . " 00:00:00'
              ORDER BY RAND()
              Limit 0,1";

        return Db::getInstance()->ExecuteS($sql);
    }

}