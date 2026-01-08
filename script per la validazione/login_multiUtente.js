import http from 'k6/http';
import { check, sleep } from 'k6';

// 1. CONFIGURAZIONE DEL TEST
export const options = {
  // Fase 1: Sali a 20 utenti in 30 secondi (Ramp-up)
  // Fase 2: Resta a 20 utenti per 1 minuto (Stress costante)
  // Fase 3: Scendi a 0 utenti in 10 secondi (Ramp-down)
  stages: [
    { duration: '30s', target: 20 },
    { duration: '1m', target: 20 },
    { duration: '10s', target: 0 },
  ],
  
  // Opzionale: Se il 95% delle richieste supera i 600ms, fallisci il test
  thresholds: {
    http_req_duration: ['p(95)<600'], 
  },
};

// 2. COSA FA OGNI UTENTE (Virtual User)
export default function () {

    const url = 'http://192.168.1.237/loginUtente.php';
    const payload = { email: 'mariopuntorossi@gimeil.colm', password: 'mariorossi' };

    // Esegui la richiesta POST
    const res = http.post(url, payload);

    // 3. CONTROLLI (Checks)
    // Verifica che il server abbia risposto 200 OK, che il login sia riuscito e che il nome restituito sia corretto
    check(res, {
    'status is 200': (r) => r.status === 200,
    'login riuscito': (r) => r.json('success') === true,
    'nome corretto': (r) => r.json('nome') !== undefined,
    });

    // Aspetta 1 secondo prima di rifare il giro (per non essere un attacco DDoS puro)
    sleep(1);
}