UPDATE wharehouse SET
    status = :status, 
    available = :available, 
    dateOut = :dateOut
WHERE
    articleId = :articleId