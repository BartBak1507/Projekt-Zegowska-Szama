"use strict";

document.addEventListener('DOMContentLoaded', () => {
    
    const SECRET_PROMO = "SZAMA2026"; // Twój ukryty kod rabatowy

    // =========================================================================
    // 1. STRONA GŁÓWNA: Dodawanie do koszyka
    // =========================================================================
    const addButtons = document.querySelectorAll('.scrollItem button:not(.topBtn):not(.bottomBtn)');

    addButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            try {
                const productItem = event.target.closest('.scrollItem');
                if (!productItem) return;

                const inputEl = productItem.querySelector('.productId');
                if (!inputEl) {
                    console.warn("Pominięto element koszyka lub brak klasy .productId");
                    return; 
                }

                const productId = inputEl.value;
                const productImage = inputEl.getAttribute('data-image');
                
                const productName = productItem.querySelector('.topP').textContent.trim();

                // Pobieranie ceny z zabezpieczeniem
                const bottomP = productItem.querySelector('.bottomP');
                const promoPriceSpan = bottomP.querySelector('.priceValue');

                // Jeśli produkt ma klasę priceValue (jest w promocji), bierzemy cenę z niej. 
                // Jeśli nie ma (zwykły produkt), bierzemy cały tekst z bottomP.
                const rawPrice = promoPriceSpan ? promoPriceSpan.textContent : bottomP.textContent;

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
                renderBasket();

            } catch (error) {
                console.error("Błąd podczas dodawania:", error.message);
            }
        });
    });

    // =========================================================================
    // 2. FUNKCJA RENDEROWANIA KOSZYKA I LIVE-SUMOWANIA (ZNIŻKA PROCENTOWA)
    // =========================================================================
    function updateTotalPrice() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        let total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        
        // Obliczanie zniżki procentowej (5%) na żywo
        const promoInput = document.getElementById('promoInput');
        if (promoInput && promoInput.value.trim().toUpperCase() === SECRET_PROMO.toUpperCase() && total > 0) {
            total = total * 0.95; // Klient płaci 95% ceny początkowej
        }
        
        const priceDisplay = document.getElementById('totalPrice');
        if (priceDisplay) {
            priceDisplay.textContent = total.toFixed(2);
        }

        // Przeliczenie na Szamsy i obsługa przycisku
        const totalSzamsy = Math.floor(total * 75); // Zaokrąglenie w dół
        const szamsyDisplay = document.getElementById('totalSzamsyPrice');
        if (szamsyDisplay) {
            szamsyDisplay.textContent = totalSzamsy;
        }

        const paySzamsyBtn = document.getElementById('paySzamsyBtn');
        if (paySzamsyBtn) {
            const isLoggedIn = document.body.getAttribute('data-logged-in') === 'true';
            const userSzamsy = parseInt(document.body.getAttribute('data-user-szamsy')) || 0;

            if (isLoggedIn && userSzamsy >= totalSzamsy && total > 0) {
                paySzamsyBtn.disabled = false;
                paySzamsyBtn.style.opacity = "1";
                paySzamsyBtn.style.cursor = "pointer";
            } else {
                paySzamsyBtn.disabled = true;
                paySzamsyBtn.style.opacity = "0.5";
                paySzamsyBtn.style.cursor = "not-allowed";
            }
        }
    }

    function renderBasket() {
        const basketContainer = document.querySelector('.basketScroll');
        if (!basketContainer) return;

        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        
        if (cart.length === 0) {
            basketContainer.innerHTML = '<p class="empty-cart-msg" style="padding: 20px; color: white; text-align: center; width: 100%;">Twój koszyk jest pusty!</p>';
            const priceDisplay = document.getElementById('totalPrice');
            if (priceDisplay) priceDisplay.textContent = '0.00';
            const szamsyDisplay = document.getElementById('totalSzamsyPrice');
            if (szamsyDisplay) szamsyDisplay.textContent = '0';
            return;
        }

        basketContainer.innerHTML = '';

        cart.forEach(item => {
            const scrollItem = document.createElement('div');
            scrollItem.className = 'scrollItem';
            
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

        updateTotalPrice();
    }

    renderBasket();

    // Reagowanie na wpisywanie kodu rabatowego na żywo
    const promoInput = document.getElementById('promoInput');
    if (promoInput) {
        promoInput.addEventListener('input', updateTotalPrice);
    }

    // =========================================================================
    // 3. OBSŁUGA ZMIANY ILOŚCI (Kliknięcia wewnątrz koszyka)
    // =========================================================================
    const basketContainer = document.querySelector('.basketScroll');
    if (basketContainer) {
        basketContainer.addEventListener('click', (event) => {
            const target = event.target;
            
            if (!target.classList.contains('topBtn') && !target.classList.contains('bottomBtn')) return;

            const productItem = target.closest('.scrollItem');
            if (!productItem) return;

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
                        currentCart.splice(productIndex, 1);
                    }
                }

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
            event.preventDefault(); 
            localStorage.removeItem('cart');
            window.location.href = event.target.href;
        });
    }

    // =========================================================================
    // 5. OBSŁUGA WYSYŁKI ZAMÓWIENIA (ZABEZPIECZENIE LOGOWANIA I WYSYŁKA 5%)
    // =========================================================================
    const orderForm = document.getElementById('orderForm');

    if (orderForm) {
        orderForm.addEventListener('submit', (e) => {
            
            // BEZPIECZNIK LOGOWANIA: Pobieramy status bezpośrednio z atrybutu body
            const isLoggedIn = document.body.getAttribute('data-logged-in') === 'true';

            if (!isLoggedIn) {
                e.preventDefault(); 
                window.location.href = 'login.php';
                return;
            }

            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            if (cart.length === 0) {
                e.preventDefault();
                alert('Twój koszyk jest pusty!');
                return;
            }

            // Sprawdzamy, który przycisk wywołał wysyłkę formularza
            const paymentMethodInput = document.getElementById('paymentMethodInput');
            if (e.submitter && e.submitter.id === 'paySzamsyBtn') {
                paymentMethodInput.value = 'szamsy'; 
            } else {
                paymentMethodInput.value = 'money';  
            }

            const basketItems = document.querySelectorAll('.scrollItem');
            
            // 1. Zbieramy wpisane szczegóły z pól tekstowych na stronie
            basketItems.forEach((itemEl) => {
                const id = itemEl.querySelector('.productId')?.value;
                const details = itemEl.querySelector('.itemDetails')?.value;
                
                const product = cart.find(p => p.id === id);
                if (product) {
                    product.details = details || ""; 
                }
            });

            // 2. Obliczamy wartość zniżki 5% dla backendu PHP
            if (promoInput && promoInput.value.trim().toUpperCase() === SECRET_PROMO.toUpperCase()) {
                const totalBeforeDiscount = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                const discountValue = -(totalBeforeDiscount * 0.05); // Wartość 5% kwoty koszyka na minusie
                
                cart.push({ 
                    id: 'DISCOUNT', 
                    name: 'Zastosowano Rabat SZAMA2026 (5%)', 
                    price: parseFloat(discountValue.toFixed(2)), 
                    quantity: 1, 
                    details: '' 
                });
            }

            // 3. Przekazanie gotowego pakietu danych do inputa
            document.getElementById('cartDataInput').value = JSON.stringify(cart);
        });
    }
});