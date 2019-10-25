# Generazione delle chiavi e dei certificati

per generare le chiavi ed i certificati da inserire in questa cartelal usare i comandi UNIX:

## generare una chiave privata

openssl genrsa -out as.key.pem 2048


## generare un certificato autofirmato

openssl req -new -key as.key.pem -out as.csr.pem