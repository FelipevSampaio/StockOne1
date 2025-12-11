// Script de Debug para o Botão de Adicionar ao Carrinho
// Cole este código no Console do Navegador (F12 > Console)

console.log('=== DEBUG CARRINHO ===');

// 1. Verificar se menuFiltersInstance existe
console.log('1. menuFiltersInstance:', window.menuFiltersInstance);
console.log('   - Tipo:', typeof window.menuFiltersInstance);
console.log('   - Tem addToCart?', window.menuFiltersInstance && typeof window.menuFiltersInstance.addToCart === 'function');

// 2. Verificar formulários de adicionar
const addToCartForms = document.querySelectorAll('.add-to-cart-form, [data-item-form]');
console.log('2. Formulários encontrados:', addToCartForms.length);
addToCartForms.forEach((form, index) => {
    console.log(`   Form ${index + 1}:`, {
        action: form.getAttribute('action'),
        itemId: form.querySelector('input[name="cardapio_item_id"]')?.value,
        hasToken: !!form.querySelector('input[name="_token"]'),
        classes: form.className
    });
});

// 3. Verificar event listeners
console.log('3. Event listeners no document:', getEventListeners ? getEventListeners(document) : 'Não disponível (Chrome DevTools)');

// 4. Testar clique manual
console.log('4. Para testar manualmente, execute:');
console.log('   testAddToCart(ITEM_ID) - substitua ITEM_ID pelo ID do item');

window.testAddToCart = function(itemId) {
    const form = document.querySelector(`[data-item-form="${itemId}"]`) ||
                 document.querySelector(`input[name="cardapio_item_id"][value="${itemId}"]`)?.closest('form');

    if (!form) {
        console.error('Formulário não encontrado para item:', itemId);
        return;
    }

    console.log('Testando adicionar item:', itemId);
    console.log('Formulário:', form);

    const menuFilters = window.menuFiltersInstance;
    if (menuFilters && typeof menuFilters.addToCart === 'function') {
        console.log('Chamando menuFilters.addToCart...');
        const syntheticEvent = {
            preventDefault: () => {},
            target: form,
            stopPropagation: () => {}
        };
        try {
            menuFilters.addToCart(syntheticEvent, parseInt(itemId));
            console.log('✅ addToCart chamado com sucesso');
        } catch (error) {
            console.error('❌ Erro ao chamar addToCart:', error);
        }
    } else {
        console.error('❌ menuFilters.addToCart não disponível');
        console.log('Tentando requisição direta...');

        const button = form.querySelector('button[type="submit"]');
        const originalText = button ? button.innerHTML : '';

        if (button) {
            button.disabled = true;
            button.innerHTML = 'Carregando...';
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                         form.querySelector('input[name="_token"]')?.value;

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Resposta:', response.status, response.statusText);
            return response.json();
        })
        .then(data => {
            console.log('Dados recebidos:', data);
            if (button) {
                button.disabled = false;
                button.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Erro na requisição:', error);
            if (button) {
                button.disabled = false;
                button.innerHTML = originalText;
            }
        });
    }
};

// 5. Monitorar eventos de submit
console.log('5. Monitorando eventos de submit...');
document.addEventListener('submit', function(e) {
    const form = e.target;
    if (form && form.classList.contains('add-to-cart-form')) {
        console.log('📝 Submit detectado no formulário:', {
            action: form.getAttribute('action'),
            itemId: form.querySelector('input[name="cardapio_item_id"]')?.value,
            defaultPrevented: e.defaultPrevented
        });
    }
}, true); // Use capture phase

console.log('=== FIM DEBUG ===');
console.log('Execute testAddToCart(ITEM_ID) para testar manualmente');
