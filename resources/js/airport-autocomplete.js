// Airport autocomplete for Origin and Destination fields

document.addEventListener('DOMContentLoaded', function () {
    function setupAutocomplete(inputId) {
        const input = document.getElementById(inputId);
        if (!input) return;
        
        // Create suggestion box
        const suggestionBox = document.createElement('div');
        suggestionBox.className = 'autocomplete-suggestions bg-white border border-gray-300 absolute z-50 w-full max-h-60 overflow-y-auto rounded shadow-lg';
        suggestionBox.style.display = 'none';
        input.parentNode.style.position = 'relative';
        input.parentNode.appendChild(suggestionBox);

        input.addEventListener('input', function () {
            const query = input.value.trim();
            if (query.length < 1) {
                suggestionBox.style.display = 'none';
                suggestionBox.innerHTML = '';
                return;
            }
            fetch(`/airport-suggest?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    suggestionBox.innerHTML = '';
                    if (data.length === 0) {
                        suggestionBox.style.display = 'none';
                        return;
                    }
                    data.forEach(airport => {
                        const item = document.createElement('div');
                        item.className = 'px-3 py-2 cursor-pointer hover:bg-blue-100';
                        item.textContent = `${airport.name} (${airport.code}) - ${airport.city}, ${airport.country}`;
                        item.addEventListener('mousedown', function (e) {
                            e.preventDefault();
                            input.value = `${airport.name} (${airport.code})`;
                            suggestionBox.style.display = 'none';
                        });
                        suggestionBox.appendChild(item);
                    });
                    suggestionBox.style.display = 'block';
                });
        });

        // Hide suggestions on blur
        input.addEventListener('blur', function () {
            setTimeout(() => suggestionBox.style.display = 'none', 100);
        });
        // Show suggestions on focus if input has value
        input.addEventListener('focus', function () {
            if (input.value.trim().length > 0 && suggestionBox.innerHTML !== '') {
                suggestionBox.style.display = 'block';
            }
        });
    }
    setupAutocomplete('origin');
    setupAutocomplete('destination');
});
