Instruction :

Pour l'exercice, voici ce que je te propose :
La création d'un module permettant un affichage sur le front office d'une popub indiquant qu'une commande à eu lieu récemment par un client X ou Y.
Le but de la manœuvre est de faire un peu de réassurance  auprès de la clientèle présente sur le site.

Grosso modo, il s'agit donc de récupérer une commande de moins de 24h (données paramétrables en back office), de manière aléatoire dans la base de donnée et d'afficher les informations d'un des produits achetés (nom du client, lieu, miniature du produit, date d'achat)

Tu trouveras probablement tout ce dont tu as besoin dans les tables : ps_order, ps_order_detail, ps_customer, ps_address
Je te laisse totalement libre de comment tu implémente cela, tant que tu le réalise sous forme d'un module Prestashop autonome. Libre à toi de rajouter des fonctionnalités, paramétrages qui te semblerait pertinents, le but étant quand même aussi de s'amuser un peu !

Je te conseillerais de réaliser cela sur Prestashop 1.6 qui est de loin la version la plus stable à ce jour (la 1.7 est à évitée).



SELECT ps_customer.firstname As firstname,ps_customer.lastname As lastname, ps_address.city AS city
                 FROM ps_customer
                 INNER JOIN ps_address ON ps_customer.id_customer = ps_address.id_customer
                 WHERE ps_customer.id_customer = 1



SELECT ps_customer.firstname As firstname,ps_customer.lastname As lastname, ps_address.city AS city, DATE_FORMAT(ps_orders.date_add,'%d-%m-%Y') As date
                 FROM ps_orders
                 INNER JOIN ps_customer ON ps_customer.id_customer = ps_orders.id_customer
                 INNER JOIN ps_address ON ps_customer.id_customer = ps_address.id_customer
                 WHERE ps_customer.id_customer = 1 AND DATE_FORMAT(ps_orders.date_add,'%Y-%m-%d') > '2019-03-28 06:00:00'


SELECT ps_order_detail.product_id As product_id, ps_customer.firstname As firstname,ps_customer.lastname As lastname, ps_address.city AS city, DATE_FORMAT(ps_orders.date_add,'%d-%m-%Y') As date
              FROM ps_order_detail
              INNER JOIN ps_orders ON ps_orders.id_order = ps_order_detail.id_order
              INNER JOIN ps_customer ON ps_customer.id_customer = ps_orders.id_customer
              INNER JOIN ps_address ON ps_customer.id_customer = ps_address.id_customer
              WHERE ps_customer.id_customer = 1 AND DATE_FORMAT(ps_orders.date_add,'%Y-%m-%d') > '2019-03-28 06:00:00'