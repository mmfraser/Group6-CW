
select 
`sd`.`date` AS `SALE_DATE`,
CONCAT(MONTH(`sd`.`date`), "-", YEAR(`sd`.`date`)) AS `SALE_MONTHYEAR`,
YEAR(`sd`.`date`) AS `SALE_YEAR`,
`sd`.`cashierName` AS `CASHIER_NAME`,
`sd`.`itemDiscount` AS `ITEM_DISCOUNT`,
`sd`.`customerEmail` AS `CUSTOMER_EMAIL`,
CONCAT(`ar`.`forename`, " ", `ar`.`surname`) AS `ARTIST_NAME`,
`ar`.`bandName` AS `BAND_NAME`,
`ge`.`genreName` AS `GENRE`,
`pr`.`name` AS `PRODUCT_NAME`,
`pr`.`releaseDate` AS `PRODUCT_RELEASE_DATE`,
`pr`.`price` AS `PRODUCT_PRICE`,
`st`.`storeName` AS `STORE_NAME`,
`st`.`city` AS `STORE_CITY` 
from `salesdata` `sd` 
left join `store` `st` on `sd`.`storeId` = `st`.`storeId` 
left join `product` `pr` on `sd`.`itemId` = `pr`.`productId` 
left join `artist` `ar` on `ar`.`artistId` = `pr`.`artistId`
left join `genre` `ge` on `ge`.`genreId` = `pr`.`genreId`


    