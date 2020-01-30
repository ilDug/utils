var OrderStatus = /** @class */ (function () {
    function OrderStatus(s) {
        this.status = {
            /** VERO durante lo shopping, e l'ordine non è ancora stato confermato. FALSO quando l'ordine è confermato o pagato */
            open: 0,
            /** indica se è avvenuto il pagamento */
            payed: 0,
            /** indica se è stata emessa fattura ufficiale */
            invoiced: 0,
            /** indica se l'ordine è passato dal checkout ed è stato confermato dall'utente (dopo il pagamento) */
            confirmed: 0,
            /** indica che l'ordine è in lavorazione */
            working: 0,
            /** VERO quando l'ordine è stato elaborato (stampa + confezionamento) */
            completed: 0,
            /** VERO quando l'ordine viene spedito */
            shipped: 0,
            /** VERO quando l'ordine risultaconsegnato al cliente */
            delivered: 0,
            /** quando è stato consengato e sono passati 30gg enza reclami */
            accepted: 0,
            /** quando il cliente lamenta una non conformità */
            rejected: 0,
            /**  VERO quando viene consegnato in sede il reso  */
            returned: 0,
            /** indica se il pagamento è stato stornato */
            refounded: 0,
            /** l'ordine diventa chiuso nel momento cui è stato consegnato e accettato dopo 30 gg */
            closed: 0,
        };
        /** assegna le proprietà */
        Object.assign(this, s);
    }
    return OrderStatus;
}());
export { OrderStatus };
