import React, { useState, useEffect } from 'react';

function ExchangeRates({ date }) {
    const [rates, setRates] = useState([]);
    const [error, setError] = useState(null);

    useEffect(() => {
        let isMounted = true;

        const fetchExchangeRates = async (selectedDate) => {
            try {
                const response = await fetch(`/api/exchange-rates/${selectedDate}`);
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.error || response.statusText);
                }
                if (isMounted) {
                    setError(null);
                    setRates(data);
                }
            } catch (error) {
                if (isMounted) {
                    setError(error.message);
                    setRates([]);
                }
            }
        };

        fetchExchangeRates(date);

        return () => {
            isMounted = false;
        };
    }, [date]);

    return (
        <div style={styles.container}>
            <h2 style={styles.dateText}>Exchange Rates for {date}</h2>
            {error && <p style={{ color: 'red' }}>Error: {error}</p>}
            {!error && (
            <table style={styles.table}>
                <thead>
                <tr>
                    <th style={styles.th}>Code</th>
                    <th style={styles.th}>Currency</th>
                    <th style={styles.th}>NBP Rate</th>
                    <th style={styles.th}>Buy Rate</th>
                    <th style={styles.th}>Sell Rate</th>
                </tr>
                </thead>
                <tbody>
                {rates.map((rate, index) => (
                    <tr key={rate.currency} style={{ backgroundColor: index % 2 === 0 ? '#f9f9f9' : '#f1f1f1' }}>
                        <td style={styles.td}>{rate.currency}</td>
                        <td style={styles.td}>{rate.name}</td>
                        <td style={styles.td}>{rate.nbpRate.toFixed(4)}</td>
                        <td style={styles.td}>{rate.buyRate ? rate.buyRate.toFixed(4) : '-'}</td>
                        <td style={styles.td}>{rate.sellRate.toFixed(4)}</td>
                    </tr>
                ))}
                </tbody>
            </table>
            )}
        </div>
    );
}

const styles = {
    container: {
        backgroundColor: 'white',
        padding: '20px',
        borderRadius: '8px',
        boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
    },
    dateText: {
        fontSize: '1.2em',
        color: '#333',
        marginBottom: '10px',
    },
    table: {
        width: '100%',
        borderCollapse: 'collapse',
        marginTop: '20px',
        marginBottom: '20px',
    },
    th: {
        backgroundColor: '#EFEFEF',
        padding: '12px',
        textAlign: 'left',
        fontWeight: 'bold',
        borderBottom: '2px solid #FA6501',
    },
    td: {
        padding: '12px',
        textAlign: 'left',
        borderBottom: '1px solid #ddd',
    },
};

export default ExchangeRates;
