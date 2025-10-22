import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { DayPicker } from "react-day-picker";
import { es } from "react-day-picker/locale";


export default function Schedule({ data }) {
    const attributes = JSON.parse(data) || {};
    const [date, setDate] = useState(new Date());
    const [slots, setSlots] = useState([]);
    const [selectedTime, setSelectedTime] = useState('');
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [message, setMessage] = useState(null);
    const [loading, setLoading] = useState(false);

    console.log(date);
    console.log(data);

    const fetchAvailable = (date) => {
        setLoading(true);
        apiFetch({
            path: `/wp-buko/v1/available?date=${encodeURIComponent(date)}`,
        }).then((res) => {
            setSlots(res.slots || []);
            setLoading(false);
        }).catch((err) => {
            setMessage({ type: 'error', text: attributes.errorMessage || 'Error fetching available slots' });
            setLoading(false);
        });
    }

    useEffect(() => {
        const datePicker = document.getElementById('wp-buko-date-picker');
        if (datePicker) {
            datePicker.addEventListener('change', (e) => {
                const selectedDate = e.target.value;
                setDate(selectedDate);
                fetchAvailable(selectedDate);
            });
        }
    }, []);

    const book = () => {
        setMessage(null);
        setLoading(true);
        apiFetch({
            path: '/wp-buko/v1/book',
            method: 'POST',
            data: { name, email, date, time: selectedTime }
        }).then((res) => {
            setMessage({ type: 'success', text: attributes.successMessage || 'Appointment booked successfully!' });
            // clear form
            setSelectedTime(''); setName(''); setEmail('');
            setLoading(false);
        }).catch((err) => {
            const t = err?.message || err?.data?.message || attributes.errorMessage || 'Error booking appointment';
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
                    onSelect={setDate}
                />
            </div>
            <div className="wp-buko-schedule__content">
                <div className="wp-buko-schedule__time-section">
                    <h4 className="wp-buko-schedule__time-label">{attributes.timeLabel || 'Horarios disponibles'}</h4>
                    <div className="wp-buko-schedule__slots-grid">
                        {loading && <div className="wp-buko-schedule__loading">Loading...</div>}
                        {slots.length === 0 && !loading && (<div className="wp-buko-schedule__placeholder">{attributes.noSlotsMessage || 'Select a date'}</div>)}
                        {slots.map(s => (
                            <button
                                key={s.time}
                                className={`wp-buko-slot ${!s.available ? 'wp-buko-slot--disabled' : ''} ${selectedTime === s.time ? 'wp-buko-slot--active' : ''}`}
                                disabled={!s.available}
                                onClick={() => setSelectedTime(s.time)}
                            >
                                {s.time}
                            </button>
                        ))}
                    </div>
                </div>

                {selectedTime && (
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
                                placeholder={attributes.emailPlaceholder || 'Correo electrónico'}
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                            />
                            <button
                                className="wp-buko-button wp-buko-button--primary"
                                onClick={book}
                                disabled={loading}
                            >
                                {loading ? 'Booking...' : (attributes.buttonText || 'Agendar cita')}
                            </button>
                        </div>
                    </div>
                )}
                {message && <div className={`wp-buko-message ${message.type === 'success' ? 'wp-buko-message--success' : 'wp-buko-message--error'}`}>{message.text}</div>}
            </div>
        </>
    );
}
