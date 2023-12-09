document.addEventListener('DOMContentLoaded', function () {
    const priceRange = document.querySelector('#priceRange');
    const currentPrice = document.querySelector('#currentPrice');

    currentPrice.textContent = 'Recherche actuelle : ' + priceRange.value + '€';

    priceRange.addEventListener('input', function () {
        currentPrice.textContent = 'Recherche actuelle : ' + this.value + '€';
    });
});