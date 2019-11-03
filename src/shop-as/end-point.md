# ACCOUNT
permette di loggarsi o registrarsi 

 
## /account/login
POST:
    @body {
        email: string,
        password : string
    }

    @response token: string || false
    
## /account/register
POST:
    @body {
        user:{
            email: string,
            password : string
        }
    }

    @response 
        token: string || false
        in più manda una mail con la chiave di attivazione

## /account/resend-activation/:email
GET:
    @param :email = email usata per registrarsi
    @response boolean or exception
    

## /account/activate/:key
GET:
    @param :key = chiave di attivazione
    @response boolean or exception
    

## /account/password/recover
POST:
    @body {
        email: string
    }

    @response  boolean (manda la mail)

## /account/restore/init
POST:
    @body {
        key: string
    }

    @response  uid: string

## /account/restore/set
POST:
    @body {
        key: string, 
        password: string
    }

    @response  boolean