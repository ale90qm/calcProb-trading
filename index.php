<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora de Probabilidad de Éxito</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        :root {
            --positive-color: #28a745; --negative-color: #dc3545; --border-color: #ddd;
            --background-color: #f4f4f4; --container-background: white; --text-primary: #333;
            --text-secondary: #666; --tab-color: #f1f1f1; --tab-active-color: #007bff;
            --modal-background: white; --input-background: white; --hover-color: #f5f5f5;
        }
        .dark-mode {
            --border-color: #444; --background-color: #121212; --container-background: #1e1e1e;
            --text-primary: #e0e0e0; --text-secondary: #a0a0a0; --tab-color: #2c2c2c;
            --modal-background: #252525; --input-background: #333; --hover-color: #333;
        }
        body { font-family: system-ui, sans-serif; display: flex; justify-content: center; align-items: flex-start; min-height: 100vh; background-color: var(--background-color); color: var(--text-primary); margin: 0; padding: 30px; transition: background-color 0.3s; }
        .container { background-color: var(--container-background); padding: 30px 40px; border-radius: 12px; box-shadow: 0 6px 12px rgba(0,0,0,0.1); max-width: 1100px; width: 100%; position: relative; transition: background-color 0.3s; }
        .top-right-controls { display: flex; align-items: center; gap: 15px; position: absolute; top: 20px; right: 25px; z-index: 101; }
        #menu-btn, #theme-toggle-btn { font-size: 1.5em; background: none; border: none; cursor: pointer; padding: 5px; color: var(--text-secondary); display: flex; align-items: center; justify-content: center; transition: color 0.3s; }
        .dark-mode .sun-icon { display: block; } .dark-mode .moon-icon { display: none; }
        .sun-icon { display: none; } .moon-icon { display: block; }
        #main-menu { display: none; position: absolute; top: 60px; right: 20px; background-color: var(--modal-background); border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border: 1px solid var(--border-color); z-index: 100; overflow: hidden; }
        #main-menu button { display: block; width: 100%; padding: 12px 20px; background: none; border: none; text-align: left; cursor: pointer; font-size: 1em; border-bottom: 1px solid var(--border-color); color: var(--text-primary); }
        #main-menu button:last-child { border-bottom: none; }
        #main-menu button:hover { background-color: var(--hover-color); }
        h1 { text-align: center; color: var(--text-primary); margin: 20px 0 10px; padding: 0 60px; }
        .note { font-size: 0.9em; color: var(--text-secondary); text-align: center; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid var(--border-color); }
        #memo-display-container { display: none; text-align: center; padding: 15px; margin: 0 auto 25px auto; background-color: var(--tab-color); border-left: 4px solid var(--tab-active-color); max-width: 80%; }
        #memo-display-text { margin: 0; font-style: italic; color: var(--text-secondary); font-size: 1.1em; transition: opacity 0.5s ease-in-out; }
        .tab-container { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: -1px; }
        .tab { padding: 10px 15px; cursor: pointer; border: 1px solid var(--border-color); border-bottom: none; border-radius: 8px 8px 0 0; background-color: var(--tab-color); display: flex; align-items: center; gap: 10px; }
        .tab.active { background-color: var(--container-background); font-weight: bold; border-bottom-color: var(--container-background); }
        .close-tab-btn { font-size: 1.4em; font-weight: bold; line-height: 1; padding: 0 4px; border-radius: 50%; } .close-tab-btn:hover { background-color: rgba(220, 220, 220, 0.188); }
        .content-container { border: 1px solid var(--border-color); padding: 30px; border-radius: 0 8px 8px 8px; min-height: 300px; }
        .calculator-content.active { display: flex; } .calculator-content { display: none; }
        #empty-state-message { display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100%; text-align: center; color: var(--text-secondary); }
        .main-layout { display: flex; gap: 40px; width: 100%; }
        .confirmations-column { flex: 1; min-width: 300px; }
        .confirmation-list { list-style: none; padding: 0; margin: 0; }
        .confirmation-list li { margin-bottom: 15px; display: flex; align-items: flex-start; }
        .confirmation-list input[type="checkbox"] { margin-right: 12px; width: 20px; height: 20px; cursor: pointer; flex-shrink: 0; margin-top: 2px; }
        .results-column { flex: 1; border-left: 1px solid var(--border-color); padding-left: 40px; display: flex; flex-direction: column; justify-content: center; }
        .results-header { display: flex; justify-content: center; align-items: center; gap: 10px; flex-wrap: wrap; }
        .results-header h2 { margin: 0; text-align: center; margin-bottom: 25px; flex-basis: 100%; }
        .clean-selection-btn { padding: 4px 8px; font-size: 0.8em; border: 1px solid var(--text-secondary); background-color: transparent; color: var(--text-secondary); border-radius: 5px; cursor: pointer; margin-bottom: 25px; }
        .probability-display { display: flex; justify-content: space-around; text-align: center; margin-top: 25px; }
        .probability-value { font-weight: bold; font-size: 2em; }
        .positive-probability { color: var(--positive-color); } .bar-positive { background-color: var(--positive-color); }
        .negative-probability { color: var(--negative-color); } .bar-negative { background-color: var(--negative-color); }
        .chart-container { width: 100%; background-color: rgba(240, 240, 240, 0.25); border-radius: 5px; display: flex; height: 40px; overflow: hidden; }
        .chart-bar { height: 100%; display: flex; justify-content: center; align-items: center; color: white; font-weight: bold; transition: width 0.4s ease-in-out; }
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 200; justify-content: center; align-items: center; }
        .modal-content { background: var(--modal-background); padding: 30px; border-radius: 10px; width: 90%; max-width: 500px; text-align: center; }
        .modal-content h2 { margin-top: 0; margin-bottom: 15px; }
        #confirm-message { margin: 20px 0; font-size: 1.1em; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px; }
        .modal-actions button { padding: 10px 20px; border: none; border-radius: 5px; color: white; cursor: pointer; font-size: 1em; }
        #confirm-add-index-btn, #add-memo-btn { background-color: var(--tab-active-color); }
        #close-add-index-btn, #close-memo-btn, #confirm-cancel-btn { background-color: #6c757d; }
        #confirm-action-btn { background-color: var(--negative-color); }
        #memo-list { list-style: none; padding: 0; margin: 20px 0; max-height: 30vh; overflow-y: auto; border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); text-align: left; }
        #memo-list li { display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid var(--border-color); }
        .delete-memo-btn { background: none; border: none; color: #dc3545; font-size: 1.2em; cursor: pointer; }
        #new-memo-input, #new-index-name-input { width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 5px; font-size: 1em; box-sizing: border-box; background-color: var(--input-background); color: var(--text-primary); }
        @media (max-width: 900px) { .main-layout, .calculator-content.active { flex-direction: column; } .results-column { border-left: none; padding-left: 0; border-top: 1px solid var(--border-color); padding-top: 30px; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-right-controls">
            <button id="theme-toggle-btn" title="Cambiar Tema"><i class="bi bi-sun-fill theme-icon sun-icon"></i><i class="bi bi-moon-stars-fill theme-icon moon-icon"></i></button>
            <button id="menu-btn" title="Menú"><i class="bi bi-list"></i></button>
        </div>
        <div id="main-menu">
            <button id="menu-add-index">Agregar Operativa</button>
            <button id="menu-show-memos">Frases de Trading</button>
        </div>
        <h1>Calculadora de probabilidad de éxito</h1>
        <p class="note"><strong>Recomendación:</strong> Opera a favor del índice. Para Boom, solo compras. Para Crash, solo ventas.</p>
        <div id="memo-display-container"><p id="memo-display-text"></p></div>
        <div id="tab-container" class="tab-container"></div>
        <div id="content-container" class="content-container">
            <div id="empty-state-message"><h2>Comienza a Calcular</h2><p>Usa el menú (☰) para agregar tu primera operativa. Ejemplo: "Boom 1000 Index".</p></div>
        </div>
    </div>
    <div id="add-index-modal-overlay" class="modal-overlay">
        <div class="modal-content">
            <h2>Agregar Nueva Operativa</h2>
            <input type="text" id="new-index-name-input" placeholder="Nombre del Índice o Activo...">
            <div class="modal-actions"><button id="close-add-index-btn">Cancelar</button><button id="confirm-add-index-btn">Agregar</button></div>
        </div>
    </div>
    <div id="memo-modal-overlay" class="modal-overlay">
        <div class="modal-content">
            <h2>Mis Frases de Trading</h2><ul id="memo-list"></ul><input type="text" id="new-memo-input" placeholder="Escribe una nueva frase o regla..."><div class="modal-actions"><button id="close-memo-btn">Cerrar</button><button id="add-memo-btn">Agregar</button></div>
        </div>
    </div>
    <div id="confirm-modal-overlay" class="modal-overlay">
        <div class="modal-content">
            <h2>Confirmación Requerida</h2><p id="confirm-message">¿Estás seguro?</p><div class="modal-actions"><button id="confirm-cancel-btn">Cancelar</button><button id="confirm-action-btn">Confirmar</button></div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.body; const tabContainer = document.getElementById('tab-container'); const contentContainer = document.getElementById('content-container');
            const emptyStateMessage = document.getElementById('empty-state-message'); const menuBtn = document.getElementById('menu-btn'); const mainMenu = document.getElementById('main-menu');
            const menuAddIndex = document.getElementById('menu-add-index'); const menuShowMemos = document.getElementById('menu-show-memos'); const themeToggleBtn = document.getElementById('theme-toggle-btn');
            const addIndexModalOverlay = document.getElementById('add-index-modal-overlay'); const newIndexNameInput = document.getElementById('new-index-name-input');
            const confirmAddIndexBtn = document.getElementById('confirm-add-index-btn'); const closeAddIndexBtn = document.getElementById('close-add-index-btn');
            const memoModalOverlay = document.getElementById('memo-modal-overlay'); const closeMemoBtn = document.getElementById('close-memo-btn'); const addMemoBtn = document.getElementById('add-memo-btn');
            const newMemoInput = document.getElementById('new-memo-input'); const memoList = document.getElementById('memo-list'); const confirmModalOverlay = document.getElementById('confirm-modal-overlay');
            const confirmMessage = document.getElementById('confirm-message'); const confirmCancelBtn = document.getElementById('confirm-cancel-btn'); const confirmActionBtn = document.getElementById('confirm-action-btn');
            const memoDisplayContainer = document.getElementById('memo-display-container'); const memoDisplayText = document.getElementById('memo-display-text');
            let indexCounter = 0; let tradingMemos = []; let onConfirmAction = null; let currentMemoIndex = 0; let memoIntervalId = null;
            const applyTheme = (theme) => { if (theme === 'dark') body.classList.add('dark-mode'); else body.classList.remove('dark-mode'); };
            themeToggleBtn.addEventListener('click', () => { const newTheme = body.classList.contains('dark-mode') ? 'light' : 'dark'; applyTheme(newTheme); localStorage.setItem('tradingCalculatorTheme', newTheme); });
            menuBtn.addEventListener('click', () => mainMenu.style.display = mainMenu.style.display === 'block' ? 'none' : 'block');
            document.addEventListener('click', (e) => { if (!menuBtn.contains(e.target) && !mainMenu.contains(e.target) && !themeToggleBtn.contains(e.target)) mainMenu.style.display = 'none'; });
            const showConfirmationModal = (message, callback) => { confirmMessage.textContent = message; onConfirmAction = callback; confirmModalOverlay.style.display = 'flex'; };
            const closeConfirmationModal = () => { confirmModalOverlay.style.display = 'none'; onConfirmAction = null; };
            confirmCancelBtn.addEventListener('click', closeConfirmationModal);
            confirmActionBtn.addEventListener('click', () => { if (typeof onConfirmAction === 'function') onConfirmAction(); closeConfirmationModal(); });
            const openAddIndexModal = () => { mainMenu.style.display = 'none'; addIndexModalOverlay.style.display = 'flex'; newIndexNameInput.focus(); };
            const closeAddIndexModal = () => { newIndexNameInput.value = ''; addIndexModalOverlay.style.display = 'none'; };
            const confirmAddIndex = () => { const name = newIndexNameInput.value; if (name) { addIndex(name); closeAddIndexModal(); } };
            menuAddIndex.addEventListener('click', openAddIndexModal); closeAddIndexBtn.addEventListener('click', closeAddIndexModal); confirmAddIndexBtn.addEventListener('click', confirmAddIndex);
            addIndexModalOverlay.addEventListener('click', (e) => { if (e.target === addIndexModalOverlay) closeAddIndexModal(); }); newIndexNameInput.addEventListener('keyup', (e) => { if (e.key === 'Enter') confirmAddIndex(); });
            const initializeMemoCarousel = () => { if (memoIntervalId) clearInterval(memoIntervalId); if (tradingMemos.length > 0) { memoDisplayContainer.style.display = 'block'; currentMemoIndex = 0; memoDisplayText.textContent = tradingMemos[currentMemoIndex]; memoDisplayText.style.opacity = 1; if (tradingMemos.length > 1) { memoIntervalId = setInterval(() => { memoDisplayText.style.opacity = 0; setTimeout(() => { currentMemoIndex = (currentMemoIndex + 1) % tradingMemos.length; memoDisplayText.textContent = tradingMemos[currentMemoIndex]; memoDisplayText.style.opacity = 1; }, 500); }, 10000); } } else { memoDisplayContainer.style.display = 'none'; } };
            const renderMemos = () => { memoList.innerHTML = ''; tradingMemos.forEach((memo, index) => { const li = document.createElement('li'); li.innerHTML = `<span>${memo}</span><button class="delete-memo-btn" data-index="${index}">&times;</button>`; memoList.appendChild(li); }); };
            const showMemoModal = () => { mainMenu.style.display = 'none'; memoModalOverlay.style.display = 'flex'; newMemoInput.focus(); renderMemos(); };
            const closeMemoModal = () => { newMemoInput.value = ''; memoModalOverlay.style.display = 'none'; };
            const addMemo = () => { const memoText = newMemoInput.value.trim(); if (memoText) { tradingMemos.push(memoText); newMemoInput.value = ''; renderMemos(); saveAllData(); newMemoInput.focus(); initializeMemoCarousel(); } };
            const deleteMemo = (index) => { tradingMemos.splice(index, 1); renderMemos(); saveAllData(); initializeMemoCarousel(); };
            menuShowMemos.addEventListener('click', showMemoModal); closeMemoBtn.addEventListener('click', closeMemoModal); memoModalOverlay.addEventListener('click', (e) => { if (e.target === memoModalOverlay) closeMemoModal(); });
            addMemoBtn.addEventListener('click', addMemo); newMemoInput.addEventListener('keyup', (e) => { if (e.key === 'Enter') addMemo(); });
            memoList.addEventListener('click', (e) => { if (e.target.classList.contains('delete-memo-btn')) { const indexToDelete = parseInt(e.target.dataset.index); showConfirmationModal('¿Estás seguro de que quieres eliminar esta frase?', () => deleteMemo(indexToDelete)); } });
            const saveAllData = () => { const tabsData = Array.from(document.querySelectorAll('.calculator-content')).map(content => ({ id: content.id, name: document.querySelector(`.tab[data-target="${content.id}"] span:first-child`).textContent, checkboxes: Array.from(content.querySelectorAll('input[type="checkbox"]')).map(cb => cb.checked) })); localStorage.setItem('tradingCalculatorData', JSON.stringify({ tabs: tabsData, memos: tradingMemos })); };
            const loadData = () => { const savedTheme = localStorage.getItem('tradingCalculatorTheme') || 'light'; applyTheme(savedTheme); const rawData = localStorage.getItem('tradingCalculatorData'); if (!rawData) { updateUIState(); return; } const data = JSON.parse(rawData); tradingMemos = data.memos || []; if (data.tabs && data.tabs.length > 0) { data.tabs.forEach(tabData => addIndex(tabData.name, tabData.checkboxes, false)); switchTab(document.querySelector('.tab').dataset.target); } updateUIState(); initializeMemoCarousel(); };
            
            // --- FUNCIÓN CORREGIDA ---
            const createCalculatorHTML = (indexId, indexName, checkboxStates = []) => {
                const confirmations = ["Estructura (Tendencia primaria H4 y 1D)", "Tendencia Secundaria (H1)", "Fallo de Tendencia a favor (H1 o H4)", "Zona de polaridad (H1 o H4)", "Patrón de Velas (H1 o H4)", "Zona de soporte, resistencia e intermedia (M15 en base a H1)", "Zona Activa (M15)", "Línea de Tendencia (M15 en base a H1)", "Canales (M15 en base a H1)", "Tendencia Terciaria, Acción del precio (M15, M5 y M1)"];
                let checkboxesHTML = confirmations.map((text, i) => { const isChecked = checkboxStates[i] ? 'checked' : ''; return `<li><input type="checkbox" id="c${i}-${indexId}" class="confirmation-checkbox"><label for="c${i}-${indexId}">${text}</label></li>`; }).join('');
                // AQUÍ ESTÁ EL CAMBIO: Se usa `indexName` en el h2
                return `<div class="main-layout"><div class="confirmations-column"><form id="form-${indexId}"><ul class="confirmation-list">${checkboxesHTML}</ul></form></div><div class="results-column"><div class="results-header"><h2>Resultados de ${indexName}</h2><button class="clean-selection-btn">Limpiar</button></div><div class="chart-container"><div class="chart-bar bar-positive"></div><div class="chart-bar bar-negative"></div></div><div class="probability-display"><div><p>Positiva</p><span class="probability-value positive-probability">0%</span></div><div><p>Negativa</p><span class="probability-value negative-probability">100%</span></div></div></div></div>`;
            };
            
            const updateUIState = () => { emptyStateMessage.style.display = tabContainer.children.length === 0 ? 'flex' : 'none'; if (tabContainer.children.length > 0 && !tabContainer.querySelector('.tab.active')) { switchTab(tabContainer.firstElementChild.dataset.target); } saveAllData(); };
            const addIndex = (name, checkboxStates = [], shouldSwitch = true) => {
                const indexName = name.trim(); if (!indexName) return; indexCounter++; const indexId = `index-${indexCounter}`;
                const tab = document.createElement('div'); tab.className = 'tab'; tab.dataset.target = indexId; tab.innerHTML = `<span>${indexName}</span><span class="close-tab-btn">&times;</span>`; tabContainer.appendChild(tab);
                const content = document.createElement('div'); content.id = indexId; content.className = 'calculator-content';
                // AQUÍ ESTÁ EL CAMBIO: Se pasa `indexName` a la función
                content.innerHTML = createCalculatorHTML(indexId, indexName, checkboxStates);
                content.querySelectorAll('.confirmation-checkbox').forEach((cb, i) => { if (checkboxStates[i]) cb.checked = true; }); contentContainer.appendChild(content);
                content.querySelectorAll('.confirmation-checkbox').forEach(cb => cb.addEventListener('change', () => calculateProbability(indexId)));
                content.querySelector('.clean-selection-btn').addEventListener('click', () => cleanSelection(indexId)); calculateProbability(indexId);
                tab.querySelector('span:first-child').addEventListener('click', () => switchTab(indexId));
                tab.querySelector('.close-tab-btn').addEventListener('click', (e) => { e.stopPropagation(); showConfirmationModal(`¿Estás seguro de que quieres eliminar la operativa "${indexName}"?`, () => { const wasActive = tab.classList.contains('active'); tab.remove(); content.remove(); if (wasActive) updateUIState(); else saveAllData(); }); });
                if (shouldSwitch) switchTab(indexId); updateUIState();
            };
            const switchTab = (targetId) => { document.querySelectorAll('.tab').forEach(t => t.classList.remove('active')); const newActiveTab = document.querySelector(`.tab[data-target="${targetId}"]`); if (newActiveTab) newActiveTab.classList.add('active'); document.querySelectorAll('.calculator-content').forEach(c => c.classList.remove('active')); const newActiveContent = document.getElementById(targetId); if (newActiveContent) newActiveContent.classList.add('active'); };
            function calculateProbability(indexId) { const activeContent = document.getElementById(indexId); if (!activeContent) return; const checkboxes = activeContent.querySelectorAll('input[type="checkbox"]'); const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length; let positiveProbability = checkedCount * 10; if (positiveProbability >= 100) positiveProbability = 99; const negativeProbability = 100 - positiveProbability; activeContent.querySelector('.positive-probability').textContent = `${positiveProbability}%`; activeContent.querySelector('.negative-probability').textContent = `${negativeProbability}%`; const positiveBar = activeContent.querySelector('.bar-positive'); const negativeBar = activeContent.querySelector('.bar-negative'); positiveBar.style.width = `${positiveProbability}%`; positiveBar.textContent = positiveProbability > 15 ? `${positiveProbability}%` : ''; negativeBar.style.width = `${negativeProbability}%`; negativeBar.textContent = negativeProbability > 15 ? `${negativeProbability}%` : ''; saveAllData(); }
            function cleanSelection(indexId) { showConfirmationModal('¿Estás seguro de que quieres limpiar la selección?', () => { const activeContent = document.getElementById(indexId); if (!activeContent) return; activeContent.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false); calculateProbability(indexId); }); }
            loadData();
        });
    </script>
</body>
</html>