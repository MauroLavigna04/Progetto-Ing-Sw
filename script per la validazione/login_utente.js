import http from 'k6/http';

// test login singolo per visualizzare il json di risposta
export default function () {
    const url = 'http://192.168.1.237/loginUtente.php';
    const payload = { email: 'mariopuntorossi@gimeil.colm', password: 'mariorossi' };

    const res = http.post(url, payload);

    console.log('Richiesta inviata!');

    console.log('Status code:', res.status);

    // Stampa TUTTO quello che il server ti ha risposto (JSON)
    console.log('Risposta del server:', res.body);
}