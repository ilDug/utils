/**
 * 
 * @param length genera una stringa casuale di lunghezza assegnata
 */
export const randomCode = (length: number): string => {
    if (!length || length <= 0) throw new Error('la lunghezza deve essere uguale o maggiore di  0');

    /** numero di volte per cui si deve ripetere l'operazione di generazione  */
    const times: number = Math.floor((length - 1) / 8) + 1;
    let str: string = "";

    for (let i = 0; i < times; i++) {
        str = str + Math.random().toString(36).substr(2, 8)
    }

    return str.substr(0, length);
}















