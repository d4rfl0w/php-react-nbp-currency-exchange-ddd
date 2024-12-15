import React, { useState, useEffect } from 'react';
import { useHistory, useLocation } from 'react-router-dom';
import ExchangeRates from './ExchangeRates';

function useQuery() {
    return new URLSearchParams(useLocation().search);
}

const App = () => {
    const history = useHistory();
    const query = useQuery();
    const today = new Date().toISOString().split('T')[0];
    const initialDate = query.get('date') || today;
    const [date, setDate] = useState(initialDate);
    const [todayDate, setTodayDate] = useState(today);
    const [submittedDate, setSubmittedDate] = useState(initialDate);

    const handleSubmit = (event) => {
        event.preventDefault();
        setSubmittedDate(date);
        history.push(`/exchange-rates?date=${date}`);
    };

    useEffect(() => {
        const queryDate = query.get('date') || new Date().toISOString().split('T')[0];
        setSubmittedDate(queryDate);
    }, [query]);

    return (
        <div style={styles.container}>
            <form onSubmit={handleSubmit} style={styles.form}>
                <label>
                    Choose Date:
                    <input
                        type="date"
                        value={date}
                        onChange={e => setDate(e.target.value)}
                        min="2023-01-01"
                        max={new Date().toISOString().split('T')[0]}
                        style={styles.input}
                    />
                </label>
                <button type="submit" style={styles.button}>Submit</button>
            </form>
            <ExchangeRates date={submittedDate}/>
            {submittedDate !== todayDate && <ExchangeRates date={todayDate}/>}
        </div>
    );
};

const styles = {
    container: {
        width: '60%',
        margin: '0 auto',
        paddingTop: '20px',
        textAlign: 'center'
    },
    form: {
        marginBottom: '20px'
    },
    input: {
        marginLeft: '10px',
        padding: '5px',
        fontSize: '16px'
    },
    button: {
        marginLeft: '10px',
        padding: '5px 10px',
        fontSize: '16px',
        backgroundColor: '#4CAF50',
        color: 'white',
        border: 'none',
        cursor: 'pointer'
    }
};

export default App;
