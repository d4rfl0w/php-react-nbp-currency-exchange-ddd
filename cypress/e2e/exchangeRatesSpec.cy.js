describe('Exchange Rates Application', () => {
    it('should display exchange rates for selected date', () => {
        cy.visit('/exchange-rates');

        // Set the date and submit the form
        cy.get('input[type="date"]').clear().type('2024-10-01');
        cy.get('button[type="submit"]').click();

        // Verify the exchange rates table
        cy.contains('Exchange Rates for 2024-10-01');
        cy.get('table').should('be.visible');
        cy.get('table tbody tr').should('have.length', 2); // Assuming two currencies

        // Verify specific data in the table
        cy.contains('td', 'EUR').should('be.visible');
        cy.contains('td', 'USD').should('be.visible');
    });

    it('should display error message for invalid date', () => {
        cy.visit('/exchange-rates');

        // Set an invalid date (future date) and submit the form
        cy.get('input[type="date"]').clear().type('3000-01-01');
        cy.get('button[type="submit"]').click();

        // Verify error message
        cy.contains('Error: The date cannot be in the future.').should('be.visible');
    });
});
