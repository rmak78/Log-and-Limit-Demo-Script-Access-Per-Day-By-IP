 /*
 #   sql statement for creating the logging table
 #   
 */
   create table visitorlog  (    visitID int(10) auto_increment primary key,
        visitIP varchar(15),
        visitURI varchar(255),
        visitReferer varchar(255),
        visitDate DATETIME,
        visitAgent varchar(255)
    ) 
