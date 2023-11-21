const params = new URLSearchParams(window.location.search);
const query = params.get('query');

search(true);

async function search(firstTime = false) {
    const url = new URL('api/eventos/pesquisa', window.location.origin);

    /*
    <div id="bottom-search-bar">
        <div class="container">
            <form id="search-form">
                <div class="input-group">
                    <input type="date" class="form-control" id="dateFilter">

                    <select class="form-control" id="tagFilter">
                        <option value="" selected disabled> Tag</option>
                        <option value="tag1">Tag 1</option>
                        <option value="tag2">Tag 2</option>
                    </select>

                    <input type="text" class="form-control" id="text-input"
                        placeholder="Pesquisa por artistas ou eventos">

                    <div class="input-group-append">
                        <button class="btn" type="button">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
});
*/

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
                <div class="col-md-8">
                    <h3>${startDate.toLocaleString('default', { weekday: 'short' })} • ${startDate.getHours()}:${startDate.getMinutes()}</h3>
                    <h2>${result.name}</h2>
                    <h3>${result.city} • ${result.venue}</h3>
                </div>
                <div class="col-md-2 ml-auto">
                    <button type="button" id="join-event">Aderir ao Evento</button>
                </div>
            </div>`;

        resultsHTML += resultHTML;
    }

    resultsHTML += '</div>';

    const resultsContainer = document.getElementById('search-container');
    resultsContainer.innerHTML = resultsHTML;
}




