describe('Exchange Rate Service', () => {
    it('should fetch exchange rates from API', () => {
        cy.request('/api/exchange-rates/2024-10-01').then((response) => {
            expect(response.status).to.eq(200);
            expect(response.body).to.be.an('array');
            expect(response.body[0]).to.have.property('currency', 'EUR');
            expect(response.body[0]).to.have.property('nbpRate', 4.5);
            expect(response.body[0]).to.have.property('buyRate', 4.45);
            expect(response.body[0]).to.have.property('sellRate', 4.57);
        });
    });
});
