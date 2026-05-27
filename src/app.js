"use strict";

document.addEventListener('DOMContentLoaded', () => {
    
    // =========================================================================
    // 1. STRONA GŁÓWNA: Dodawanie do koszyka
    // =========================================================================
    // Wybieramy przyciski dodawania na stronie głównej (zabezpieczone przed łapaniem buttonów z koszyka)
    const addButtons = document.querySelectorAll('.scrollItem button:not(.topBtn):not(.bottomBtn)');

    addButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            try {
                const productItem = event.target.closest('.scrollItem');
                if (!productItem) return;

                // Szukamy inputa po konkretnej klasie .productId dla pełnego bezpieczeństwa
                const inputEl = productItem.querySelector('.productId');
                if (!inputEl) {
                    console.warn("Pominięto element koszyka lub brak klasy .productId");
                    return; 
                }

                const productId = inputEl.value;
                const productImage = inputEl.getAttribute('data-image');
                
                const productName = productItem.querySelector('.topP').textContent.trim();
                const rawPrice = productItem.querySelector('.bottomP').textContent;
                const cleanPrice = rawPrice.replace('zł', '').replace(',', '.').trim();
                const productPrice = parseFloat(cleanPrice);

                if (isNaN(productPrice)) {
                    throw new Error(`Nie udało się sparsować ceny: "${rawPrice}"`);
                }

                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                const existingProduct = cart.find(item => item.id === productId);

                if (existingProduct) {
                    existingProduct.quantity += 1;
                } else {
                    cart.push({
                        id: productId,
                        name: productName,
                        price: productPrice,
                        image: productImage || 'produkty/placeholder.png',
                        quantity: 1
                    });
                }

                localStorage.setItem('cart', JSON.stringify(cart));
                console.log('Produkt dodany do localStorage:', cart);

                // Odświeżamy zawartość panelu bocznego bez przeładowania strony
                renderBasket();

            } catch (error) {
                console.error("Błąd podczas dodawania:", error.message);
            }
        });
    });

    // =========================================================================
    // 2. FUNKCJA RENDEROWANIA KOSZYKA (Identyczna struktura z Twojego HTML)
    // =========================================================================
    function renderBasket() {
        const basketContainer = document.querySelector('.basketScroll');
        if (!basketContainer) return;

        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        
        if (cart.length === 0) {
            basketContainer.innerHTML = '<p class="empty-cart-msg" style="padding: 20px; color: white; text-align: center; width: 100%;">Twój koszyk jest pusty!</p>';
            return;
        }

        basketContainer.innerHTML = '';

        cart.forEach(item => {
            const scrollItem = document.createElement('div');
            scrollItem.className = 'scrollItem';
            
            // BEZPIECZNIK: Jeśli zdjęcie to undefined, wstawiamy puste miejsce żeby nie wywaliło błędu
            const bezpieczneZdjecie = (item.image && item.image !== 'undefined') ? item.image : '';

            scrollItem.innerHTML = `
                <input type="hidden" class="productId" value="${item.id}" data-image="${bezpieczneZdjecie}">
                <img src="${bezpieczneZdjecie}" alt="artykuł">
                <div class="scrollItemsMainDiv">
                    <div class="itemFlexContainer">
                        <div class="itemInfo">
                            <p class="itemName">${item.name}</p>
                            <p class="itemDesc">${(item.price * item.quantity).toFixed(2)}zł</p>
                        </div>
                        <div class="adjustAmount">
                            <div class="topBtn">+</div>
                            <p>${item.quantity}</p>
                            <div class="bottomBtn">-</div>
                        </div>
                    </div>
                    <div class="detailsInputDiv">
                        <input type="text" placeholder="Wpisz szczegóły..." class="itemDetails">
                    </div>
                </div>
            `;

            basketContainer.appendChild(scrollItem);
        });
    }

    // Wywołanie funkcji na start po wczytaniu strony/docka
    renderBasket();

    // =========================================================================
    // 3. OBSŁUGA ZMIANY ILOŚCI (Kliknięcia wewnątrz koszyka)
    // =========================================================================
    const basketContainer = document.querySelector('.basketScroll');
    if (basketContainer) {
        basketContainer.addEventListener('click', (event) => {
            const target = event.target;
            
            // Sprawdzamy czy kliknięto w plus lub minus wewnątrz wygenerowanego koszyka
            if (!target.classList.contains('topBtn') && !target.classList.contains('bottomBtn')) return;

            const productItem = target.closest('.scrollItem');
            if (!productItem) return;

            // Ścisłe pobranie ID z ukrytego inputa o klasie .productId
            const inputEl = productItem.querySelector('.productId');
            if (!inputEl) return;
            
            const productId = inputEl.value;

            let currentCart = JSON.parse(localStorage.getItem('cart')) || [];
            const productIndex = currentCart.findIndex(item => item.id === productId);

            if (productIndex !== -1) {
                if (target.classList.contains('topBtn')) {
                    currentCart[productIndex].quantity += 1;
                } else if (target.classList.contains('bottomBtn')) {
                    if (currentCart[productIndex].quantity > 1) {
                        currentCart[productIndex].quantity -= 1;
                    } else {
                        // Jeśli ilość spada do 0, usuwamy całkowicie element z tablicy
                        currentCart.splice(productIndex, 1);
                    }
                }

                // Nadpisanie pamięci i aktualizacja widoku bez przeładowywania i zamykania docka
                localStorage.setItem('cart', JSON.stringify(currentCart));
                renderBasket();
            }
        });
    }

    // =========================================================================
    // 4. CZYSZCZENIE KOSZYKA PRZY WYLOGOWANIU
    // =========================================================================
    const logoutBtn = document.querySelector('a[href="profile.php?logout=1"]');
    
    if (logoutBtn) {
        logoutBtn.addEventListener('click', (event) => {
            // 1. Zatrzymujemy natychmiastowe przejście do linku
            event.preventDefault(); 
            
            // 2. Czyścimy koszyk
            localStorage.removeItem('cart');
            console.log('Koszyk został wyczyszczony. Wylogowywanie...');
            
            // 3. Dopiero teraz ręcznie wysyłamy użytkownika do wylogowania
            window.location.href = event.target.href;
        });
    }
});

