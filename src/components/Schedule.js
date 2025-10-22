import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { DayPicker } from "react-day-picker";
import { es, is } from "react-day-picker/locale";


export default function Schedule({ data }) {
    const attributes = JSON.parse(data) || {};
    const [date, setDate] = useState(new Date());
    const [slots, setSlots] = useState([]);
    const [selectedTime, setSelectedTime] = useState('');
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [message, setMessage] = useState(null);
    const [loading, setLoading] = useState(false);


    const fetchAvailable = (date) => {
        setLoading(true);
        apiFetch({
            path: `/wp_buko/v1/available?date=${encodeURIComponent(date)}`,
        }).then((res) => {
            setSlots(res.slots || []);
            setLoading(false);
        }).catch((err) => {
            setMessage({ type: 'error', text: attributes.errorMessage || 'Error al obtener horarios disponibles' });
            setLoading(false);
        });
    }

    useEffect(() => {
        // Fetch available slots for initial date
        if (date) {
            fetchAvailable(date.toISOString().split('T')[0]);
        }
    }, []);

    const book = () => {
        setMessage(null);
        setLoading(true);
        apiFetch({
            path: '/wp_buko/v1/book',
            method: 'POST',
            data: { name, email, date, time: selectedTime }
        }).then((res) => {
            setMessage({ type: 'success', text: attributes.successMessage || 'Cita agendada con éxito!' });
            // clear form
            setSelectedTime(''); setName(''); setEmail('');
            // refetch available slots for the current date
            fetchAvailable(date.toISOString().split('T')[0]);
            setLoading(false);
        }).catch((err) => {
            const t = err?.message || err?.data?.message || attributes.errorMessage || 'Error al agendar la cita';
            setMessage({ type: 'error', text: t });
            setLoading(false);
        });
    }

    return (
        <>
            <div className="wp-buko-schedule__calendar">
                <DayPicker locale={es}
                    animate
                    mode="single"
                    selected={date}
                    onSelect={(selectedDate) => {
                        setDate(selectedDate);
                        if (selectedDate) {
                            fetchAvailable(selectedDate.toISOString().split('T')[0]);
                        }
                    }}
                />
            </div>
            <div className="wp-buko-schedule__content">
                <div className="wp-buko-schedule__time-section">
                    <h4 className="wp-buko-schedule__time-label">{attributes.timeLabel || 'Horarios disponibles'}</h4>
                    <div className="wp-buko-schedule__slots-grid">
                        {loading && <div className="wp-buko-schedule__loading">Cargando...</div>}
                        {slots.length === 0 && !loading && (<div className="wp-buko-schedule__placeholder">{attributes.noSlotsMessage || 'Selecciona una fecha'}</div>)}
                        {!loading && slots.map(slot => {
                            const is_available = slot.available;
                            return (
                                <button
                                    key={slot.time}
                                    className={`wp-buko-slot ${is_available ? '' : 'wp-buko-slot--disabled'} ${selectedTime === slot.time ? 'wp-buko-slot--active' : ''}`}
                                    disabled={!is_available}
                                    onClick={() => setSelectedTime(slot.time)}
                                >
                                    {slot.time}
                                </button>
                            );
                        })}
                    </div>
                </div>

                {!loading && selectedTime && (
                    <div className="wp-buko-schedule__form-section">
                        <div className="wp-buko-schedule__form">
                            <input
                                className="wp-buko-input"
                                placeholder={attributes.namePlaceholder || 'Nombre'}
                                value={name}
                                onChange={(e) => setName(e.target.value)}
                            />
                            <input
                                className="wp-buko-input"
                                placeholder={attributes.emailPlaceholder || 'Correo electrónico'}
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                            />
                            <button
                                className="wp-buko-button wp-buko-button--primary"
                                onClick={book}
                                disabled={loading}
                            >
                                {loading ? 'Agendando...' : (attributes.buttonText || 'Agendar Cita')}
                            </button>
                        </div>
                    </div>
                )}
                {message && <div className={`wp-buko-message ${message.type === 'success' ? 'wp-buko-message--success' : 'wp-buko-message--error'}`}>{message.text}</div>}
            </div>
        </>
    );
}
