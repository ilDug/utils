INSERT INTO warehouse ( 
    productId,
    sku,
    batch,
    item,
    dateExpiry,
    status
)

VALUES (
   :productId,
   :sku,
   :batch,
   :item,
   :dateExpiry,
   :status
);