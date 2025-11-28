// Gestionnaire d'événements global
import { EVENTS } from './constants.js';

class EventManager {
    constructor() {
        this.events = new Map();
    }

    // Émettre un événement personnalisé
    emit(eventName, data = null) {
        const event = new CustomEvent(eventName, { detail: data });
        document.dispatchEvent(event);
    }

    // Écouter un événement personnalisé
    on(eventName, callback) {
        document.addEventListener(eventName, callback);
    }

    // Arrêter d'écouter un événement
    off(eventName, callback) {
        document.removeEventListener(eventName, callback);
    }

    // Émettre un événement global
    emitGlobal(eventType, data = null) {
        this.emit(EVENTS[eventType], data);
    }

    // Déclencher un loading state
    setLoading(state = true) {
        this.emit('app:loading', { loading: state });
    }
}

export default new EventManager();