const params = new URLSearchParams(window.location.search);
const query = params.get('query');

search(true);

async function search(firstTime = false) {
    const url = new URL('api/eventos/pesquisa', window.location.origin);

    if (firstTime && query != null) {
        url.searchParams.append('query', query);
    }
    else {
        const dateFilter = document.getElementById('dateFilter').value;
        const tagFilter = document.getElementById('tagFilter').value;
        const textInput = document.getElementById('text-input').value;

        url.searchParams.append('query', textInput);
        url.searchParams.append('tags', tagFilter);
        url.searchParams.append('start_date', dateFilter);
    }

    console.log(url);

    const response = await fetch(url);
    const results = await response.json();

    let resultsHTML = `
        <div class="row">
            <div class="col-12">
                <h1 id="results-title">Eventos • ${results.length} Resultados</h1>
            </div>
        </div>
        <div class="card">
    `;

    for (const result of results) {
        const startDate = new Date(result.start_date);
    
        const resultHTML = `
            <div class="row search-result">
                <div class="col-md-2">
                    <h2>${startDate.getDate()} ${startDate.toLocaleString('default', { month: 'long' })}</h2>
                    <h2>${startDate.getFullYear()}</h2>
                </div>
                <div class="col-md-8" onclick="window.location.href = '/evento/${result.id}'" style="cursor: pointer;">
                    <h3>${startDate.toLocaleString('default', { weekday: 'short' })} • ${startDate.getHours()}:${startDate.getMinutes()}</h3>
                    <h2>${result.name}</h2>
                    <h3>${result.city} • ${result.venue}</h3>
                </div>
                <div class="col-md-2 ml-auto">
                    
                    ${result.isParticipating ?
                        `<button type="button" id="button-${result.id}" class="result-button" onclick="leaveEvent(${result.id})">Sair do Evento</button>`
                        :

                        `
                        <button type="button" id="button-${result.id}" class="result-button" onclick="joinEvent(${result.id})">Aderir ao Evento</button>`
                    }
                </div>
            </div>`;
    
        resultsHTML += resultHTML;
    }

    resultsHTML += '</div>';

    const resultsContainer = document.getElementById('search-container');
    resultsContainer.innerHTML = resultsHTML;
}

async function joinEvent(id) {
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const response = await fetch(`/api/evento/${id}/aderir`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
        }
    });

    if (!response.ok) {
        console.error(`Error: ${response.statusText}`);
        return;
    }
    else {
        const button = document.getElementById('button-' + id);
        button.innerHTML = 'Sair do Evento';
        button.onclick = () => leaveEvent(id);
    }
}

async function leaveEvent(id) {
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const response = await fetch(`/api/evento/${id}/sair`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
        }
    });

    if (!response.ok) {
        console.error(`Error: ${response.statusText}`);
        return;
    }
    else {
        const button = document.getElementById('button-' + id);
        button.innerHTML = 'Aderir ao Evento';
        button.onclick = () => joinEvent(id);
    }
}