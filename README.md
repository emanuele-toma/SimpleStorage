# Struttura DB

## Tabelle

* inventario

| Nome          | Tipo          | Extra          |
|---------------|---------------|----------------|
| id            | INT           | AUTO_INCREMENT |
| personal_id   | INT           |                |
| nome_prodotto | TINYTEXT      |                |
| descrizione   | TINYTEXT      |                |
| categoria     | TINYTEXT      |                |
| quantita      | INT           |                |
| prezzo        | DECIMAL(10,0) |                |
| proprietario  | INT           |                |

* ordini

| Nome         | Tipo          | Extra          |
|--------------|---------------|----------------|
| id           | INT           | AUTO_INCREMENT |
| personal_id  | INT           |                |
| nome         | TINYTEXT      |                |
| cognome      | TINYTEXT      |                |
| id_prodotto  | INT           |                |
| quantita     | INT           |                |
| prezzo       | DECIMAL(10,0) |                |
| data_ordine  | DATE          |                |
| proprietario | INT           |                |

* utenti

| Nome            | Tipo     | Extra          |
|-----------------|----------|----------------|
| user_id         | int      | AUTO_INCREMENT |
| personal_id_inv | int      |                |
| personal_id_ord | int      |                |
| username        | tinytext |                |
| password        | text     |                |
| email           | tinytext |                |
| picture         | text     |                |
| role            | tinytext |                |

# Configurazione
Per modificare la configurazione cambiare le variabili nel file settings.php.public e rimuovere .public