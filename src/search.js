"use strict";

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const searchResultsSection = document.getElementById('searchResultsSection');
    const resultsContainer = document.querySelector('.resultsContainer');
    const originalSections = document.getElementById('originalSections');

    if (!searchInput || !searchResultsSection || !resultsContainer || !originalSections) {
        console.warn("Wyszukiwarka: Nie odnaleziono wymaganych elementów HTML.");
        return;
    }

    // Pobieramy unikalną listę wszystkich produktów dostępnych na stronie
    const allItems = Array.from(originalSections.querySelectorAll('.scrollItem'));
    const productCache = [];
    const seenIds = new Set();

    allItems.forEach(item => {
        const idInput = item.querySelector('.productId');
        if (!idInput) return;
        
        const id = idInput.value;
        if (!seenIds.has(id)) {
            seenIds.add(id);
            productCache.push(item);
        }
    });

    // Funkcja filtrująca
    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.trim().toLowerCase();

        if (query === "") {
            searchResultsSection.style.display = "none";
            resultsContainer.innerHTML = "";
            originalSections.style.display = "block";
            return;
        }

        const matchedProducts = productCache.filter(item => {
            const nameEl = item.querySelector('.topP');
            const name = nameEl ? nameEl.textContent.toLowerCase() : "";
            return name.includes(query);
        });

        originalSections.style.display = "none";
        resultsContainer.innerHTML = "";
        searchResultsSection.style.display = "block";

        if (matchedProducts.length === 0) {
            resultsContainer.innerHTML = '<p class="text-muted w-100 text-center py-4" style="color: #aaa !important;">Nie znaleźliśmy niczego o tej nazwie...</p>';
            return;
        }

        matchedProducts.forEach(product => {
            const clonedProduct = product.cloneNode(true);
            const addBtn = clonedProduct.querySelector('button');
            if (addBtn) {
                addBtn.addEventListener('click', () => {
                    const originalBtn = product.querySelector('button');
                    if (originalBtn) {
                        originalBtn.click();
                    }
                });
            }
            resultsContainer.appendChild(clonedProduct);
        });
    });

    // =========================================================================
    // NOWOŚĆ: Automatyczny focus po kliknięciu lupy z docking panelu
    // =========================================================================
    function checkSearchHash() {
        if (window.location.hash === '#search') {
            // Przewija płynnie ekran do wyszukiwarki
            searchInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            // Aktywuje pole tekstowe (ustawia kursor)
            searchInput.focus();
        }
    }

    // Wywołaj od razu po załadowaniu strony
    checkSearchHash();

    // Dodatkowo: obsłuż sytuację, kiedy użytkownik jest już na main.php i ponownie kliknie lupę
    window.addEventListener('hashchange', checkSearchHash);
});