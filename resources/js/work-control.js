document.getElementById('memberType').addEventListener('change', async function () {
    const type = this.value;
    const nameSuggestions = document.getElementById('nameSuggestions');

    // Clear existing suggestions
    nameSuggestions.innerHTML = '';

    if (type) {
        try {
            const response = await fetch(`/professionals-by-type?type=${encodeURIComponent(type)}`);
            const professionals = await response.json();

            professionals.forEach(professional => {
                const option = document.createElement('option');
                option.value = professional.name;
                nameSuggestions.appendChild(option);
            });
        } catch (error) {
            console.error('Error fetching professionals:', error);
        }
    }
});

document.getElementById('confirmCompletionButton')?.addEventListener('click', async function() {
    try {
        const response = await fetch("{{ route('work.confirmCompletion', ['workId' => $workId]) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
        });
        if (response.ok) {
            document.getElementById('confirmCompletionButton').classList.add('d-none');
            document.getElementById('completionSuccessAlert').classList.remove('d-none');
        }
    } catch (error) {
        console.error('Error confirming project completion:', error);
    }
});

